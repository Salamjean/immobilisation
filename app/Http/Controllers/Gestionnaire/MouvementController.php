<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Immobilisation;
use App\Models\Mouvement;
use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Http\Request;

class MouvementController extends Controller
{
    public function index(Request $request)
    {
        $query = Mouvement::with(['immobilisation.categorie', 'structureOrigine', 'structureDestination', 'demandeur', 'valideur']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_mouvement', 'like', "%{$search}%")
                  ->orWhereHas('immobilisation', function ($iq) use ($search) {
                      $iq->where('designation', 'like', "%{$search}%")
                         ->orWhere('code_immobilisation', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }
        if ($request->filled('statut_workflow')) {
            $query->where('statut_workflow', $request->statut_workflow);
        }
        if ($request->filled('date_mouvement')) {
            $query->whereDate('date_mouvement', $request->date_mouvement);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date_mouvement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_mouvement', '<=', $request->date_fin);
        }

        $mouvements = $query->latest('date_mouvement')->paginate(15)->withQueryString();

        return view('gestionnaire.mouvements.index', compact('mouvements'));
    }

    public function create(Request $request)
    {
        $actifId = $request->query('actif_id');
        $actifSelectionne = $actifId ? Immobilisation::find($actifId) : null;
        $actifs = Immobilisation::where('statut_operationnel', 'en_service')->orderBy('code_immobilisation')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        $dernierId = Mouvement::max('id') ?? 0;
        $refSuggeree = 'MVT-' . date('Y') . '-' . str_pad($dernierId + 1, 4, '0', STR_PAD_LEFT);

        return view('gestionnaire.mouvements.create', compact('actifSelectionne', 'actifs', 'structures', 'refSuggeree'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_mouvement' => 'required|string|max:100|unique:mouvements,reference_mouvement',
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'type_mouvement' => 'required|string',
            'structure_destination_id' => 'nullable|exists:structures_beneficiaires,id',
            'service_destination_id' => 'nullable|exists:services_structures,id',
            'detenteur_destination_id' => 'nullable|exists:detenteurs,id',
            'date_mouvement' => 'required|date',
            'motif' => 'required|string',
            'commentaires' => 'nullable|string',
        ]);

        $actif = Immobilisation::findOrFail($validated['immobilisation_id']);

        $validated['structure_origine_id'] = $actif->structure_beneficiaire_id;
        $validated['service_origine_id'] = $actif->service_id;
        $validated['detenteur_origine_id'] = $actif->detenteur_id;
        $validated['demande_par_user_id'] = auth()->id() ?? User::first()?->id;
        $validated['statut_workflow'] = 'soumis';

        $mvt = Mouvement::create($validated);

        AuditLog::logAction('DEMANDE_MOUVEMENT', 'MOUVEMENTS', $mvt->reference_mouvement, null, $mvt->toArray(), $mvt);

        return redirect()->route('gestionnaire.mouvements.index')
            ->with('success', "La demande de mouvement [{$mvt->reference_mouvement}] a été enregistrée et soumise à validation.");
    }

    public function show($id)
    {
        $mouvement = Mouvement::with([
            'immobilisation.categorie',
            'structureOrigine',
            'serviceOrigine',
            'detenteurOrigine',
            'structureDestination',
            'serviceDestination',
            'detenteurDestination',
            'demandeur',
            'valideur',
            'recepteur'
        ])->findOrFail($id);

        return view('gestionnaire.mouvements.show', compact('mouvement'));
    }

    public function decharge($id)
    {
        $mouvement = Mouvement::with([
            'immobilisation.categorie',
            'immobilisation.programme',
            'structureOrigine',
            'serviceOrigine',
            'detenteurOrigine',
            'structureDestination',
            'serviceDestination',
            'detenteurDestination',
            'demandeur',
            'valideur',
            'recepteur'
        ])->findOrFail($id);

        return view('gestionnaire.mouvements.decharge', compact('mouvement'));
    }

    public function valider(Request $request, $id)
    {
        $mvt = Mouvement::with('immobilisation')->findOrFail($id);
        $action = $request->input('action', 'valide');

        if ($action === 'valide') {
            $nouveauStatut = ($mvt->type_mouvement === 'reforme') ? 'reforme_validee' : 'en_transit';

            $mvt->update([
                'statut_workflow' => $nouveauStatut,
                'valide_par_user_id' => auth()->id() ?? User::first()?->id,
                'date_validation' => now(),
            ]);

            if ($mvt->immobilisation) {
                if ($mvt->type_mouvement === 'reforme') {
                    $mvt->immobilisation->update([
                        'statut_operationnel' => 'mis_au_rebut',
                        'etat' => 'reforme',
                        'observations' => ($mvt->immobilisation->observations ? $mvt->immobilisation->observations . "\n" : '') . "Réformé le " . now()->format('d/m/Y') . " (Réf: {$mvt->reference_mouvement}) : {$mvt->motif}",
                    ]);
                } else {
                    $mvt->immobilisation->update([
                        'statut_operationnel' => 'en_transit',
                        'etat' => 'transfere',
                    ]);
                }
            }

            AuditLog::logAction('VALIDATION_MOUVEMENT', 'MOUVEMENTS', $mvt->reference_mouvement, null, ['statut' => $nouveauStatut], $mvt);

            $msg = ($mvt->type_mouvement === 'reforme')
                ? "La réforme de l'actif [{$mvt->immobilisation?->code_immobilisation}] a été validée et actée au registre."
                : "Le transfert a été validé. L'actif est actuellement en transit vers la destination.";

            return back()->with('success', $msg);
        } else {
            $mvt->update([
                'statut_workflow' => 'rejete',
                'valide_par_user_id' => auth()->id() ?? User::first()?->id,
                'date_validation' => now(),
            ]);

            AuditLog::logAction('REJET_MOUVEMENT', 'MOUVEMENTS', $mvt->reference_mouvement, null, ['statut' => 'rejete'], $mvt);

            return back()->with('warning', 'La demande de mouvement a été rejetée.');
        }
    }

    public function receptionner(Request $request, $id)
    {
        $mvt = Mouvement::with('immobilisation', 'structureDestination')->findOrFail($id);

        $mvt->update([
            'statut_workflow' => 'receptionne',
            'receptionne_par_user_id' => auth()->id() ?? User::first()?->id,
            'date_reception_effective' => now(),
        ]);

        if ($mvt->immobilisation) {
            $destStructure = $mvt->structureDestination;
            $mvt->immobilisation->update([
                'structure_beneficiaire_id' => $mvt->structure_destination_id,
                'service_id' => $mvt->service_destination_id,
                'detenteur_id' => $mvt->detenteur_destination_id,
                'date_affectation' => now(),
                'statut_operationnel' => 'en_service',
                'etat' => 'bon_etat',
                'latitude' => $destStructure?->latitude ?? $mvt->immobilisation->latitude,
                'longitude' => $destStructure?->longitude ?? $mvt->immobilisation->longitude,
                'date_derniere_localisation' => now(),
            ]);
        }

        AuditLog::logAction('RECEPTION_MOUVEMENT', 'MOUVEMENTS', $mvt->reference_mouvement, null, ['statut' => 'receptionne'], $mvt);

        return back()->with('success', 'Réception confirmée ! La localisation et le détenteur du bien ont été automatiquement mis à jour.');
    }
}
