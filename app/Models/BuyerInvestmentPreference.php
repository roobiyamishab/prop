<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerInvestmentPreference extends Model
{
    use HasFactory;

    protected $table = 'buyer_investment_preferences';

    protected $fillable = [
        'user_id',

        // Location hierarchy
        'preferred_countries',   // JSON: array of country names or IDs
        'preferred_states',      // JSON: array of state names or IDs
        'preferred_districts',   // JSON: array of district/city names or IDs
        'preferred_locations',   // JSON: array of micro-locations / areas

        // Core fields
        'status', 
        'investment_property_type',
        'investment_budget_min',
        'investment_budget_max',
        'profit_expectation_year',

        // Admin created
        'created_by_admin_id',
    ];

    protected $casts = [
        // Arrays
        'preferred_countries'   => 'array',
        'preferred_states'      => 'array',
        'preferred_districts'   => 'array',
        'preferred_locations'   => 'array',

        // Decimals
        'investment_budget_min'   => 'decimal:2',
        'investment_budget_max'   => 'decimal:2',
        'profit_expectation_year' => 'decimal:2',
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
