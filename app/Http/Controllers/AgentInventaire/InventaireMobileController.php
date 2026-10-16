<?php

namespace App\Http\Controllers\AgentInventaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CampagneInventaire;
use App\Models\Detenteur;
use App\Models\Immobilisation;
use App\Models\InventaireReleve;
use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Http\Request;

class InventaireMobileController extends Controller
{
    public function index(Request $request)
    {
        $selectedCampagneId = $request->query('campagne_id');

        $campagnes = CampagneInventaire::with(['programme', 'region', 'releves'])
            ->where('statut', 'en_cours')
            ->orderByDesc('id')
            ->get();

        if ($campagnes->isEmpty()) {
            $campagnes = CampagneInventaire::with(['programme', 'region', 'releves'])
                ->orderByDesc('id')
                ->take(5)
                ->get();
        }

        $activeCampagne = null;
        if ($selectedCampagneId) {
            $activeCampagne = $campagnes->firstWhere('id', $selectedCampagneId) 
                ?? CampagneInventaire::with(['programme', 'region', 'releves'])->find($selectedCampagneId);
        }

        if (!$activeCampagne) {
            $activeCampagne = $campagnes->first();
        }

        $campagneActrice = $activeCampagne;
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        return view('agent_inventaire.mobile.index', compact('campagneActrice', 'activeCampagne', 'campagnes', 'structures'));
    }

    public function scanChercher(Request $request)
    {
        $code = trim($request->input('code'));

        if (empty($code)) {
            return response()->json(['found' => false, 'message' => 'Code vide'], 400);
        }

        $actif = Immobilisation::with(['categorie', 'programme', 'bailleur', 'structureBeneficiaire.region', 'service', 'detenteur'])
            ->where('code_immobilisation', $code)
            ->orWhere('reference_etiquette', $code)
            ->orWhere('qr_token', $code)
            ->orWhere('numero_serie', $code)
            ->first();

        if (!$actif) {
            return response()->json([
                'found' => false,
                'code_scanne' => $code,
                'message' => "Aucun actif correspondant trouvé pour le code [{$code}]."
            ]);
        }

        return response()->json([
            'found' => true,
            'actif' => [
                'id' => $actif->id,
                'code_immobilisation' => $actif->code_immobilisation,
                'reference_etiquette' => $actif->reference_etiquette,
                'designation' => $actif->designation,
                'marque' => $actif->marque,
                'modele' => $actif->modele,
                'numero_serie' => $actif->numero_serie,
                'categorie' => $actif->categorie?->nom,
                'programme' => $actif->programme?->code,
                'bailleur' => $actif->bailleur?->nom,
                'structure_id' => $actif->structure_beneficiaire_id,
                'structure_nom' => $actif->structureBeneficiaire?->nom,
                'service_id' => $actif->service_id,
                'service_nom' => $actif->service?->nom,
                'detenteur_id' => $actif->detenteur_id,
                'detenteur_nom' => $actif->detenteur?->nom_complet ?? 'Non spécifié',
                'etat' => $actif->etat,
                'etat_libelle' => $actif->etat_libelle,
                'niveau_criticite' => $actif->niveau_criticite,
                'valeur' => $actif->valeur_formatee,
                'latitude_theorique' => $actif->latitude,
                'longitude_theorique' => $actif->longitude,
                'photo' => $actif->photo_principale,
            ]
        ]);
    }

