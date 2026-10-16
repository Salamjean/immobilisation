<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detenteur extends Model
{
    use HasFactory;

    protected $table = 'detenteurs';

    protected $fillable = [
        'structure_beneficiaire_id',
        'service_id',
        'matricule',
        'nom',
        'prenom',
        'fonction',
        'telephone',
        'email',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function structure()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_beneficiaire_id');
    }

    public function service()
    {
        return $this->belongsTo(ServiceStructure::class, 'service_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'detenteur_id');
    }
}
