<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subvention extends Model
{
    use HasFactory;

    protected $table = 'subventions';

    protected $fillable = [
        'programme_id',
        'bailleur_id',
        'code_subvention',
        'intitule',
        'date_debut',
        'date_fin',
        'montant_alloue',
        'devise',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant_alloue' => 'decimal:2',
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function bailleur()
    {
        return $this->belongsTo(Bailleur::class, 'bailleur_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'subvention_id');
    }
}
