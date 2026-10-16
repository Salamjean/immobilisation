<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $fillable = [
        'nom',
        'chef_lieu',
    ];

    public function districts()
    {
        return $this->hasMany(DistrictSanitaire::class, 'region_id');
    }

    public function structures()
    {
        return $this->hasMany(StructureBeneficiaire::class, 'region_id');
    }
}
