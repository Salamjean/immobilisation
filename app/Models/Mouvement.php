<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    use HasFactory;

    protected $table = 'mouvements';

    protected $fillable = [
        'reference_mouvement',
        'immobilisation_id',
        'type_mouvement',
        'statut_workflow',
        'structure_origine_id',
        'service_origine_id',
        'detenteur_origine_id',
        'structure_destination_id',
        'service_destination_id',
        'detenteur_destination_id',
        'date_mouvement',
        'motif',
        'demande_par_user_id',
        'valide_par_user_id',
        'date_validation',
        'date_reception_effective',
        'receptionne_par_user_id',
        'document_justificatif',
        'commentaires',
    ];

    protected $casts = [
        'date_mouvement' => 'date',
        'date_validation' => 'datetime',
        'date_reception_effective' => 'datetime',
    ];

    public function immobilisation()
    {
        return $this->belongsTo(Immobilisation::class, 'immobilisation_id');
    }

    public function structureOrigine()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_origine_id');
    }

    public function serviceOrigine()
    {
        return $this->belongsTo(ServiceStructure::class, 'service_origine_id');
    }

    public function detenteurOrigine()
    {
        return $this->belongsTo(Detenteur::class, 'detenteur_origine_id');
    }

    public function structureDestination()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_destination_id');
    }

    public function serviceDestination()
    {
        return $this->belongsTo(ServiceStructure::class, 'service_destination_id');
    }

    public function detenteurDestination()
    {
        return $this->belongsTo(Detenteur::class, 'detenteur_destination_id');
    }

    public function demandeur()
    {
        return $this->belongsTo(User::class, 'demande_par_user_id');
    }

    public function valideur()
    {
        return $this->belongsTo(User::class, 'valide_par_user_id');
    }

    public function recepteur()
    {
        return $this->belongsTo(User::class, 'receptionne_par_user_id');
    }

    public function getTypeLibelleAttribute()
    {
        return match ($this->type_mouvement) {
            'affectation' => 'Affectation initiale',
            'reaffectation' => 'Réaffectation',
            'transfert' => 'Transfert inter-sites',
            'pret' => 'Prêt temporaire',
            'retour' => 'Retour de prêt',
            'sortie_temporaire' => 'Sortie temporaire',
            'reforme' => 'Proposition de réforme',
            'mise_au_rebut' => 'Mise au rebut',
            'cession' => 'Cession',
            default => ucfirst(str_replace('_', ' ', $this->type_mouvement)),
        };
    }

    public function getStatutBadgeClassAttribute()
    {
        return match ($this->statut_workflow) {
            'brouillon' => 'badge-secondary',
            'soumis' => 'badge-warning',
            'valide' => 'badge-info',
            'en_transit' => 'badge-purple',
            'receptionne' => 'badge-success',
            'rejete' => 'badge-danger',
            'annule' => 'badge-dark',
            default => 'badge-secondary',
        };
    }
}
