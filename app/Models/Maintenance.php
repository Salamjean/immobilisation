<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenances';

    protected $fillable = [
        'numero_fiche',
        'immobilisation_id',
        'type_maintenance',
        'statut',
        'date_signalement',
        'date_intervention',
        'date_fin_intervention',
        'prestataire',
        'technicien_intervenant',
        'description_panne',
        'diagnostic',
        'travaux_effectues',
        'pieces_remplacees',
        'cout_intervention',
        'duree_immobilisation_jours',
        'date_prochaine_maintenance',
        'rapport_document',
    ];

    protected $casts = [
        'date_signalement' => 'date',
        'date_intervention' => 'date',
        'date_fin_intervention' => 'date',
        'date_prochaine_maintenance' => 'date',
        'cout_intervention' => 'decimal:2',
        'duree_immobilisation_jours' => 'integer',
    ];

    public function immobilisation()
    {
        return $this->belongsTo(Immobilisation::class, 'immobilisation_id');
    }

    public function getTypeLibelleAttribute()
    {
        return match ($this->type_maintenance) {
            'preventive' => 'Maintenance Préventive',
            'corrective' => 'Maintenance Corrective (Curative)',
            'calibration' => 'Étalonnage / Calibration',
            'controle_technique' => 'Contrôle Technique Régulier',
            default => ucfirst(str_replace('_', ' ', $this->type_maintenance)),
        };
    }

    public function getStatutBadgeClassAttribute()
    {
        return match ($this->statut) {
            'signalee' => 'badge-danger',
            'en_attente' => 'badge-warning',
            'en_cours' => 'badge-info',
            'terminee' => 'badge-success',
            'annulee' => 'badge-dark',
            default => 'badge-secondary',
        };
    }
}
