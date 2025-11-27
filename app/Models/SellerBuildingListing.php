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
        'property_code',
        'status',
'created_by_admin_id',
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

        // 👇 add these to match your controller
        'documents',
        'photos',
        // 'videos', // only if you create this column in DB and use it
    ];

     protected $casts = [
        // numbers
        'total_plot_area'     => 'decimal:2',
        'builtup_area'        => 'decimal:2',
        'expected_price'      => 'decimal:2',
        'price_per_sqft'      => 'decimal:2',
        'parking_slots'       => 'integer',
        'road_frontage'       => 'integer',
        'expected_advance_pct'=> 'integer',
        'floors'              => 'integer',

        // booleans
        'lift_available'      => 'boolean',

        // json
        'photos'              => 'array',
        'documents'           => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matches()
    {
        return $this->hasMany(PropertyMatch::class, 'seller_listing_id')
                    ->where('listing_type', 'building');
    }
    // In SellerLandListing, SellerBuildingListing, SellerInvestmentListing


public function createdByAdmin()
{
    return $this->belongsTo(Admin::class, 'created_by_admin_id'); // the admin who added it
}



}
