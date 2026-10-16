<?php

namespace App\Http\Controllers\Logistique;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Immobilisation;
use App\Models\Mouvement;
use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Http\Request;

class LogistiqueMouvementController extends Controller
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
        if ($request->filled('date_debut')) {
            $query->whereDate('date_mouvement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_mouvement', '<=', $request->date_fin);
        }

        $mouvements = $query->latest('date_mouvement')->paginate(15)->withQueryString();

        return view('logistique.mouvements.index', compact('mouvements'));
    }

    public function create(Request $request)
    {
        $actifId = $request->query('actif_id');
        $actifSelectionne = $actifId ? Immobilisation::find($actifId) : null;
        $actifs = Immobilisation::where('statut_operationnel', 'en_service')->orderBy('code_immobilisation')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        $dernierId = Mouvement::max('id') ?? 0;
        $refSuggeree = 'MVT-' . date('Y') . '-' . str_pad($dernierId + 1, 4, '0', STR_PAD_LEFT);

        return view('logistique.mouvements.create', compact('actifSelectionne', 'actifs', 'structures', 'refSuggeree'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'type_mouvement' => 'required|in:transfert_site,affectation_interne,reaffectation,mise_au_rebut,reforme,pret_temporaire,retour_pret',
            'structure_origine_id' => 'required|exists:structures_beneficiaires,id',
            'structure_destination_id' => 'nullable|exists:structures_beneficiaires,id',
            'emplacement_destination' => 'nullable|string|max:255',
            'detenteur_destination_id' => 'nullable|exists:users,id',
            'date_mouvement' => 'required|date',
            'motif' => 'required|string|max:1000',
            'reference_decharge' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
        ]);

        $dernierId = Mouvement::max('id') ?? 0;
        $validated['reference_mouvement'] = 'MVT-' . date('Y') . '-' . str_pad($dernierId + 1, 4, '0', STR_PAD_LEFT);
        $validated['demandeur_id'] = auth()->id();
        $validated['statut_workflow'] = 'soumis';

        $mouvement = Mouvement::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATION_MOUVEMENT',
            'auditable_type' => Mouvement::class,
            'auditable_id' => $mouvement->id,
            'nouvelles_valeurs' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('logistique.mouvements.show', $mouvement->id)
            ->with('success', "Le mouvement {$mouvement->reference_mouvement} a été créé avec succès.");
    }

    public function show($id)
    {
        $mouvement = Mouvement::with([
            'immobilisation.categorie',
            'immobilisation.structureBeneficiaire',
            'structureOrigine',
            'structureDestination',
            'demandeur',
            'valideur',
            'detenteurDestination'
        ])->findOrFail($id);

        return view('logistique.mouvements.show', compact('mouvement'));
    }

    public function valider(Request $request, $id)
    {
        $mouvement = Mouvement::findOrFail($id);

        $mouvement->update([
            'statut_workflow' => 'valide',
            'valideur_id' => auth()->id(),
            'date_validation' => now(),
        ]);

        $actif = $mouvement->immobilisation;
        if ($actif) {
            $anciennesValeurs = $actif->toArray();
            $actif->structure_beneficiaire_id = $mouvement->structure_destination_id ?? $actif->structure_beneficiaire_id;
            if ($mouvement->emplacement_destination) {
                $actif->emplacement_bureau = $mouvement->emplacement_destination;
            }
            if ($mouvement->detenteur_destination_id) {
                $actif->detenteur_id = $mouvement->detenteur_destination_id;
            }
            if ($mouvement->type_mouvement === 'mise_au_rebut') {
                $actif->statut_operationnel = 'mis_au_rebut';
                $actif->etat = 'hors_service';
            } elseif ($mouvement->type_mouvement === 'reforme') {
                $actif->statut_operationnel = 'reforme';
            }
            $actif->save();

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'VALIDATION_MOUVEMENT',
                'auditable_type' => Immobilisation::class,
                'auditable_id' => $actif->id,
                'anciennes_valeurs' => $anciennesValeurs,
                'nouvelles_valeurs' => $actif->toArray(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->route('logistique.mouvements.show', $mouvement->id)
            ->with('success', 'Mouvement validé avec succès. La fiche d\'actif a été mise à jour.');
    }

    public function receptionner(Request $request, $id)
    {
        $mouvement = Mouvement::findOrFail($id);
        $mouvement->update([
            'statut_workflow' => 'receptionne',
            'date_reception' => now(),
            'receptionnaire_id' => auth()->id(),
        ]);

        return redirect()->route('logistique.mouvements.show', $mouvement->id)
            ->with('success', 'Réception confirmée avec succès.');
    }

    public function decharge($id)
    {
        $mouvement = Mouvement::with([
            'immobilisation.categorie',
            'immobilisation.programme',
            'structureOrigine',
            'structureDestination',
            'demandeur',
            'valideur',
            'detenteurDestination'
        ])->findOrFail($id);

        return view('gestionnaire.mouvements.decharge', compact('mouvement'));
    }
}
