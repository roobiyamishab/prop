<?php

namespace App\Models;

use App\Models\BuyerLandPreference;
use App\Models\BuyerBuildingPreference;
use App\Models\BuyerInvestmentPreference;
use App\Models\SellerLandListing;
use App\Models\SellerBuildingListing;
use App\Models\SellerInvestmentListing;
use App\Models\PropertyMatch;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'location',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* =======================
     * Buyer Relationships
     * ======================= */

    public function landPreference()
    {
        return $this->hasOne(BuyerLandPreference::class);
    }

    public function buildingPreference()
    {
        return $this->hasOne(BuyerBuildingPreference::class);
    }

    public function investmentPreference()
    {
        return $this->hasOne(BuyerInvestmentPreference::class);
    }

    /* =======================
     * Seller Relationships
     * ======================= */

    public function landListings()
    {
        return $this->hasMany(SellerLandListing::class);
    }

    public function buildingListings()
    {
        return $this->hasMany(SellerBuildingListing::class);
    }

    public function investmentListings()
    {
        return $this->hasMany(SellerInvestmentListing::class);
    }

    /* =======================
     * Matches
     * ======================= */

    public function propertyMatches()
    {
        return $this->hasMany(PropertyMatch::class);
    }
}
