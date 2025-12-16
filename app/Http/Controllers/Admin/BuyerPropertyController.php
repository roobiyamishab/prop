<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BuyerLandPreference;
use App\Models\BuyerBuildingPreference;
use App\Models\BuyerInvestmentPreference;
use Illuminate\Http\Request;

class BuyerPropertyController extends Controller
{
    // 1️⃣ Per-buyer view (keep your existing one)
    public function index(User $buyer, Request $request)
    {
        $tab = $request->get('tab', 'land');

        $landPreferences        = $buyer->buyerLandPreferences()->latest()->get();
        $buildingPreferences    = $buyer->buyerBuildingPreferences()->latest()->get();
        $investmentPreferences  = $buyer->buyerInvestmentPreferences()->latest()->get();

        return view('admin.buyers.properties-index', compact(
            'buyer',
            'tab',
            'landPreferences',
            'buildingPreferences',
            'investmentPreferences'
        ));
    }

    // 2️⃣ NEW: all buyers + all property preferences
    public function all(Request $request)
    {
        $tab = $request->get('tab', 'land');

        // make sure each preference model has ->user() relation
        $landPreferences = BuyerLandPreference::with('user')->latest()->get();
        $buildingPreferences = BuyerBuildingPreference::with('user')->latest()->get();
        $investmentPreferences = BuyerInvestmentPreference::with('user')->latest()->get();

        return view('admin.buyers.properties-all-index', compact(
            'tab',
            'landPreferences',
            'buildingPreferences',
            'investmentPreferences'
        ));
    }
}
