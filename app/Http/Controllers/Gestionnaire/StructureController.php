<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DistrictSanitaire;
use App\Models\Region;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StructureController extends Controller
{
    public const TYPES_STRUCTURE = [
        'CHU' => 'Centre Hospitalier Universitaire (CHU)',
        'CHR' => 'Centre Hospitalier Régional (CHR)',
        'HG' => 'Hôpital Général (HG)',
        'CSU' => 'Centre de Santé Urbain (CSU)',
        'CSR' => 'Centre de Santé Rural (CSR)',
        'ESPC' => 'Établissement Sanitaire de Premier Contact (ESPC)',
        'DISTRICT' => 'Direction de District Sanitaire',
        'DIRECTION' => 'Direction Régionale / Ministère',
        'UCP' => 'Unité de Coordination des Projets (UCP)',
        'AUTRE' => 'Autre structure sanitaire',
    ];

    public function index(Request $request)
    {
        $query = StructureBeneficiaire::with(['region', 'districtSanitaire'])->withCount('immobilisations');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%")
                  ->orWhere('responsable_nom', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type_structure')) {
            $query->where('type_structure', $request->type_structure);
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif == '1');
        }

        $structures = $query->latest()->paginate(10)->withQueryString();
        $regions = Region::orderBy('nom')->get();
        $districts = DistrictSanitaire::orderBy('nom')->get();
        $typesStructure = self::TYPES_STRUCTURE;

        $stats = [
            'total' => StructureBeneficiaire::count(),
            'actifs' => StructureBeneficiaire::where('actif', true)->count(),
            'inactifs' => StructureBeneficiaire::where('actif', false)->count(),
        ];

        return view('gestionnaire.structures.index', compact('structures', 'regions', 'districts', 'typesStructure', 'stats'));
    }

    public function create()
    {
        $regions = Region::orderBy('nom')->get();
        $districts = DistrictSanitaire::orderBy('nom')->get();
        $typesStructure = self::TYPES_STRUCTURE;

        return view('gestionnaire.structures.create', compact('regions', 'districts', 'typesStructure'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('code') && $request->filled('nom')) {
            $request->merge(['code' => $this->generateCodeFromName($request->nom, $request->type_structure)]);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:structures_beneficiaires,code',
            'type_structure' => 'required|string|max:50',
            'region_id' => 'nullable|exists:regions,id',
            'district_sanitaire_id' => 'nullable|exists:districts_sanitaires,id',
            'ville' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'responsable_nom' => 'nullable|string|max:255',
            'responsable_contact' => 'nullable|string|max:50',
            'responsable_email' => 'nullable|email|max:150',
            'actif' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['actif'] = $request->has('actif') ? (bool) $request->actif : true;

        $structure = StructureBeneficiaire::create($validated);

        AuditLog::logAction('CREATION_STRUCTURE', 'STRUCTURE', auth()->user()?->email ?? 'gestionnaire', null, $structure->toArray(), $structure);

        return redirect()->route('gestionnaire.structures.index')->with('success', "La structure [{$structure->nom}] a été créée avec succès.");
    }

    public function edit($id)
    {
        $structure = StructureBeneficiaire::findOrFail($id);
        $regions = Region::orderBy('nom')->get();
        $districts = DistrictSanitaire::orderBy('nom')->get();
        $typesStructure = self::TYPES_STRUCTURE;

        return view('gestionnaire.structures.edit', compact('structure', 'regions', 'districts', 'typesStructure'));
    }

    public function update(Request $request, $id)
    {
        $structure = StructureBeneficiaire::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('structures_beneficiaires', 'code')->ignore($structure->id)],
            'type_structure' => 'required|string|max:50',
            'region_id' => 'nullable|exists:regions,id',
            'district_sanitaire_id' => 'nullable|exists:districts_sanitaires,id',
            'ville' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'responsable_nom' => 'nullable|string|max:255',
            'responsable_contact' => 'nullable|string|max:50',
            'responsable_email' => 'nullable|email|max:150',
            'actif' => 'nullable|boolean',
        ]);

        $validated['actif'] = $request->has('actif') ? (bool) $request->actif : false;

        $old = $structure->toArray();
        $structure->update($validated);

        AuditLog::logAction('MODIFICATION_STRUCTURE', 'STRUCTURE', auth()->user()?->email ?? 'gestionnaire', $old, $structure->toArray(), $structure);

        return redirect()->route('gestionnaire.structures.index')->with('success', "La structure [{$structure->nom}] a été mise à jour.");
    }

    public function toggleStatus($id)
    {
        $structure = StructureBeneficiaire::findOrFail($id);
        $structure->actif = !$structure->actif;
        $structure->save();

        $action = $structure->actif ? 'ACTIVATION_STRUCTURE' : 'DESACTIVATION_STRUCTURE';
        AuditLog::logAction($action, 'STRUCTURE', auth()->user()?->email ?? 'gestionnaire', null, ['id' => $structure->id, 'actif' => $structure->actif], $structure);

        $statusMsg = $structure->actif ? 'activée' : 'désactivée';
        return back()->with('success', "La structure [{$structure->nom}] a été {$statusMsg}.");
    }

    private function generateCodeFromName(string $nom, ?string $type = null): string
    {
        $prefix = $type ? strtoupper($type) : 'STR';
        $stopWords = ['le', 'la', 'les', 'l', 'de', 'du', 'des', 'd', 'un', 'une', 'et', 'ou', 'pour', 'en', 'dans', 'par', 'sur', 'au', 'aux', 'centre', 'hopital', 'general', 'regional', 'urbain', 'rural'];
        
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nom) ?: $nom;
        preg_match_all('/[a-zA-Z0-9]+/u', $ascii, $matches);
        $words = $matches[0] ?? [];

        if (empty($words)) {
            return $prefix . '-' . date('Ymd');
        }

        $filtered = array_filter($words, fn($w) => !in_array(strtolower($w), $stopWords));
        $target = !empty($filtered) ? array_values($filtered) : $words;

        if (count($target) === 1) {
            $suffix = strtoupper(substr($target[0], 0, 8));
        } else {
            $suffix = '';
            foreach ($target as $w) {
                $suffix .= strtoupper(substr($w[0], 0, 3));
            }
            if (strlen($suffix) > 8) {
                $suffix = substr($suffix, 0, 8);
            }
        }

        return $prefix . '-' . $suffix;
    }
}
