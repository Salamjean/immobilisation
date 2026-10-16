<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bailleur;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\Programme;
use App\Models\Region;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $totalActifs = Immobilisation::count();
        $valeurTotale = Immobilisation::sum('valeur_acquisition');
        $enService = Immobilisation::where('etat', 'bon_etat')->count();
        $enPanne = Immobilisation::whereIn('etat', ['panne', 'hors_service', 'en_maintenance'])->count();

        $parCategorie = CategorieActif::withCount('immobilisations')->get();
        $parRegion = Region::withCount('structures')->get();

        return view('admin.reporting.index', compact(
            'totalActifs',
            'valeurTotale',
            'enService',
            'enPanne',
            'parCategorie',
            'parRegion'
        ));
    }

    public function registreOfficiel(Request $request)
    {
        $query = Immobilisation::with(['categorie', 'programme', 'bailleur', 'structureBeneficiaire.region', 'detenteur']);

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->programme_id);
        }
        if ($request->filled('bailleur_id')) {
            $query->where('bailleur_id', $request->bailleur_id);
        }

        $actifs = $query->orderBy('code_immobilisation')->paginate(20)->withQueryString();

        $categories = CategorieActif::orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $bailleurs = Bailleur::where('actif', true)->orderBy('nom')->get();

        return view('admin.reporting.registre', compact('actifs', 'categories', 'programmes', 'bailleurs'));
    }
}
