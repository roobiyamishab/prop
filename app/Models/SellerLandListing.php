<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;   // 👈 add this line


class SellerLandListing extends Model
{
    use HasFactory;

    protected $table = 'seller_land_listings';

    protected $fillable = [
        'user_id',
        'property_code',
         'status',  
        'district',
        'taluk',
        'village',
        'exact_location',
        'landmark',
        'survey_no',
        'google_map_link',
        'land_area',
        'land_unit',
        'proximity',
        'road_frontage',
        'plot_shape',
        'zoning_type',
        'ownership_type',
        'restrictions',
        'expected_price_per_cent',
        'negotiability',
        'expected_advance_pct',
        'min_offer_expected',
        'market_value_info',
        'land_type',
        'current_use',
        'electricity',
        'water',
        'drainage',
        'compound_wall',
        'sale_timeline',
        'photos',
        'videos',
        'documents',
    ];

    protected $casts = [
        'land_area'              => 'decimal:2',
        'expected_price_per_cent'=> 'decimal:2',
        'min_offer_expected'     => 'decimal:2',
        'electricity'            => 'boolean',
        'water'                  => 'boolean',
        'drainage'               => 'boolean',
        'compound_wall'          => 'boolean',
        'photos'                 => 'array',
        'videos'                 => 'array',
        'documents'              => 'array',
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
                // You can replace this with your generatePropertyCode logic if you prefer
                $model->property_code = 'LND-' . strtoupper(Str::random(6));
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matches()
    {
        return $this->hasMany(PropertyMatch::class, 'seller_listing_id')
                    ->where('listing_type', 'land');
    }
}
