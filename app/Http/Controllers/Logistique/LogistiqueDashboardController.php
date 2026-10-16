<?php

namespace App\Http\Controllers\Logistique;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\Immobilisation;
use App\Models\Mouvement;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogistiqueDashboardController extends Controller
{
    public function index()
    {
        $totalMouvements = Mouvement::count();
        $mouvementsEnAttente = Mouvement::where('statut_workflow', 'soumis')->count();
        $mouvementsValides = Mouvement::where('statut_workflow', 'valide')->count();
        $mouvementsEnTransit = Mouvement::whereIn('type_mouvement', ['transfert_site', 'reaffectation'])->count();

        $totalMaintenances = DB::table('maintenances')->count();
        $maintenancesEnCours = DB::table('maintenances')->whereIn('statut', ['signalee', 'en_attente', 'en_cours'])->count();

        $totalActifs = Immobilisation::count();
        $actifsEnService = Immobilisation::where('statut_operationnel', 'en_service')->count();
        $totalStructures = StructureBeneficiaire::where('actif', true)->count();

        $derniersMouvements = Mouvement::with(['immobilisation.categorie', 'structureOrigine', 'structureDestination', 'demandeur'])
            ->latest('date_mouvement')
            ->take(8)
            ->get();

        $dernieresMaintenances = DB::table('maintenances')
            ->join('immobilisations', 'maintenances.immobilisation_id', '=', 'immobilisations.id')
            ->select('maintenances.*', 'immobilisations.code_immobilisation', 'immobilisations.designation')
            ->orderByDesc('maintenances.created_at')
            ->take(5)
            ->get();

        $pointsCarte = Immobilisation::with(['structureBeneficiaire', 'categorie'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'code_immobilisation', 'designation', 'latitude', 'longitude', 'etat', 'niveau_criticite', 'structure_beneficiaire_id', 'categorie_id']);

        return view('logistique.dashboard', compact(
            'totalMouvements',
            'mouvementsEnAttente',
            'mouvementsValides',
            'mouvementsEnTransit',
            'totalMaintenances',
            'maintenancesEnCours',
            'totalActifs',
            'actifsEnService',
            'totalStructures',
            'derniersMouvements',
            'dernieresMaintenances',
            'pointsCarte'
        ));
    }
}
