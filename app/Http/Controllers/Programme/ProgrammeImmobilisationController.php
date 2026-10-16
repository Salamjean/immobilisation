<?php

namespace App\Http\Controllers\Programme;

use App\Http\Controllers\Controller;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\Programme;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgrammeImmobilisationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $programmeId = $user?->programme_id ?? Programme::where('code', 'PNLP')->first()?->id ?? Programme::first()?->id;

        $query = Immobilisation::with(['categorie', 'structureBeneficiaire', 'detenteur'])
            ->where('programme_id', $programmeId);

        if ($request->filled('search')) {
            $query->recherche($request->search);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('structure_id')) {
            $query->where('structure_beneficiaire_id', $request->structure_id);
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        $immobilisations = $query->latest()->paginate(15)->withQueryString();
        $programme = Programme::find($programmeId);
        $categories = CategorieActif::orderBy('nom')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        return view('programme.immobilisations.index', compact('immobilisations', 'programme', 'categories', 'structures'));
    }

    public function show($id)
    {
        $actif = Immobilisation::with([
            'categorie',
            'sousCategorie',
            'bailleur',
            'programme',
            'subvention',
            'structureBeneficiaire.region',
            'service',
            'detenteur',
            'mouvements',
            'maintenances',
            'alertes'
        ])->findOrFail($id);

        return view('programme.immobilisations.show', compact('actif'));
    }
}
