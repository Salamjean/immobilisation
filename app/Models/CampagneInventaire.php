<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampagneInventaire extends Model
{
    use HasFactory;

    protected $table = 'campagnes_inventaire';

    protected $fillable = [
        'code_campagne',
        'intitule',
        'type_campagne',
        'date_debut',
        'date_fin',
        'statut',
        'programme_id',
        'region_id',
        'superviseur_id',
        'objectifs',
        'date_cloture',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_cloture' => 'datetime',
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function superviseur()
    {
        return $this->belongsTo(User::class, 'superviseur_id');
    }

    public function equipes()
    {
        return $this->hasMany(InventaireEquipe::class, 'campagne_id');
    }

    public function agents()
    {
        return $this->belongsToMany(User::class, 'inventaire_equipes', 'campagne_id', 'user_id');
    }

    public function releves()
    {
        return $this->hasMany(InventaireReleve::class, 'campagne_id');
    }

    public function getTypeLibelleAttribute()
    {
        return match ($this->type_campagne) {
            'annuel' => 'Inventaire Général Annuel',
            'tournant' => 'Inventaire Tournant',
            'cible_programme' => 'Inventaire Ciblé par Programme',
            'cible_region' => 'Inventaire Régional',
            'actifs_critiques' => 'Contrôle Spécifique Actifs Critiques',
            default => ucfirst(str_replace('_', ' ', $this->type_campagne)),
        };
    }

    public function getStatutBadgeClassAttribute()
    {
        return match ($this->statut) {
            'planifiee' => 'badge-info',
            'en_cours' => 'badge-warning',
            'en_rapprochement' => 'badge-purple',
            'cloturee' => 'badge-success',
            default => 'badge-secondary',
        };
    }
}
