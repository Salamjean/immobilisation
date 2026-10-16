<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StructureBeneficiaire extends Model
{
    use HasFactory;

    protected $table = 'structures_beneficiaires';

    protected $fillable = [
        'region_id',
        'district_sanitaire_id',
        'code',
        'nom',
        'type_structure',
        'ville',
        'commune',
        'adresse',
        'latitude',
        'longitude',
        'responsable_nom',
        'responsable_contact',
        'responsable_email',
        'actif',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'actif' => 'boolean',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function districtSanitaire()
    {
        return $this->belongsTo(DistrictSanitaire::class, 'district_sanitaire_id');
    }

    public function services()
    {
        return $this->hasMany(ServiceStructure::class, 'structure_beneficiaire_id');
    }

    public function detenteurs()
    {
        return $this->hasMany(Detenteur::class, 'structure_beneficiaire_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'structure_beneficiaire_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'structure_beneficiaire_id');
    }
}
