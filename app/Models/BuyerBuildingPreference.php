<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerBuildingPreference extends Model
{
    use HasFactory;

    protected $table = 'buyer_building_preferences';

    protected $fillable = [
        'user_id',
        'preferred_districts',
        'building_type',
        'status', 
        'area_min',
        'area_max',
        'exact_area',
        'frontage_min',
        'age_preference',
        'total_budget_min',
        'total_budget_max',
        'micro_locations',
        'distance_requirement',
        'rent_expectation',
    ];

    protected $casts = [
        'preferred_districts' => 'array',
        'micro_locations'     => 'array',
        'total_budget_min'    => 'decimal:2',
        'total_budget_max'    => 'decimal:2',
        'rent_expectation'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
