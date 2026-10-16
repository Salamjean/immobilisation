<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Bailleur;
use App\Models\CategorieActif;
use App\Models\Detenteur;
use App\Models\Immobilisation;
use App\Models\Programme;
use App\Models\Region;
use App\Models\ServiceStructure;
use App\Models\StructureBeneficiaire;
use App\Models\Subvention;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImmobilisationController extends Controller
{
    public function index(Request $request)
    {
        $query = Immobilisation::with(['categorie', 'sousCategorie', 'programme', 'bailleur', 'structureBeneficiaire', 'detenteur']);

        if ($request->filled('search')) {
            $query->recherche($request->search);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->programme_id);
        }
        if ($request->filled('structure_id')) {
            $query->where('structure_beneficiaire_id', $request->structure_id);
        }
        if ($request->filled('region_id')) {
            $query->whereHas('structureBeneficiaire', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        if ($request->filled('niveau_criticite')) {
            $query->where('niveau_criticite', $request->niveau_criticite);
        }

        $immobilisations = $query->latest()->paginate(15)->withQueryString();

        $categories = CategorieActif::orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $regions = Region::orderBy('nom')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        $stats = [
            'total' => Immobilisation::count(),
            'valeur_totale' => Immobilisation::sum('valeur_acquisition'),
            'en_service' => Immobilisation::where('etat', 'bon_etat')->count(),
            'critiques' => Immobilisation::whereIn('niveau_criticite', ['critique', 'eleve'])->count(),
        ];

        return view('admin.immobilisations.index', compact('immobilisations', 'categories', 'programmes', 'regions', 'structures', 'stats'));
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
            'structureBeneficiaire.districtSanitaire',
            'service',
            'detenteur',
            'mouvements.structureOrigine',
            'mouvements.structureDestination',
            'mouvements.demandeur',
            'mouvements.valideur',
            'maintenances',
            'documents.uploader',
            'inventaireReleves.campagne',
            'inventaireReleves.agent',
            'alertes'
        ])->findOrFail($id);

        return view('admin.immobilisations.show', compact('actif'));
    }

    public function create()
    {
        $categories = CategorieActif::with('sousCategories')->orderBy('nom')->get();
        $bailleurs = Bailleur::where('actif', true)->orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $subventions = Subvention::orderBy('code_subvention')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();
        $services = ServiceStructure::orderBy('nom')->get();
        $detenteurs = Detenteur::where('actif', true)->orderBy('nom')->get();

        $dernierId = Immobilisation::max('id') ?? 0;
        $codeSuggere = 'UCPFM-' . date('Y') . '-ACT-' . str_pad($dernierId + 1, 4, '0', STR_PAD_LEFT);
        $tagSuggere = 'TAG-QR-' . (88000 + $dernierId + 1);

        return view('admin.immobilisations.create', compact('categories', 'bailleurs', 'programmes', 'subventions', 'structures', 'services', 'detenteurs', 'codeSuggere', 'tagSuggere'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 1. Identification
            'code_immobilisation' => 'required|string|max:100|unique:immobilisations,code_immobilisation',
            'code_bailleur' => 'nullable|string|max:100',
            'reference_etiquette' => 'nullable|string|max:100',
            'designation' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories_actifs,id',
            'sous_categorie_id' => 'nullable|exists:sous_categories_actifs,id',
            'marque' => 'nullable|string|max:100',
            'modele' => 'nullable|string|max:100',
            'numero_serie' => 'nullable|string|max:150',

            // 2. Acquisition
            'fournisseur' => 'nullable|string|max:255',
            'reference_marche' => 'nullable|string|max:255',
            'bon_commande' => 'nullable|string|max:100',
            'date_acquisition' => 'nullable|date',
            'date_reception' => 'nullable|date',
            'date_mise_en_service' => 'nullable|date',
            'valeur_acquisition' => 'required|numeric|min:0',
            'devise' => 'required|string|max:10',
            'bailleur_id' => 'nullable|exists:bailleurs,id',
            'subvention_id' => 'nullable|exists:subventions,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'date_fin_garantie' => 'nullable|date',
            'fournisseur_garantie' => 'nullable|string|max:255',

            // 3. Affectation
            'structure_beneficiaire_id' => 'required|exists:structures_beneficiaires,id',
            'service_id' => 'nullable|exists:services_structures,id',
            'detenteur_id' => 'nullable|exists:detenteurs,id',
            'date_affectation' => 'nullable|date',
            'emplacement_detaille' => 'nullable|string|max:255',

            // 4. Géographie
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'precision_gps' => 'nullable|numeric',

            // 5. État
            'etat' => 'required|string|in:bon_etat,panne,hors_service,non_retrouve,transfere,vole,en_maintenance,a_reformer,mis_au_rebut',
            'statut_operationnel' => 'nullable|string',

            // 6. Risque
            'niveau_criticite' => 'required|string|in:faible,modere,eleve,critique',
            'score_risque' => 'nullable|integer|min:0|max:100',
            'justification_risque' => 'nullable|string',

            // 7. Documents & Médias
            'photo_principale' => 'nullable|image|max:5120',
            'observations' => 'nullable|string',
        ]);

        if ($request->hasFile('photo_principale')) {
            $file = $request->file('photo_principale');
            $filename = 'actif_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('immobilisations/photos', $filename, 'public');
            $validated['photo_principale'] = '/storage/' . $path;
        }

        if (empty($validated['latitude']) || empty($validated['longitude'])) {
            $structure = StructureBeneficiaire::find($validated['structure_beneficiaire_id']);
            if ($structure && $structure->latitude && $structure->longitude) {
                $validated['latitude'] = $structure->latitude;
                $validated['longitude'] = $structure->longitude;
                $validated['date_derniere_localisation'] = now();
            }
        } else {
            $validated['date_derniere_localisation'] = now();
        }

        $validated['qr_token'] = Str::random(32);
        $validated['statut_operationnel'] = $validated['statut_operationnel'] ?? 'en_service';

        $actif = Immobilisation::create($validated);

        AuditLog::logAction('CREATION_ACTIF', 'IMMOBILISATIONS', $actif->code_immobilisation, null, $actif->toArray(), $actif);

        return redirect()->route('admin.immobilisations.show', $actif->id)
            ->with('success', "L'immobilisation [{$actif->code_immobilisation}] a été enregistrée avec succès.");
    }

    public function edit($id)
    {
        $actif = Immobilisation::findOrFail($id);
        $categories = CategorieActif::with('sousCategories')->orderBy('nom')->get();
        $bailleurs = Bailleur::where('actif', true)->orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $subventions = Subvention::orderBy('code_subvention')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();
        $detenteurs = Detenteur::orderBy('nom')->get();
        $services = ServiceStructure::orderBy('nom')->get();

        return view('admin.immobilisations.edit', compact('actif', 'categories', 'bailleurs', 'programmes', 'subventions', 'structures', 'detenteurs', 'services'));
    }

    public function update(Request $request, $id)
    {
        $actif = Immobilisation::findOrFail($id);
        $oldData = $actif->toArray();

        $validated = $request->validate([
            // 1. Identification
            'code_bailleur' => 'nullable|string|max:100',
            'designation' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories_actifs,id',
            'sous_categorie_id' => 'nullable|exists:sous_categories_actifs,id',
            'marque' => 'nullable|string|max:100',
            'modele' => 'nullable|string|max:100',
            'numero_serie' => 'nullable|string|max:150',

            // 2. Acquisition
            'fournisseur' => 'nullable|string|max:255',
            'reference_marche' => 'nullable|string|max:255',
            'bon_commande' => 'nullable|string|max:100',
            'date_acquisition' => 'nullable|date',
            'date_reception' => 'nullable|date',
            'date_mise_en_service' => 'nullable|date',
            'valeur_acquisition' => 'required|numeric|min:0',
            'devise' => 'required|string|max:10',
            'bailleur_id' => 'nullable|exists:bailleurs,id',
            'subvention_id' => 'nullable|exists:subventions,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'date_fin_garantie' => 'nullable|date',
            'fournisseur_garantie' => 'nullable|string|max:255',

            // 3. Affectation
            'structure_beneficiaire_id' => 'required|exists:structures_beneficiaires,id',
            'service_id' => 'nullable|exists:services_structures,id',
            'detenteur_id' => 'nullable|exists:detenteurs,id',
            'date_affectation' => 'nullable|date',
            'emplacement_detaille' => 'nullable|string|max:255',

            // 4. Géographie
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'precision_gps' => 'nullable|numeric',

            // 5. État
            'etat' => 'required|string|in:bon_etat,panne,hors_service,non_retrouve,transfere,vole,en_maintenance,a_reformer,mis_au_rebut',
            'statut_operationnel' => 'required|string',

            // 6. Risque
            'niveau_criticite' => 'required|string|in:faible,modere,eleve,critique',
            'score_risque' => 'nullable|integer|min:0|max:100',
            'justification_risque' => 'nullable|string',

            // 7. Documents & Médias
            'photo_principale' => 'nullable|image|max:5120',
            'observations' => 'nullable|string',
        ]);

        if ($request->hasFile('photo_principale')) {
            $file = $request->file('photo_principale');
            $filename = 'actif_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('immobilisations/photos', $filename, 'public');
            $validated['photo_principale'] = '/storage/' . $path;
        }

        $actif->update($validated);

        AuditLog::logAction('MODIFICATION_ACTIF', 'IMMOBILISATIONS', $actif->code_immobilisation, $oldData, $actif->fresh()->toArray(), $actif);

        return redirect()->route('admin.immobilisations.show', $actif->id)
            ->with('success', "L'immobilisation [{$actif->code_immobilisation}] a été mise à jour avec succès.");
    }

    public function etiquette($id)
    {
        $actif = Immobilisation::with(['categorie', 'programme', 'bailleur', 'structureBeneficiaire'])->findOrFail($id);
        return view('admin.immobilisations.etiquette', compact('actif'));
    }

    public function exportCsv(Request $request)
    {
        $actifs = Immobilisation::with(['categorie', 'programme', 'bailleur', 'structureBeneficiaire.region', 'detenteur'])->get();

        $filename = "UCPFM_Registre_Immobilisations_" . date('Ymd_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($actifs) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Code Immobilisation',
                'Code Bailleur',
                'Tag / QR Code',
                'Désignation',
                'Catégorie',
                'Marque',
                'Modèle',
                'Numéro de Série',
                'Bailleur',
                'Programme',
                'Valeur Acquisition',
                'Devise',
                'Date Acquisition',
                'Région',
                'Structure Bénéficiaire',
                'Service',
                'Détenteur',
                'État Actuel',
                'Niveau Criticité',
                'Latitude',
                'Longitude',
                'Date Dernier Inventaire'
            ], ';');

            foreach ($actifs as $a) {
                fputcsv($handle, [
                    $a->code_immobilisation,
                    $a->code_bailleur,
                    $a->reference_etiquette,
                    $a->designation,
                    $a->categorie?->nom,
                    $a->marque,
                    $a->modele,
                    $a->numero_serie,
                    $a->bailleur?->nom,
                    $a->programme?->code,
                    $a->valeur_acquisition,
                    $a->devise,
                    $a->date_acquisition ? (\Carbon\Carbon::parse($a->date_acquisition)->format('d/m/Y')) : '',
                    $a->structureBeneficiaire?->region?->nom,
                    $a->structureBeneficiaire?->nom,
                    $a->service?->nom,
                    $a->detenteur?->nom_complet,
                    $a->etat_libelle,
                    $a->niveau_criticite,
                    $a->latitude,
                    $a->longitude,
                    $a->date_dernier_inventaire ? (\Carbon\Carbon::parse($a->date_dernier_inventaire)->format('d/m/Y H:i')) : '',
                ], ';');
            }

            fclose($handle);
        };

        AuditLog::logAction('EXPORT_CSV_REFERENTIEL', 'IMMOBILISATIONS', 'GLOBAL');

        return response()->stream($callback, 200, $headers);
    }
}
