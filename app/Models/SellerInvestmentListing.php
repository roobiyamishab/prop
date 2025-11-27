<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerInvestmentListing extends Model
{
    use HasFactory;

    protected $table = 'seller_investment_listings';

    protected $fillable = [
        'user_id',
        'property_code',
         'created_by_admin_id',
         'status',  
        'project_name',
        'project_type',
        'district',
        'micro_location',
        'landmark',
        'map_link',
        'project_cost',
        'investment_required',
        'profit_sharing_model',
        'payback_period',
        'project_status',
        'completion_percent',
        'documents',
    ];

    protected $casts = [
        'project_cost'       => 'decimal:2',
        'investment_required'=> 'decimal:2',
        'completion_percent' => 'integer',
        'documents'          => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function createdByAdmin()
{
    return $this->belongsTo(Admin::class, 'created_by_admin_id'); // the admin who added it
}

    public function matches()
    {
        return $this->hasMany(PropertyMatch::class, 'seller_listing_id')
                    ->where('listing_type', 'investment');
    }
}
