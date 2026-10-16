<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Bailleur;
use App\Models\Programme;
use App\Models\Subvention;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubventionController extends Controller
{
    public const STATUTS = [
        'en_cours' => 'En cours',
        'cloturee' => 'Clôturée',
        'suspendue' => 'Suspendue',
        'en_attente' => 'En attente',
    ];

    public function index(Request $request)
    {
        $query = Subvention::with(['programme', 'bailleur'])->withCount('immobilisations');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('intitule', 'like', "%{$search}%")
                  ->orWhere('code_subvention', 'like', "%{$search}%");
            });
        }

        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->programme_id);
        }

        if ($request->filled('bailleur_id')) {
            $query->where('bailleur_id', $request->bailleur_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $subventions = $query->latest()->paginate(10)->withQueryString();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $bailleurs = Bailleur::where('actif', true)->orderBy('nom')->get();

        $stats = [
            'total' => Subvention::count(),
            'en_cours' => Subvention::where('statut', 'en_cours')->count(),
            'cloturees' => Subvention::where('statut', 'cloturee')->count(),
            'montant_total' => Subvention::sum('montant_alloue'),
        ];

        $statuts = self::STATUTS;

        return view('gestionnaire.subventions.index', compact('subventions', 'programmes', 'bailleurs', 'stats', 'statuts'));
    }

    public function create()
    {
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $bailleurs = Bailleur::where('actif', true)->orderBy('nom')->get();
        $statuts = self::STATUTS;

        return view('gestionnaire.subventions.create', compact('programmes', 'bailleurs', 'statuts'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('code_subvention') && $request->filled('intitule')) {
            $request->merge(['code_subvention' => $this->generateCodeSubvention($request)]);
        }

        $validated = $request->validate([
            'code_subvention' => 'required|string|max:100|unique:subventions,code_subvention',
            'intitule' => 'required|string|max:255',
            'programme_id' => 'required|exists:programmes,id',
            'bailleur_id' => 'required|exists:bailleurs,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'montant_alloue' => 'nullable|numeric|min:0',
            'devise' => 'required|string|max:10',
            'statut' => 'required|string|in:en_cours,cloturee,suspendue,en_attente',
        ]);

        $validated['code_subvention'] = strtoupper(trim($validated['code_subvention']));
        $validated['montant_alloue'] = $validated['montant_alloue'] ?? 0;

        $subvention = Subvention::create($validated);

        AuditLog::logAction('CREATION_SUBVENTION', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', null, $subvention->toArray(), $subvention);

        return redirect()->route('gestionnaire.subventions.index')->with('success', "La subvention [{$subvention->intitule}] ({$subvention->code_subvention}) a été créée avec succès.");
    }

    private function generateCodeSubvention(Request $request): string
    {
        $prog = Programme::find($request->programme_id);
        $bail = Bailleur::find($request->bailleur_id);

        $progCode = $prog ? $prog->code : 'PRG';
        $bailCode = $bail ? $bail->code : 'BL';
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -4));

        return "SUB-{$bailCode}-{$progCode}-{$year}-{$random}";
    }

    public function edit($id)
    {
        $subvention = Subvention::findOrFail($id);
        $programmes = Programme::orderBy('nom')->get();
        $bailleurs = Bailleur::orderBy('nom')->get();
        $statuts = self::STATUTS;

        return view('gestionnaire.subventions.edit', compact('subvention', 'programmes', 'bailleurs', 'statuts'));
    }

    public function update(Request $request, $id)
    {
        $subvention = Subvention::findOrFail($id);

        $validated = $request->validate([
            'code_subvention' => ['required', 'string', 'max:100', Rule::unique('subventions', 'code_subvention')->ignore($subvention->id)],
            'intitule' => 'required|string|max:255',
            'programme_id' => 'required|exists:programmes,id',
            'bailleur_id' => 'required|exists:bailleurs,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'montant_alloue' => 'nullable|numeric|min:0',
            'devise' => 'required|string|max:10',
            'statut' => 'required|string|in:en_cours,cloturee,suspendue,en_attente',
        ]);

        $validated['code_subvention'] = strtoupper(trim($validated['code_subvention']));
        $validated['montant_alloue'] = $validated['montant_alloue'] ?? 0;

        $old = $subvention->toArray();
        $subvention->update($validated);

        AuditLog::logAction('MODIFICATION_SUBVENTION', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', $old, $subvention->toArray(), $subvention);

        return redirect()->route('gestionnaire.subventions.index')->with('success', "La subvention [{$subvention->intitule}] a été mise à jour.");
    }

    public function destroy($id)
    {
        $subvention = Subvention::withCount('immobilisations')->findOrFail($id);

        if ($subvention->immobilisations_count > 0) {
            return redirect()->back()->with('error', "Impossible de supprimer cette subvention car des immobilisations y sont rattachées.");
        }

        $old = $subvention->toArray();
        $subvention->delete();

        AuditLog::logAction('SUPPRESSION_SUBVENTION', 'REFERENTIEL', auth()->user()?->email ?? 'gestionnaire', $old, null, $subvention);

        return redirect()->route('gestionnaire.subventions.index')->with('success', "La subvention a été supprimée avec succès.");
    }
}
