<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BuyerLandPreference;
use App\Models\BuyerBuildingPreference;
use App\Models\BuyerInvestmentPreference;
use App\Models\SellerLandListing;
use App\Models\SellerBuildingListing;
use App\Models\SellerInvestmentListing;

class AdminDashboardController extends Controller
{


public function index()
{
    $admin = Auth::guard('admin')->user();

    // 🔹 All land buyer preferences created by THIS admin
    $adminLandPreferences = BuyerLandPreference::with('user')
        ->where('created_by_admin_id', $admin->id)
        ->latest()
        ->get();

    // 🔹 All building buyer preferences created by THIS admin
    $adminBuildingPreferences = BuyerBuildingPreference::with('user')
        ->where('created_by_admin_id', $admin->id)
        ->latest()
        ->get();

    // 🔹 All investment buyer preferences created by THIS admin
    $adminInvestmentPreferences = BuyerInvestmentPreference::with('user')
        ->where('created_by_admin_id', $admin->id)
        ->latest()
        ->get();

    // (You can still keep these if you use single “latest” cards anywhere)
    $buyerLand       = BuyerLandPreference::latest()->first();
    $buyerBuilding   = BuyerBuildingPreference::latest()->first();
    $buyerInvestment = BuyerInvestmentPreference::latest()->first();

    $sellerLandListings       = SellerLandListing::latest()->take(5)->get();
    $sellerBuildingListings   = SellerBuildingListing::latest()->take(5)->get();
    $sellerInvestmentListings = SellerInvestmentListing::latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'admin',
        'adminLandPreferences',
        'adminBuildingPreferences',
        'adminInvestmentPreferences',   // ⬅️ new
        'buyerLand',
        'buyerBuilding',
        'buyerInvestment',
        'sellerLandListings',
        'sellerBuildingListings',
        'sellerInvestmentListings'
    ));
}

}
