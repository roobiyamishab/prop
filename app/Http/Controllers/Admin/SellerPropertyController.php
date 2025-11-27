<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SellerLandListing;
use App\Models\SellerBuildingListing;
use App\Models\SellerInvestmentListing;
use Illuminate\Http\Request;

class SellerPropertyController extends Controller
{
    /**
     * Show all properties (land / building / investment) for a specific seller.
     *
     * Route: GET /admin/sellers/{seller}/properties?tab=land|building|investment
     * Name : admin.seller.properties.index
     */
    public function index(Request $request, User $seller)
    {
        $tab = $request->query('tab', 'land'); // default tab = land

        $landListings = SellerLandListing::with(['createdByAdmin', 'user'])
    ->where('user_id', $seller->id)
    ->orderByDesc('created_at')
    ->get();

$buildingListings = SellerBuildingListing::with(['createdByAdmin', 'user'])
    ->where('user_id', $seller->id)
    ->orderByDesc('created_at')
    ->get();

$investmentListings = SellerInvestmentListing::with(['createdByAdmin', 'user'])
    ->where('user_id', $seller->id)
    ->orderByDesc('created_at')
    ->get();


        return view('admin.seller.properties.index', [
            'seller'             => $seller,
            'tab'                => $tab,
            'landListings'       => $landListings,
            'buildingListings'   => $buildingListings,
            'investmentListings' => $investmentListings,
        ]);
    }
}
