<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerBuildingListing extends Model
{
    use HasFactory;

    protected $table = 'seller_building_listings';

    protected $fillable = [
        'user_id',
        'created_by_admin_id',
        'property_code',
        'status',

        // ✅ NEW location IDs
        'country_id',
        'state_id',
        'district_id',

        // old text location (keep for compatibility)
        'district',
        'area',
        'street_name',
        'landmark',
        'map_link',

        'building_type',
        'total_plot_area',
        'builtup_area',
        'floors',
        'construction_year',
        'building_age',
        'structure_condition',
        'lift_available',
        'parking_slots',
        'road_frontage',

        'expected_price',
        'price_per_sqft',
        'negotiability',
        'expected_advance_pct',
        'sell_timeline',

        'documents',
        'photos',
    ];

    protected $casts = [
        // ids
        'country_id'           => 'integer',
        'state_id'             => 'integer',
        'district_id'          => 'integer',

        // numbers
        'total_plot_area'      => 'decimal:2',
        'builtup_area'         => 'decimal:2',
        'expected_price'       => 'decimal:2',
        'price_per_sqft'       => 'decimal:2',
        'parking_slots'        => 'integer',
        'road_frontage'        => 'integer',
        'expected_advance_pct' => 'integer',
        'floors'               => 'integer',
        'construction_year'    => 'integer',

        // booleans
        'lift_available'       => 'boolean',

        // json
        'photos'               => 'array',
        'documents'            => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function matches()
    {
        return $this->hasMany(PropertyMatch::class, 'seller_listing_id')
            ->where('listing_type', 'building');
    }

    /* Optional (only if you actually have these models)
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // If your package uses City as district
    public function districtObj()
    {
        return $this->belongsTo(City::class, 'district_id');
    }
    */
}
