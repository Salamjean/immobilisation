<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCategorieActif extends Model
{
    use HasFactory;

    protected $table = 'sous_categories_actifs';

    protected $fillable = [
        'categorie_id',
        'code',
        'nom',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieActif::class, 'categorie_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'sous_categorie_id');
    }
}
