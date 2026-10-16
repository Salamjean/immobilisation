<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $table = 'programmes';

    protected $fillable = [
        'bailleur_id',
        'code',
        'nom',
        'description',
        'coordonnateur',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function bailleur()
    {
        return $this->belongsTo(Bailleur::class, 'bailleur_id');
    }

    public function subventions()
    {
        return $this->hasMany(Subvention::class, 'programme_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'programme_id');
    }
}
