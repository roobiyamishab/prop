<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BuyerLandPreference;
use App\Models\BuyerBuildingPreference;
use App\Models\BuyerInvestmentPreference;
use App\Models\SellerLandListing;
use App\Models\SellerBuildingListing;
use App\Models\SellerInvestmentListing;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // Buyer preferences (one per user)
        $buyerLand       = BuyerLandPreference::where('user_id', $user->id)->first();
        $buyerBuilding   = BuyerBuildingPreference::where('user_id', $user->id)->first();
        $buyerInvestment = BuyerInvestmentPreference::where('user_id', $user->id)->first();

        // Seller listings (limit 2 each for the cards)
        $sellerLandListings = SellerLandListing::where('user_id', $user->id)
            ->latest()->take(2)->get();

        $sellerBuildingListings = SellerBuildingListing::where('user_id', $user->id)
            ->latest()->take(2)->get();

        $sellerInvestmentListings = SellerInvestmentListing::where('user_id', $user->id)
            ->latest()->take(2)->get();

        return view('dashboard', compact(
            'user',
            'buyerLand',
            'buyerBuilding',
            'buyerInvestment',
            'sellerLandListings',
            'sellerBuildingListings',
            'sellerInvestmentListings'
        ));
    }
}
