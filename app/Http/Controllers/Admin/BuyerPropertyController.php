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
    public function index(User $buyer, Request $request)
    {
         $tab = $request->get('tab', 'land');

    $landPreferences = $buyer->buyerLandPreferences()->latest()->get();
    $buildingPreferences = $buyer->buyerBuildingPreferences()->latest()->get();
    $investmentPreferences = $buyer->buyerInvestmentPreferences()->latest()->get();

    return view('admin.buyers.properties-index', compact(
        'buyer',
        'tab',
        'landPreferences',
        'buildingPreferences',
        'investmentPreferences'
    ));
    }
}
