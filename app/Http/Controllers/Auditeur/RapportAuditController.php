<?php

namespace App\Http\Controllers\Auditeur;

use App\Http\Controllers\Controller;
use App\Models\Bailleur;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\InventaireReleve;
use App\Models\Programme;
use Illuminate\Http\Request;

class RapportAuditController extends Controller
{
    public function index()
    {
        $totalBiens = Immobilisation::count();
        $valeurGlobale = Immobilisation::sum('valeur_acquisition');

        $statsProgrammes = Programme::withCount('immobilisations')
            ->withSum('immobilisations', 'valeur_acquisition')
            ->get();

        $statsBailleurs = Bailleur::withCount('immobilisations')
            ->withSum('immobilisations', 'valeur_acquisition')
            ->get();

        $actifsCritiques = Immobilisation::with(['categorie', 'structureBeneficiaire', 'programme'])
            ->whereIn('niveau_criticite', ['critique', 'eleve'])
            ->orderByDesc('valeur_acquisition')
            ->get();

        $derniersEcarts = InventaireReleve::with(['campagne', 'immobilisation', 'structureConstatee'])
            ->where('statut_rapprochement', '!=', 'conforme')
            ->latest()
            ->take(15)
            ->get();

        return view('auditeur.reporting.index', compact(
            'totalBiens',
            'valeurGlobale',
            'statsProgrammes',
            'statsBailleurs',
            'actifsCritiques',
            'derniersEcarts'
        ));
    }

    public function registre()
    {
        $actifs = Immobilisation::with(['categorie', 'programme', 'bailleur', 'subvention', 'structureBeneficiaire.region', 'service', 'detenteur'])
            ->orderBy('code_immobilisation')
            ->get();

        $valeurTotale = $actifs->sum('valeur_acquisition');

        return view('auditeur.reporting.registre', compact('actifs', 'valeurTotale'));
    }
}
