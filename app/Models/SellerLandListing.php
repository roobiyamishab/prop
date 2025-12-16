<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerLandListing extends Model
{
    use HasFactory;

    protected $table = 'seller_land_listings';

    protected $fillable = [
        'user_id',
        'created_by_admin_id',
        'property_code',
        'status',

        // ✅ NEW: Location IDs
        'country_id',
        'state_id',
        'district_id', // city/district id (cities table)

        // Old / compatibility
        'district',
        'taluk',
        'village',
        'exact_location',
        'landmark',
        'survey_no',
        'google_map_link',

        // Land
        'land_area',
        'land_unit',
        'proximity',
        'road_frontage',
        'plot_shape',

        // Zoning & legal
        'zoning_type',
        'ownership_type',
        'restrictions',

        // Pricing
        'expected_price_per_cent',
        'negotiability',
        'expected_advance_pct',
        'min_offer_expected',
        'market_value_info',

        // Condition
        'land_type',
        'current_use',
        'electricity',
        'water',
        'drainage',
        'compound_wall',

        // Sale timeline
        'sale_timeline',

        // Media & docs
        'photos',
        'videos',
        'documents',
    ];

    protected $casts = [
        // ✅ NEW: ids
        'country_id'   => 'integer',
        'state_id'     => 'integer',
        'district_id'  => 'integer',

        'land_area'               => 'decimal:2',
        'expected_price_per_cent' => 'decimal:2',
        'min_offer_expected'      => 'decimal:2',
        'electricity'             => 'boolean',
        'water'                   => 'boolean',
        'drainage'                => 'boolean',
        'compound_wall'           => 'boolean',
        'photos'                  => 'array',
        'videos'                  => 'array',
        'documents'               => 'array',
    ];

    protected static function booted()
    {
        static::creating(function (SellerLandListing $model) {

            // Ensure user_id
            if (empty($model->user_id) && Auth::check()) {
                $model->user_id = Auth::id();
            }

            // Default status
            if (empty($model->status)) {
                $model->status = 'normal';
            }

            // Default property code
            if (empty($model->property_code)) {
                $model->property_code = 'LND-' . strtoupper(Str::random(6));
            }

            /**
             * Optional: keep `district` string in sync with district_id.
             * (Only if you have City model & want auto-fill district name.)
             *
             * if (empty($model->district) && !empty($model->district_id)) {
             *     $city = \App\Models\City::find($model->district_id);
             *     if ($city) $model->district = $city->name;
             * }
             */
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    // ✅ Optional relationships (only if these models exist)
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // district_id -> cities table
    public function districtRef()
    {
        return $this->belongsTo(City::class, 'district_id');
    }

    public function matches()
    {
        return $this->hasMany(PropertyMatch::class, 'seller_listing_id')
                    ->where('listing_type', 'land');
    }
}
