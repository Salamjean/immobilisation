<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStructure extends Model
{
    use HasFactory;

    protected $table = 'services_structures';

    protected $fillable = [
        'structure_beneficiaire_id',
        'code',
        'nom',
        'responsable',
    ];

    public function structure()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_beneficiaire_id');
    }

    public function detenteurs()
    {
        return $this->hasMany(Detenteur::class, 'service_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'service_id');
    }
}
