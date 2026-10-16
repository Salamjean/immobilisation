<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDocument extends Model
{
    use HasFactory;

    protected $table = 'asset_documents';

    protected $fillable = [
        'immobilisation_id',
        'type_document',
        'titre',
        'fichier_path',
        'taille_fichier',
        'extension',
        'uploaded_by_user_id',
    ];

    public function immobilisation()
    {
        return $this->belongsTo(Immobilisation::class, 'immobilisation_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function getTypeLibelleAttribute()
    {
        return match ($this->type_document) {
            'facture' => 'Facture d\'acquisition',
            'bon_commande' => 'Bon de commande',
            'contrat' => 'Contrat / Marché',
            'pv_reception' => 'Procès-verbal de réception',
            'bordereau_livraison' => 'Bordereau de livraison',
            'fiche_technique' => 'Fiche technique / Manuel',
            'certificat_garantie' => 'Certificat de garantie',
            'pv_reforme' => 'Procès-verbal de réforme',
            'pv_mise_au_rebut' => 'Procès-verbal de mise au rebut',
            'photo' => 'Photographie de l\'actif',
            default => 'Autre pièce justificative',
        };
    }
}
