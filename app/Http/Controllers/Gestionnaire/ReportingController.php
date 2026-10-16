<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Bailleur;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\InventaireReleve;
use App\Models\Programme;
use App\Models\Region;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $totalBiens = Immobilisation::count();
        $valeurGlobale = Immobilisation::sum('valeur_acquisition');

        $statsProgrammes = Programme::withCount('immobilisations')
            ->withSum('immobilisations', 'valeur_acquisition')
            ->get();

        $statsBailleurs = Bailleur::withCount('immobilisations')
            ->withSum('immobilisations', 'valeur_acquisition')
            ->get();

        $statsCategories = CategorieActif::withCount('immobilisations')
            ->withSum('immobilisations', 'valeur_acquisition')
            ->get();

        $statsRegions = Region::withCount(['structures as total_structures'])->get();

        $actifsCritiques = Immobilisation::with(['categorie', 'structureBeneficiaire', 'programme'])
            ->whereIn('niveau_criticite', ['critique', 'eleve'])
            ->orderByDesc('valeur_acquisition')
            ->get();

        $actifsDefaillants = Immobilisation::with(['categorie', 'structureBeneficiaire'])
            ->whereIn('etat', ['panne', 'hors_service', 'en_maintenance'])
            ->get();

        $derniersEcarts = InventaireReleve::with(['campagne', 'immobilisation', 'structureConstatee'])
            ->where('statut_rapprochement', '!=', 'conforme')
            ->latest()
            ->take(10)
            ->get();

        return view('gestionnaire.reporting.index', compact(
            'totalBiens',
            'valeurGlobale',
            'statsProgrammes',
            'statsBailleurs',
            'statsCategories',
            'statsRegions',
            'actifsCritiques',
            'actifsDefaillants',
            'derniersEcarts'
        ));
    }

    public function registreOfficiel()
    {
        $actifs = Immobilisation::with(['categorie', 'programme', 'bailleur', 'subvention', 'structureBeneficiaire.region', 'service', 'detenteur'])
            ->orderBy('code_immobilisation')
            ->get();

        $valeurTotale = $actifs->sum('valeur_acquisition');

        return view('gestionnaire.reporting.registre', compact('actifs', 'valeurTotale'));
    }
}
