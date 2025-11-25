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
        'preferred_districts',
        'preferred_locations',
        'status', 
        'investment_property_type',
        'investment_budget_min',
        'investment_budget_max',
        'profit_expectation_year',
    ];

    protected $casts = [
        'preferred_districts'    => 'array',
        'preferred_locations'    => 'array',
        'investment_budget_min'  => 'decimal:2',
        'investment_budget_max'  => 'decimal:2',
        'profit_expectation_year'=> 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
