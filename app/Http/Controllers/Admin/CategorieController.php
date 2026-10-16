<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CategorieActif;
use App\Models\SousCategorieActif;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        $query = CategorieActif::withCount(['immobilisations', 'sousCategories']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => CategorieActif::count(),
            'total_sous_categories' => SousCategorieActif::count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        if (!$request->filled('code') && $request->filled('nom')) {
            $request->merge(['code' => $this->generateCodeFromName($request->nom)]);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:categories_actifs,code',
            'duree_vie_standard_mois' => 'required|integer|min:1|max:600',
            'description' => 'nullable|string|max:500',
            'icone' => 'nullable|string|max:50',
            'sous_categories' => 'nullable|array',
            'sous_categories.*' => 'nullable|string|max:255',
        ]);

        $categorie = CategorieActif::create([
            'nom' => $validated['nom'],
            'code' => strtoupper(trim($validated['code'])),
            'duree_vie_standard_mois' => $validated['duree_vie_standard_mois'],
            'description' => $validated['description'] ?? null,
            'icone' => $validated['icone'] ?? 'fa-cube',
        ]);

        if (!empty($validated['sous_categories'])) {
            foreach ($validated['sous_categories'] as $index => $sousCatNom) {
                if (!empty(trim($sousCatNom))) {
                    SousCategorieActif::create([
                        'categorie_id' => $categorie->id,
                        'nom' => trim($sousCatNom),
                        'code' => strtoupper($categorie->code . '-S' . ($index + 1)),
                    ]);
                }
            }
        }

        AuditLog::logAction('CREATION_CATEGORIE', 'PARAMETRAGE', auth()->user()?->email ?? 'admin', null, $categorie->toArray(), $categorie);

        return redirect()->route('admin.categories.index')->with('success', "La catégorie [{$categorie->nom}] a été créée avec succès.");
    }

    public function edit($id)
    {
        $categorie = CategorieActif::with('sousCategories')->findOrFail($id);
        return view('admin.categories.edit', compact('categorie'));
    }

    public function update(Request $request, $id)
    {
        $categorie = CategorieActif::with('sousCategories')->findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('categories_actifs', 'code')->ignore($categorie->id)],
            'duree_vie_standard_mois' => 'required|integer|min:1|max:600',
            'description' => 'nullable|string|max:500',
            'icone' => 'nullable|string|max:50',
            'sous_categories' => 'nullable|array',
            'sous_categories.*' => 'nullable|string|max:255',
        ]);

        $old = $categorie->toArray();
        $categorie->update([
            'nom' => $validated['nom'],
            'code' => strtoupper(trim($validated['code'])),
            'duree_vie_standard_mois' => $validated['duree_vie_standard_mois'],
            'description' => $validated['description'] ?? null,
            'icone' => $validated['icone'] ?? 'fa-cube',
        ]);

        if ($request->has('sous_categories')) {
            $submittedNames = array_values(array_filter(array_map('trim', $request->input('sous_categories', []))));
            
            $existingSousCats = $categorie->sousCategories;
            
            // Supprimer les sous-catégories retirées si elles ne sont pas liées à des immobilisations
            foreach ($existingSousCats as $existing) {
                if (!in_array($existing->nom, $submittedNames)) {
                    if ($existing->immobilisations()->count() === 0) {
                        $existing->delete();
                    }
                }
            }
            
            // Ajouter les nouvelles sous-catégories
            $currentExistingNames = $categorie->sousCategories()->pluck('nom')->toArray();
            $index = $categorie->sousCategories()->count();
            foreach ($submittedNames as $subNom) {
                if (!empty($subNom) && !in_array($subNom, $currentExistingNames)) {
                    $index++;
                    SousCategorieActif::create([
                        'categorie_id' => $categorie->id,
                        'nom' => $subNom,
                        'code' => strtoupper($categorie->code . '-S' . $index),
                    ]);
                    $currentExistingNames[] = $subNom;
                }
            }
        }

        AuditLog::logAction('MODIFICATION_CATEGORIE', 'PARAMETRAGE', auth()->user()?->email ?? 'admin', $old, $categorie->toArray(), $categorie);

        return redirect()->route('admin.categories.index')->with('success', "La catégorie [{$categorie->nom}] a été mise à jour.");
    }

    private function generateCodeFromName(string $nom): string
    {
        $stopWords = ['le', 'la', 'les', 'l', 'de', 'du', 'des', 'd', 'un', 'une', 'et', 'ou', 'pour', 'en', 'dans', 'par', 'sur', 'au', 'aux', 'avec', 'sans', 'sous', 'contre', 'materiel', 'equipement'];
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nom) ?: $nom;
        preg_match_all('/[a-zA-Z0-9]+/u', $ascii, $matches);
        $words = $matches[0] ?? [];

        if (empty($words)) {
            return 'CAT-' . date('Ymd');
        }

        if (count($words) === 1) {
            $prefix = strtoupper(substr($words[0], 0, 4));
            return 'CAT-' . $prefix;
        }

        $filtered = array_filter($words, fn($w) => !in_array(strtolower($w), $stopWords));
        $target = !empty($filtered) ? array_values($filtered) : $words;

        if (count($target) === 1) {
            $prefix = strtoupper(substr($target[0], 0, 4));
        } else {
            $prefix = '';
            foreach ($target as $w) {
                $prefix .= strtoupper($w[0]);
            }
            if (strlen($prefix) > 6) {
                $prefix = substr($prefix, 0, 6);
            }
        }

        return 'CAT-' . $prefix;
    }
}
