<?php

namespace App\Http\Controllers\Logistique;

use App\Http\Controllers\Controller;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\Programme;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;

class LogistiqueImmobilisationController extends Controller
{
    public function index(Request $request)
    {
        $query = Immobilisation::with(['categorie', 'structureBeneficiaire', 'programme', 'detenteur']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code_immobilisation', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhere('numero_serie', 'like', "%{$search}%")
                  ->orWhere('reference_etiquette', 'like', "%{$search}%");
            });
        }
        if ($request->filled('structure_id')) {
            $query->where('structure_beneficiaire_id', $request->structure_id);
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $immobilisations = $query->latest()->paginate(15)->withQueryString();
        $categories = CategorieActif::orderBy('nom')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        return view('logistique.immobilisations.index', compact('immobilisations', 'categories', 'structures'));
    }

    public function show($id)
    {
        $actif = Immobilisation::with([
            'categorie',
            'structureBeneficiaire',
            'programme',
            'bailleur',
            'detenteur',
            'mouvements.structureOrigine',
            'mouvements.structureDestination',
            'maintenances'
        ])->findOrFail($id);

        return view('logistique.immobilisations.show', compact('actif'));
    }
}
