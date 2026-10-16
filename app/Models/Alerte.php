<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    use HasFactory;

    protected $table = 'alertes';

    protected $fillable = [
        'immobilisation_id',
        'type_alerte',
        'niveau_severite',
        'titre',
        'message',
        'lue',
        'date_echeance',
    ];

    protected $casts = [
        'lue' => 'boolean',
        'date_echeance' => 'datetime',
    ];

    public function immobilisation()
    {
        return $this->belongsTo(Immobilisation::class, 'immobilisation_id');
    }

    public function getSeveriteBadgeClassAttribute()
    {
        return match ($this->niveau_severite) {
            'danger' => 'badge-danger',
            'warning' => 'badge-warning',
            'info' => 'badge-info',
            default => 'badge-secondary',
        };
    }
}
