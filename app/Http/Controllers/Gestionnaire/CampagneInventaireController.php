<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CampagneInventaire;
use App\Models\Immobilisation;
use App\Models\InventaireEquipe;
use App\Models\InventaireReleve;
use App\Models\Programme;
use App\Models\Region;
use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Http\Request;

class CampagneInventaireController extends Controller
{
    public function index()
    {
        $campagnes = CampagneInventaire::with(['programme', 'region', 'superviseur'])
            ->withCount(['releves', 'equipes'])
            ->latest()
            ->paginate(10);

        return view('gestionnaire.inventaires.index', compact('campagnes'));
    }

    public function show($id)
    {
        $campagne = CampagneInventaire::with([
            'programme',
            'region',
            'superviseur',
            'equipes.user',
            'equipes.structure',
            'releves.immobilisation.categorie',
            'releves.structureConstatee',
            'releves.agent',
            'releves.validateur'
        ])->findOrFail($id);

        $totalBiensTheoriques = Immobilisation::count();
        $totalReleves = $campagne->releves()->count();
        $totalConformes = $campagne->releves()->where('statut_rapprochement', 'conforme')->count();
        $totalEcarts = $campagne->releves()->where('statut_rapprochement', '!=', 'conforme')->count();
        $totalNonRetrouves = $campagne->releves()->where('statut_rapprochement', 'non_retrouve')->count();

        $tauxProgression = $totalBiensTheoriques > 0 ? round(($totalReleves / $totalBiensTheoriques) * 100, 1) : 0;
        $tauxConformite = $totalReleves > 0 ? round(($totalConformes / $totalReleves) * 100, 1) : 0;

        return view('gestionnaire.inventaires.show', compact(
            'campagne',
            'totalBiensTheoriques',
            'totalReleves',
            'totalConformes',
            'totalEcarts',
            'totalNonRetrouves',
            'tauxProgression',
            'tauxConformite'
        ));
    }

    public function create()
    {
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $regions = Region::orderBy('nom')->get();
        $agents = User::where('actif', true)->where('role', User::ROLE_AGENT_INVENTAIRE)->orderBy('name')->get();
        $superviseurs = User::where('actif', true)->whereIn('role', [
            User::ROLE_AUDITEUR,
            User::ROLE_CONSULTANT,
            User::ROLE_LECTEUR,
        ])->orderBy('name')->get();
        $utilisateurs = $superviseurs;
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        $dernierId = CampagneInventaire::max('id') ?? 0;
        $codeSuggere = 'INV-' . date('Y') . '-' . str_pad($dernierId + 1, 3, '0', STR_PAD_LEFT);

        return view('gestionnaire.inventaires.create', compact('programmes', 'regions', 'agents', 'superviseurs', 'utilisateurs', 'structures', 'codeSuggere'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_campagne' => 'required|string|max:100|unique:campagnes_inventaire,code_campagne',
            'intitule' => 'required|string|max:255',
            'type_campagne' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'programme_id' => 'nullable|exists:programmes,id',
            'region_id' => 'nullable|exists:regions,id',
            'superviseur_id' => 'nullable|exists:users,id',
            'objectifs' => 'nullable|string',
            'agents' => 'required|array|min:1',
            'agents.*' => 'required|exists:users,id',
        ], [
            'agents.required' => 'Vous devez obligatoirement sélectionner au moins un agent d\'inventaire à attribuer à la campagne.',
            'agents.min' => 'Vous devez sélectionner au moins un agent pour mener le recensement terrain.',
            'agents.*.exists' => "L'un des agents sélectionnés n'est pas valide.",
        ]);

        $campagneData = collect($validated)->except(['agents'])->toArray();
        $campagneData['statut'] = 'en_cours';

        $campagne = CampagneInventaire::create($campagneData);

        // Enregistrement des agents affectés à la campagne
        $agentIds = array_unique(array_filter($request->input('agents', [])));
        foreach ($agentIds as $userId) {
            InventaireEquipe::create([
                'campagne_id' => $campagne->id,
                'user_id' => $userId,
                'role_equipe' => 'agent_recenseur',
            ]);
        }

        AuditLog::logAction('CREATION_CAMPAGNE_INVENTAIRE', 'INVENTAIRE', $campagne->code_campagne, null, $campagne->toArray(), $campagne);

        return redirect()->route('gestionnaire.inventaires.show', $campagne->id)
            ->with('success', "La campagne d'inventaire [{$campagne->code_campagne}] a été lancée avec succès et attribuée aux agents sélectionnés.");
    }

    public function validerEcart(Request $request, $id)
    {
        $releve = InventaireReleve::with('immobilisation')->findOrFail($id);

        $validated = $request->validate([
            'decision_regularisation' => 'required|string',
            'analyse_ecart' => 'nullable|string',
        ]);

        $releve->update([
            'decision_regularisation' => $validated['decision_regularisation'],
            'analyse_ecart' => $validated['analyse_ecart'] ?? $releve->analyse_ecart,
            'valide_par_user_id' => auth()->id() ?? User::where('role', 'admin')->first()?->id,
            'date_validation_rapprochement' => now(),
        ]);

        if ($validated['decision_regularisation'] === 'regularise' && $releve->immobilisation) {
            $actif = $releve->immobilisation;
            $oldData = $actif->toArray();

            $actif->update([
                'etat' => $releve->etat_constate,
                'structure_beneficiaire_id' => $releve->structure_constatee_id ?? $actif->structure_beneficiaire_id,
                'service_id' => $releve->service_constate_id ?? $actif->service_id,
                'detenteur_id' => $releve->detenteur_constate_id ?? $actif->detenteur_id,
                'latitude' => $releve->latitude ?? $actif->latitude,
                'longitude' => $releve->longitude ?? $actif->longitude,
                'precision_gps' => $releve->precision_gps ?? $actif->precision_gps,
                'date_derniere_localisation' => now(),
                'date_dernier_inventaire' => $releve->date_heure_scan ?? now(),
            ]);

            AuditLog::logAction('REGULARISATION_INVENTAIRE', 'IMMOBILISATIONS', $actif->code_immobilisation, $oldData, $actif->fresh()->toArray(), $actif);
        }

        return back()->with('success', 'Décision de rapprochement enregistrée avec succès.');
    }

    public function cloturer($id)
    {
        $campagne = CampagneInventaire::findOrFail($id);
        $campagne->update([
            'statut' => 'cloturee',
            'date_cloture' => now(),
        ]);

        AuditLog::logAction('CLOTURE_CAMPAGNE_INVENTAIRE', 'INVENTAIRE', $campagne->code_campagne, null, ['statut' => 'cloturee'], $campagne);

        return back()->with('success', "La campagne [{$campagne->code_campagne}] a été clôturée. Les données sont désormais verrouillées.");
    }
}
