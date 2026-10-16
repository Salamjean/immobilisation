<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bailleur extends Model
{
    use HasFactory;

    protected $table = 'bailleurs';

    protected $fillable = [
        'code',
        'nom',
        'description',
        'contact_nom',
        'contact_email',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function programmes()
    {
        return $this->hasMany(Programme::class, 'bailleur_id');
    }

    public function subventions()
    {
        return $this->hasMany(Subvention::class, 'bailleur_id');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class, 'bailleur_id');
    }
}
