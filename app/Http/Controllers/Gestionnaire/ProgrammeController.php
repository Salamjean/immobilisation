<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Bailleur;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgrammeController extends Controller
{
    public function index(Request $request)
    {
        $query = Programme::with('bailleur')->withCount(['subventions', 'immobilisations']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('coordonnateur', 'like', "%{$search}%");
            });
        }

        if ($request->filled('bailleur_id')) {
            $query->where('bailleur_id', $request->bailleur_id);
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }

        $programmes = $query->latest()->paginate(10)->withQueryString();
        $bailleurs = Bailleur::where('actif', true)->orderBy('nom')->get();

        $stats = [
            'total' => Programme::count(),
            'actifs' => Programme::where('actif', true)->count(),
            'inactifs' => Programme::where('actif', false)->count(),
        ];

        return view('gestionnaire.programmes.index', compact('programmes', 'bailleurs', 'stats'));
    }

    public function create()
    {
        $bailleurs = Bailleur::where('actif', true)->orderBy('nom')->get();
        return view('gestionnaire.programmes.create', compact('bailleurs'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('code') && $request->filled('nom')) {
            $request->merge(['code' => $this->generateCodeFromName($request->nom)]);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:programmes,code',
            'nom' => 'required|string|max:255',
            'bailleur_id' => 'nullable|exists:bailleurs,id',
            'coordonnateur' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'actif' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['actif'] = $request->has('actif') ? (bool) $request->actif : true;

        $programme = Programme::create($validated);

        AuditLog::logAction('CREATION_PROGRAMME', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', null, $programme->toArray(), $programme);

        return redirect()->route('gestionnaire.programmes.index')->with('success', "Le programme [{$programme->nom}] ({$programme->code}) a été créé avec succès.");
    }

    public function edit($id)
    {
        $programme = Programme::findOrFail($id);
        $bailleurs = Bailleur::orderBy('nom')->get();
        return view('gestionnaire.programmes.edit', compact('programme', 'bailleurs'));
    }

    public function update(Request $request, $id)
    {
        $programme = Programme::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('programmes', 'code')->ignore($programme->id)],
            'nom' => 'required|string|max:255',
            'bailleur_id' => 'nullable|exists:bailleurs,id',
            'coordonnateur' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'actif' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['actif'] = $request->has('actif') ? (bool) $request->actif : false;

        $old = $programme->toArray();
        $programme->update($validated);

        AuditLog::logAction('MODIFICATION_PROGRAMME', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', $old, $programme->toArray(), $programme);

        return redirect()->route('gestionnaire.programmes.index')->with('success', "Le programme [{$programme->nom}] a été mis à jour.");
    }

    public function toggleStatus($id)
    {
        $programme = Programme::findOrFail($id);
        $old = $programme->toArray();
        $programme->actif = !$programme->actif;
        $programme->save();

        AuditLog::logAction('STATUT_PROGRAMME', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', $old, $programme->toArray(), $programme);

        $statut = $programme->actif ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "Le programme [{$programme->nom}] a été {$statut}.");
    }

    public function destroy($id)
    {
        $programme = Programme::withCount(['subventions', 'immobilisations'])->findOrFail($id);

        if ($programme->subventions_count > 0 || $programme->immobilisations_count > 0) {
            return redirect()->back()->with('error', "Impossible de supprimer ce programme car il contient des subventions ou des immobilisations associées.");
        }

        $old = $programme->toArray();
        $programme->delete();

        AuditLog::logAction('SUPPRESSION_PROGRAMME', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', $old, null, $programme);

        return redirect()->route('gestionnaire.programmes.index')->with('success', "Le programme a été supprimé avec succès.");
    }

    private function generateCodeFromName(string $nom): string
    {
        $dateComplete = date('Ymd');
        $stopWords = ['le', 'la', 'les', 'l', 'de', 'du', 'des', 'd', 'un', 'une', 'et', 'ou', 'pour', 'en', 'dans', 'par', 'sur', 'au', 'aux', 'avec', 'sans', 'sous', 'contre', 'programme', 'projet'];
        
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nom) ?: $nom;
        preg_match_all('/[a-zA-Z0-9]+/u', $ascii, $matches);
        $words = $matches[0] ?? [];

        if (empty($words)) {
            return 'PRG-' . $dateComplete;
        }

        if (count($words) === 1) {
            $word = strtoupper($words[0]);
            $prefix = strlen($word) <= 6 ? $word : substr($word, 0, 4);
            return 'PRG-' . $prefix . '-' . $dateComplete;
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

        return 'PRG-' . $prefix . '-' . $dateComplete;
    }
}
