<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Immobilisation;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with(['immobilisation.categorie', 'immobilisation.structureBeneficiaire']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type_maintenance')) {
            $query->where('type_maintenance', $request->type_maintenance);
        }

        $maintenances = $query->latest('date_signalement')->paginate(15)->withQueryString();

        $totalEnCours = Maintenance::whereIn('statut', ['signalee', 'en_attente', 'en_cours'])->count();
        $coutTotal = Maintenance::sum('cout_intervention');

        return view('gestionnaire.maintenances.index', compact('maintenances', 'totalEnCours', 'coutTotal'));
    }

    public function create(Request $request)
    {
        $actifId = $request->query('actif_id');
        $actifSelectionne = $actifId ? Immobilisation::find($actifId) : null;
        $actifs = Immobilisation::orderBy('code_immobilisation')->get();

        $dernierId = Maintenance::max('id') ?? 0;
        $numSuggere = 'MAINT-' . date('Y') . '-' . str_pad($dernierId + 1, 4, '0', STR_PAD_LEFT);

        return view('gestionnaire.maintenances.create', compact('actifSelectionne', 'actifs', 'numSuggere'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_fiche' => 'required|string|max:100|unique:maintenances,numero_fiche',
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'type_maintenance' => 'required|string',
            'statut' => 'required|string',
            'date_signalement' => 'required|date',
            'date_intervention' => 'nullable|date',
            'date_fin_intervention' => 'nullable|date',
            'prestataire' => 'nullable|string|max:255',
            'technicien_intervenant' => 'nullable|string|max:255',
            'description_panne' => 'nullable|string',
            'diagnostic' => 'nullable|string',
            'travaux_effectues' => 'nullable|string',
            'pieces_remplacees' => 'nullable|string',
            'cout_intervention' => 'nullable|numeric|min:0',
            'duree_immobilisation_jours' => 'nullable|integer|min:0',
            'date_prochaine_maintenance' => 'nullable|date',
        ]);

        $maint = Maintenance::create($validated);

        $actif = Immobilisation::find($validated['immobilisation_id']);
        if ($actif) {
            if (in_array($validated['statut'], ['signalee', 'en_attente', 'en_cours'])) {
                $actif->update(['etat' => 'en_maintenance', 'statut_operationnel' => 'en_reparation']);
            } elseif ($validated['statut'] === 'terminee') {
                $actif->update(['etat' => 'bon_etat', 'statut_operationnel' => 'en_service']);
            }
        }

        AuditLog::logAction('CREATION_FICHE_MAINTENANCE', 'MAINTENANCE', $maint->numero_fiche, null, $maint->toArray(), $maint);

        return redirect()->route('gestionnaire.maintenances.index')
            ->with('success', "La fiche de maintenance [{$maint->numero_fiche}] a été enregistrée.");
    }

    public function show($id)
    {
        $maintenance = Maintenance::with(['immobilisation.categorie', 'immobilisation.structureBeneficiaire', 'immobilisation.detenteur'])->findOrFail($id);
        return view('gestionnaire.maintenances.show', compact('maintenance'));
    }

    public function cloturer(Request $request, $id)
    {
        $maintenance = Maintenance::with('immobilisation')->findOrFail($id);

        $validated = $request->validate([
            'date_fin_intervention' => 'nullable|date',
            'travaux_effectues' => 'nullable|string',
            'pieces_remplacees' => 'nullable|string',
            'cout_intervention' => 'nullable|numeric|min:0',
        ]);

        $maintenance->update(array_merge($validated, [
            'statut' => 'terminee',
            'date_fin_intervention' => $validated['date_fin_intervention'] ?? now(),
        ]));

        if ($maintenance->immobilisation) {
            $maintenance->immobilisation->update([
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
            ]);
        }

        AuditLog::logAction('CLOTURE_MAINTENANCE', 'MAINTENANCE', $maintenance->numero_fiche, null, ['statut' => 'terminee'], $maintenance);

        return back()->with('success', "La maintenance [{$maintenance->numero_fiche}] a été clôturée avec succès. L'immobilisation est remise en service.");
    }
}
