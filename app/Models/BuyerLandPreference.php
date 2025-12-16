<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerLandPreference extends Model
{
    use HasFactory;

    protected $table = 'buyer_land_preferences';

    protected $fillable = [
        'user_id',
        'created_by_admin_id',

        // 🔹 Location fields
        'preferred_countries',
        'preferred_states',
        'preferred_districts',
        'preferred_locations',

        // 🔹 Land size + budget
        'land_size_unit',
        'land_size_needed_min',
        'land_size_needed_max',
        'budget_per_cent_min',
        'budget_per_cent_max',

        // 🔹 Other preferences
        'status',
        'zoning_preference',
        'timeline_to_purchase',
        'mode_of_purchase',
        'advance_capacity',
        'documentation_speed',
        'property_condition',
        'amenities_preference',
        'infra_preference',
    ];

    protected $casts = [
        // Arrays / JSON
        'preferred_countries'  => 'array',
        'preferred_states'     => 'array',
        'preferred_districts'  => 'array',
        'preferred_locations'  => 'array',
        'amenities_preference' => 'array',
        'infra_preference'     => 'array',

        // Decimals
        'land_size_needed_min' => 'decimal:2',
        'land_size_needed_max' => 'decimal:2',
        'budget_per_cent_min'  => 'decimal:2',
        'budget_per_cent_max'  => 'decimal:2',
    ];

    // 🔹 Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }
}
