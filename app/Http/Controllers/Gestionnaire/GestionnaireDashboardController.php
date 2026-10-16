<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\CampagneInventaire;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\Mouvement;
use App\Models\Programme;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionnaireDashboardController extends Controller
{
    public function index()
    {
        $totalActifs = Immobilisation::count();
        $valeurTotale = Immobilisation::sum('valeur_acquisition');
        $actifsCritiques = Immobilisation::whereIn('niveau_criticite', ['critique', 'eleve'])->count();
        $actifsEnPanne = Immobilisation::whereIn('etat', ['panne', 'hors_service', 'en_maintenance'])->count();
        $totalSites = StructureBeneficiaire::count();
        $totalProgrammes = Programme::count();

        $actifsGeolocalises = Immobilisation::whereNotNull('latitude')->whereNotNull('longitude')->count();
        $tauxGeolocalisation = $totalActifs > 0 ? round(($actifsGeolocalises / $totalActifs) * 100, 1) : 0;

        $actifsEtiquetes = Immobilisation::whereNotNull('reference_etiquette')->count();
        $tauxEtiquetage = $totalActifs > 0 ? round(($actifsEtiquetes / $totalActifs) * 100, 1) : 0;

        $actifsInventories = Immobilisation::whereNotNull('date_dernier_inventaire')->count();
        $tauxInventaire = $totalActifs > 0 ? round(($actifsInventories / $totalActifs) * 100, 1) : 0;

        $parProgramme = Programme::withCount('immobilisations')
            ->withSum('immobilisations', 'valeur_acquisition')
            ->get();

        $parCategorie = CategorieActif::withCount('immobilisations')
            ->withSum('immobilisations', 'valeur_acquisition')
            ->get();

        $parEtat = Immobilisation::select('etat', DB::raw('count(*) as total'), DB::raw('sum(valeur_acquisition) as valeur'))
            ->groupBy('etat')
            ->get();

        $parCriticite = Immobilisation::select('niveau_criticite', DB::raw('count(*) as total'), DB::raw('sum(valeur_acquisition) as valeur'))
            ->groupBy('niveau_criticite')
            ->get();

        $derniersMouvements = Mouvement::with(['immobilisation', 'structureOrigine', 'structureDestination'])
            ->latest('date_mouvement')
            ->take(5)
            ->get();

        $alertesActives = Alerte::with('immobilisation')
            ->where('lue', false)
            ->latest()
            ->take(6)
            ->get();

        $campagneEnCours = CampagneInventaire::withCount(['releves', 'equipes'])
            ->where('statut', 'en_cours')
            ->first();

        $dernieresImmobilisations = Immobilisation::with(['categorie', 'structureBeneficiaire', 'programme'])
            ->latest()
            ->take(5)
            ->get();

        $mouvementsEnAttenteCount = Mouvement::where('statut_workflow', 'soumis')->count();
        $maintenancesEnCoursCount = DB::table('maintenances')->whereIn('statut', ['signalee', 'en_attente', 'en_cours'])->count();

        $pointsCarte = Immobilisation::with(['structureBeneficiaire', 'categorie', 'programme'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'code_immobilisation', 'designation', 'latitude', 'longitude', 'etat', 'niveau_criticite', 'valeur_acquisition', 'structure_beneficiaire_id', 'categorie_id', 'programme_id']);

        return view('gestionnaire.dashboard.index', compact(
            'totalActifs',
            'valeurTotale',
            'actifsCritiques',
            'actifsEnPanne',
            'totalSites',
            'totalProgrammes',
            'tauxGeolocalisation',
            'tauxEtiquetage',
            'tauxInventaire',
            'parProgramme',
            'parCategorie',
            'parEtat',
            'parCriticite',
            'derniersMouvements',
            'dernieresImmobilisations',
            'mouvementsEnAttenteCount',
            'maintenancesEnCoursCount',
            'alertesActives',
            'campagneEnCours',
            'pointsCarte'
        ));
    }
}
