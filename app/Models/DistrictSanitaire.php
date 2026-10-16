<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictSanitaire extends Model
{
    use HasFactory;

    protected $table = 'districts_sanitaires';

    protected $fillable = [
        'region_id',
        'nom',
        'code',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function structures()
    {
        return $this->hasMany(StructureBeneficiaire::class, 'district_sanitaire_id');
    }
}
