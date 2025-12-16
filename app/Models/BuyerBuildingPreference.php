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

        // 🔹 location hierarchy
        'preferred_countries',
        'preferred_states',
        'preferred_districts',

        // existing fields
        'preferred_districts',   // keep for backward compatibility if you were using it
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
        'created_by_admin_id',
    ];

    protected $casts = [
        'preferred_countries' => 'array',
        'preferred_states'    => 'array',
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

    public function createdByAdmin()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }
}