    public function storeReleve(Request $request)
    {
        $validated = $request->validate([
            'campagne_id' => 'required|exists:campagnes_inventaire,id',
            'immobilisation_id' => 'nullable|exists:immobilisations,id',
            'code_scanne' => 'required|string|max:150',
            'presence_constatee' => 'required|boolean',
            'etat_constate' => 'required|string',
            'structure_constatee_id' => 'nullable|exists:structures_beneficiaires,id',
            'service_constate_id' => 'nullable|exists:services_structures,id',
            'detenteur_constate_id' => 'nullable|exists:detenteurs,id',
            'emplacement_constate' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'precision_gps' => 'nullable|numeric',
            'photo_constatee' => 'nullable|string',
            'observations' => 'nullable|string',
        ]);

        $agent = auth()->user() ?? User::where('role', 'agent_inventaire')->first() ?? User::first();
        $validated['agent_user_id'] = $agent?->id;
        $validated['date_heure_scan'] = now();

        $statutRapprochement = 'conforme';
        $analyseEcart = 'Conforme';

        if (!$validated['presence_constatee']) {
            $statutRapprochement = 'non_retrouve';
            $analyseEcart = 'Bien non retrouvé sur le site lors du passage de l\'agent.';
        } elseif (empty($validated['immobilisation_id'])) {
            $statutRapprochement = 'nouvel_actif_non_enregistre';
            $analyseEcart = 'Bien présent sur le terrain mais absent du référentiel initial.';
        } else {
            $actif = Immobilisation::find($validated['immobilisation_id']);
            $ecarts = [];

            if ($actif->etat !== $validated['etat_constate']) {
                $ecarts[] = "État physique modifié (Théorique: {$actif->etat_libelle} -> Constaté: {$validated['etat_constate']})";
            }
            if ($validated['structure_constatee_id'] && $actif->structure_beneficiaire_id != $validated['structure_constatee_id']) {
                $structureNom = StructureBeneficiaire::find($validated['structure_constatee_id'])?->nom;
                $ecarts[] = "Structure modifiée (Théorique: {$actif->structureBeneficiaire?->nom} -> Constaté: {$structureNom})";
            }
            if ($validated['detenteur_constate_id'] && $actif->detenteur_id != $validated['detenteur_constate_id']) {
                $detenteurNom = Detenteur::find($validated['detenteur_constate_id'])?->nom_complet;
                $ecarts[] = "Détenteur modifié (Théorique: {$actif->detenteur?->nom_complet} -> Constaté: {$detenteurNom})";
            }

            if (!empty($ecarts)) {
                $statutRapprochement = 'ecart_localisation';
                if ($actif->etat !== $validated['etat_constate']) {
                    $statutRapprochement = 'ecart_etat';
                }
                $analyseEcart = implode(' | ', $ecarts);
            }
        }

        $validated['statut_rapprochement'] = $statutRapprochement;
        $validated['analyse_ecart'] = $analyseEcart;

        $releve = InventaireReleve::create($validated);

        if (!empty($validated['immobilisation_id'])) {
            $actif = Immobilisation::find($validated['immobilisation_id']);
            $updateFields = [
                'date_dernier_inventaire' => now(),
                'agent_dernier_inventaire' => $agent?->name,
            ];
            if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
                $updateFields['latitude'] = $validated['latitude'];
                $updateFields['longitude'] = $validated['longitude'];
                $updateFields['precision_gps'] = $validated['precision_gps'];
                $updateFields['date_derniere_localisation'] = now();
            }
            $actif->update($updateFields);
        }

        AuditLog::logAction('SCAN_MOBILE_INVENTAIRE', 'INVENTAIRE', $validated['code_scanne'], null, $releve->toArray(), $releve);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Relevé enregistré avec succès.',
                'releve_id' => $releve->id,
                'statut_rapprochement' => $statutRapprochement,
            ]);
        }

        return redirect()->route('agent.mobile.index')->with('success', "Le relevé pour [{$validated['code_scanne']}] a été synchronisé.");
    }

    public function offlineData(Request $request)
    {
        $structureId = $request->query('structure_id');

        $query = Immobilisation::with(['categorie', 'programme', 'bailleur', 'structureBeneficiaire', 'detenteur']);
        if ($structureId) {
            $query->where('structure_beneficiaire_id', $structureId);
        }

        $actifs = $query->get()->map(function ($a) {
            return [
                'id' => $a->id,
                'code_immobilisation' => $a->code_immobilisation,
                'reference_etiquette' => $a->reference_etiquette,
                'numero_serie' => $a->numero_serie,
                'designation' => $a->designation,
                'marque' => $a->marque,
                'modele' => $a->modele,
                'categorie' => $a->categorie?->nom,
                'structure_id' => $a->structure_beneficiaire_id,
                'structure_nom' => $a->structureBeneficiaire?->nom,
                'detenteur_nom' => $a->detenteur?->nom_complet,
                'etat' => $a->etat,
                'etat_libelle' => $a->etat_libelle,
                'valeur' => $a->valeur_formatee,
                'latitude' => $a->latitude,
                'longitude' => $a->longitude,
            ];
        });

        return response()->json([
            'timestamp' => now()->toISOString(),
            'total' => $actifs->count(),
            'actifs' => $actifs,
        ]);
    }
}
