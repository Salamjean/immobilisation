<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Bailleur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BailleurController extends Controller
{
    public function index(Request $request)
    {
        $query = Bailleur::withCount(['programmes', 'subventions', 'immobilisations']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('contact_nom', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }

        $bailleurs = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Bailleur::count(),
            'actifs' => Bailleur::where('actif', true)->count(),
            'inactifs' => Bailleur::where('actif', false)->count(),
        ];

        return view('gestionnaire.bailleurs.index', compact('bailleurs', 'stats'));
    }

    public function create()
    {
        return view('gestionnaire.bailleurs.create');
    }

    public function store(Request $request)
    {
        if (!$request->filled('code') && $request->filled('nom')) {
            $request->merge(['code' => $this->generateCodeFromName($request->nom)]);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:bailleurs,code',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'contact_nom' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'actif' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['actif'] = $request->has('actif') ? true : false;

        $bailleur = Bailleur::create($validated);

        AuditLog::logAction('CREATION_BAILLEUR', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', null, $bailleur->toArray(), $bailleur);

        return redirect()->route('gestionnaire.bailleurs.index')->with('success', "Le bailleur [{$bailleur->nom}] ({$bailleur->code}) a été créé avec succès.");
    }

    public function edit($id)
    {
        $bailleur = Bailleur::findOrFail($id);
        return view('gestionnaire.bailleurs.edit', compact('bailleur'));
    }

    public function update(Request $request, $id)
    {
        $bailleur = Bailleur::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('bailleurs', 'code')->ignore($bailleur->id)],
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'contact_nom' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['actif'] = $request->has('actif') ? true : false;

        $old = $bailleur->toArray();
        $bailleur->update($validated);

        AuditLog::logAction('MODIFICATION_BAILLEUR', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', $old, $bailleur->toArray(), $bailleur);

        return redirect()->route('gestionnaire.bailleurs.index')->with('success', "Le bailleur [{$bailleur->nom}] a été mis à jour.");
    }

    public function toggleStatus($id)
    {
        $bailleur = Bailleur::findOrFail($id);
        $old = $bailleur->toArray();
        $bailleur->actif = !$bailleur->actif;
        $bailleur->save();

        AuditLog::logAction('STATUT_BAILLEUR', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', $old, $bailleur->toArray(), $bailleur);

        $statut = $bailleur->actif ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "Le bailleur [{$bailleur->nom}] a été {$statut}.");
    }

    private function generateCodeFromName(string $nom): string
    {
        $dateComplete = date('Ymd');
        $stopWords = ['le', 'la', 'les', 'l', 'de', 'du', 'des', 'd', 'un', 'une', 'et', 'ou', 'pour', 'en', 'dans', 'par', 'sur', 'au', 'aux', 'avec', 'sans', 'sous', 'contre'];
        
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nom) ?: $nom;
        preg_match_all('/[a-zA-Z0-9]+/u', $ascii, $matches);
        $words = $matches[0] ?? [];

        if (empty($words)) {
            return 'UCPFM-BL-' . $dateComplete;
        }

        if (count($words) === 1) {
            $word = strtoupper($words[0]);
            $prefix = strlen($word) <= 6 ? $word : substr($word, 0, 4);
            return 'UCPFM-' . $prefix . '-' . $dateComplete;
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

        return 'UCPFM-' . $prefix . '-' . $dateComplete;
    }
}
