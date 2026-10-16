<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventaireReleve extends Model
{
    use HasFactory;

    protected $table = 'inventaire_releves';

    protected $fillable = [
        'campagne_id',
        'immobilisation_id',
        'agent_user_id',
        'code_scanne',
        'presence_constatee',
        'etat_constate',
        'structure_constatee_id',
        'service_constate_id',
        'detenteur_constate_id',
        'emplacement_constate',
        'latitude',
        'longitude',
        'precision_gps',
        'date_heure_scan',
        'photo_constatee',
        'observations',
        'statut_rapprochement',
        'analyse_ecart',
        'decision_regularisation',
        'valide_par_user_id',
        'date_validation_rapprochement',
    ];

    protected $casts = [
        'presence_constatee' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'precision_gps' => 'float',
        'date_heure_scan' => 'datetime',
        'date_validation_rapprochement' => 'datetime',
    ];

    public function campagne()
    {
        return $this->belongsTo(CampagneInventaire::class, 'campagne_id');
    }

    public function immobilisation()
    {
        return $this->belongsTo(Immobilisation::class, 'immobilisation_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    public function structureConstatee()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_constatee_id');
    }

    public function serviceConstate()
    {
        return $this->belongsTo(ServiceStructure::class, 'service_constate_id');
    }

    public function detenteurConstate()
    {
        return $this->belongsTo(Detenteur::class, 'detenteur_constate_id');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'valide_par_user_id');
    }

    public function getStatutRapprochementLibelleAttribute()
    {
        return match ($this->statut_rapprochement) {
            'conforme' => 'Conforme',
            'ecart_localisation' => 'Écart de localisation',
            'ecart_detenteur' => 'Écart de détenteur',
            'ecart_etat' => 'Écart d\'état matériel',
            'non_retrouve' => 'Bien non retrouvé',
            'nouvel_actif_non_enregistre' => 'Nouvel actif non répertorié',
            default => ucfirst(str_replace('_', ' ', $this->statut_rapprochement)),
        };
    }

    public function getStatutRapprochementBadgeClassAttribute()
    {
        return match ($this->statut_rapprochement) {
            'conforme' => 'badge-success',
            'ecart_localisation' => 'badge-warning',
            'ecart_detenteur' => 'badge-info',
            'ecart_etat' => 'badge-warning',
            'non_retrouve' => 'badge-danger',
            'nouvel_actif_non_enregistre' => 'badge-purple',
            default => 'badge-secondary',
        };
    }
}
