<?php

namespace App\Http\Controllers\Admin;

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

        $stats = [
            'total' => CampagneInventaire::count(),
            'en_cours' => CampagneInventaire::where('statut', 'en_cours')->count(),
            'cloturees' => CampagneInventaire::where('statut', 'cloturee')->count(),
            'planifiees' => CampagneInventaire::where('statut', 'planifiee')->count(),
        ];

        return view('admin.inventaires.index', compact('campagnes', 'stats'));
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

        return view('admin.inventaires.show', compact(
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

        return view('admin.inventaires.create', compact('programmes', 'regions', 'agents', 'superviseurs', 'utilisateurs', 'structures', 'codeSuggere'));
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

        AuditLog::logAction('CREATION_CAMPAGNE_INVENTAIRE', 'INVENTAIRES', $campagne->code_campagne, null, $campagne->toArray(), $campagne);

        return redirect()->route('admin.inventaires.show', $campagne->id)
            ->with('success', "La campagne d'inventaire [{$campagne->code_campagne}] a été créée avec succès et attribuée aux agents sélectionnés.");
    }

    public function validerEcart(Request $request, $id)
    {
        $releve = InventaireReleve::findOrFail($id);
        $decision = $request->input('decision');

        $releve->update([
            'statut_rapprochement' => $decision === 'ajuster' ? 'regularise' : 'ecart_confirme',
            'valide_par_user_id' => auth()->id() ?? User::first()?->id,
            'date_validation' => now(),
            'notes_arbitrage' => $request->input('notes_arbitrage')
        ]);

        if ($decision === 'ajuster' && $releve->structure_constatee_id) {
            $releve->immobilisation->update([
                'structure_beneficiaire_id' => $releve->structure_constatee_id,
                'service_id' => $releve->service_constate_id,
                'detenteur_id' => $releve->detenteur_constate_id,
                'etat' => $releve->etat_constate,
                'date_dernier_inventaire' => $releve->date_releve,
                'agent_dernier_inventaire' => $releve->agent?->nom_complet,
                'latitude' => $releve->latitude ?? $releve->immobilisation->latitude,
                'longitude' => $releve->longitude ?? $releve->immobilisation->longitude,
            ]);
        }

        AuditLog::logAction('ARBITRAGE_ECART_INVENTAIRE', 'INVENTAIRES', $releve->immobilisation->code_immobilisation, null, ['decision' => $decision]);

        return redirect()->back()->with('success', "L'arbitrage de l'écart a été enregistré.");
    }

    public function cloturer(Request $request, $id)
    {
        $campagne = CampagneInventaire::findOrFail($id);

        $campagne->update([
            'statut' => 'cloturee',
            'date_cloture' => now(),
            'rapport_cloture' => $request->input('rapport_cloture', 'Campagne clôturée par l\'administrateur.')
        ]);

        AuditLog::logAction('CLOTURE_CAMPAGNE_INVENTAIRE', 'INVENTAIRES', $campagne->code_campagne);

        return redirect()->route('admin.inventaires.show', $campagne->id)
            ->with('success', "La campagne d'inventaire a été clôturée avec succès.");
    }
}
