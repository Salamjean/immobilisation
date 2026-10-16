<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventaireEquipe extends Model
{
    use HasFactory;

    protected $table = 'inventaire_equipes';

    protected $fillable = [
        'campagne_id',
        'user_id',
        'structure_beneficiaire_id',
        'role_equipe',
    ];

    public function campagne()
    {
        return $this->belongsTo(CampagneInventaire::class, 'campagne_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function structure()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_beneficiaire_id');
    }
}
