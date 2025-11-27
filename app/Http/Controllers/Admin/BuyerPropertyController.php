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

        $landPreferences = BuyerLandPreference::
            orderBy('created_at', 'desc')
            ->get();

        $buildingPreferences = BuyerBuildingPreference::
            orderBy('created_at', 'desc')
            ->get();

        $investmentPreferences = BuyerInvestmentPreference::
            orderBy('created_at', 'desc')
            ->get();

        return view('admin.buyers.properties-index', [
            'buyer'                 => $buyer,
            'tab'                   => $tab,
            'landPreferences'       => $landPreferences,
            'buildingPreferences'   => $buildingPreferences,
            'investmentPreferences' => $investmentPreferences,
        ]);
    }
}
