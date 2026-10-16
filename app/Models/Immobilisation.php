<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Immobilisation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'immobilisations';

    protected $fillable = [
        'code_immobilisation',
        'code_bailleur',
        'reference_etiquette',
        'numero_serie',
        'designation',
        'categorie_id',
        'sous_categorie_id',
        'marque',
        'modele',
        'description_technique',
        'bailleur_id',
        'programme_id',
        'subvention_id',
        'fournisseur',
        'reference_marche',
        'bon_commande',
        'date_acquisition',
        'date_reception',
        'date_mise_en_service',
        'valeur_acquisition',
        'devise',
        'date_fin_garantie',
        'fournisseur_garantie',
        'structure_beneficiaire_id',
        'service_id',
        'detenteur_id',
        'date_affectation',
        'emplacement_detaille',
        'latitude',
        'longitude',
        'precision_gps',
        'date_derniere_localisation',
        'rayon_geofence_metres',
        'etat',
        'statut_operationnel',
        'niveau_criticite',
        'score_risque',
        'justification_risque',
        'photo_principale',
        'qr_token',
        'date_dernier_inventaire',
        'agent_dernier_inventaire',
        'observations',
    ];

    protected $casts = [
        'date_acquisition' => 'date',
        'date_reception' => 'date',
        'date_mise_en_service' => 'date',
        'date_fin_garantie' => 'date',
        'date_affectation' => 'date',
        'date_derniere_localisation' => 'datetime',
        'date_dernier_inventaire' => 'datetime',
        'valeur_acquisition' => 'decimal:2',
        'latitude' => 'float',
        'longitude' => 'float',
        'precision_gps' => 'float',
        'score_risque' => 'integer',
        'rayon_geofence_metres' => 'integer',
    ];

    // Relations
    public function categorie()
    {
        return $this->belongsTo(CategorieActif::class, 'categorie_id');
    }

    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorieActif::class, 'sous_categorie_id');
    }

    public function bailleur()
    {
        return $this->belongsTo(Bailleur::class, 'bailleur_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function subvention()
    {
        return $this->belongsTo(Subvention::class, 'subvention_id');
    }

    public function structureBeneficiaire()
    {
        return $this->belongsTo(StructureBeneficiaire::class, 'structure_beneficiaire_id');
    }

    public function service()
    {
        return $this->belongsTo(ServiceStructure::class, 'service_id');
    }

    public function detenteur()
    {
        return $this->belongsTo(Detenteur::class, 'detenteur_id');
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class, 'immobilisation_id')->latest('date_mouvement');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'immobilisation_id')->latest('date_signalement');
    }

    public function documents()
    {
        return $this->hasMany(AssetDocument::class, 'immobilisation_id');
    }

    public function inventaireReleves()
    {
        return $this->hasMany(InventaireReleve::class, 'immobilisation_id');
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class, 'immobilisation_id');
    }

    // Badges & Labels Helpers
    public function getEtatLibelleAttribute()
    {
        return match ($this->etat) {
            'bon_etat' => 'Bon état / Fonctionnel',
            'panne' => 'En panne',
            'hors_service' => 'Hors service',
            'en_maintenance' => 'En maintenance',
            'transfere' => 'Transféré',
            'non_retrouve' => 'Non retrouvé',
            'vole' => 'Volé / Disparu',
            'a_reformer' => 'À réformer',
            'mis_au_rebut' => 'Mis au rebut',
            default => ucfirst(str_replace('_', ' ', $this->etat)),
        };
    }

    public function getEtatBadgeClassAttribute()
    {
        return match ($this->etat) {
            'bon_etat' => 'badge-success',
            'panne' => 'badge-warning',
            'hors_service' => 'badge-danger',
            'en_maintenance' => 'badge-info',
            'transfere' => 'badge-purple',
            'non_retrouve' => 'badge-dark',
            'vole' => 'badge-danger',
            'a_reformer' => 'badge-secondary',
            'mis_au_rebut' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getNiveauCriticiteBadgeClassAttribute()
    {
        return match ($this->niveau_criticite) {
            'critique' => 'badge-criticite-critique',
            'eleve' => 'badge-criticite-eleve',
            'modere' => 'badge-criticite-modere',
            'faible' => 'badge-criticite-faible',
            default => 'badge-secondary',
        };
    }

    public function getValeurFormateeAttribute()
    {
        return number_format($this->valeur_acquisition, 0, ',', ' ') . ' ' . $this->devise;
    }

    // Scopes
    public function scopeCritiques($query)
    {
        return $query->whereIn('niveau_criticite', ['critique', 'eleve']);
    }

    public function scopeEnService($query)
    {
        return $query->where('etat', 'bon_etat');
    }

    public function scopeEnPanne($query)
    {
        return $query->whereIn('etat', ['panne', 'hors_service', 'en_maintenance']);
    }

    public function scopeRecherche($query, $terme)
    {
        if (empty($terme)) {
            return $query;
        }

        return $query->where(function ($q) use ($terme) {
            $q->where('code_immobilisation', 'like', "%{$terme}%")
                ->orWhere('designation', 'like', "%{$terme}%")
                ->orWhere('numero_serie', 'like', "%{$terme}%")
                ->orWhere('reference_etiquette', 'like', "%{$terme}%")
                ->orWhere('code_bailleur', 'like', "%{$terme}%")
                ->orWhere('marque', 'like', "%{$terme}%")
                ->orWhere('modele', 'like', "%{$terme}%");
        });
    }
}
