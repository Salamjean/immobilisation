<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieActif extends Model
{
    use HasFactory;

    protected $table = 'categories_actifs';

    protected $fillable = [
        'code',
        'nom',
        'description',
        'duree_vie_standard_mois',
        'icone',
    ];

    public function sousCategories()
    {
        return $this->hasMany(SousCategorieActif::class, 'categorie_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'categorie_id');
    }
}
