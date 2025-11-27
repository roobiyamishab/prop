<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BuyerLandPreference;
use App\Models\BuyerBuildingPreference;
use App\Models\BuyerInvestmentPreference;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminBuyerPreferenceController extends Controller
{
    /* -------------------- LAND: STORE (ADMIN) -------------------- */




  public function storeLand(Request $request)
{
   
    $validated = $request->validate([
        'user_id'              => 'required|exists:users,id',
        'districts'            => 'nullable|string|max:255',
        'locations'            => 'nullable|string|max:255',
        'land_size_unit'       => 'required|in:cent,acre',
        'budget_per_cent'      => 'nullable|numeric',
        'zoning_preference'    => 'nullable|string|max:50',
        'timeline_to_purchase' => 'nullable|string|max:50',
        'mode_of_purchase'     => 'nullable|string|max:50',
        'advance_capacity'     => 'nullable|numeric|min:0|max:100',
        'amenities'            => 'nullable|array',
        'amenities.*'          => 'string|max:50',
        'documentation_speed'  => 'nullable|string|max:50',
        'property_condition'   => 'nullable|string|max:100',
        'infrastructure'       => 'nullable|string',
    ]);
   

    // Optional: Get the user model if you want their name for the message
    $user = User::find($validated['user_id']);

    $land = new BuyerLandPreference();

    $land->user_id = $validated['user_id'];
    $land->status  = 'active';

    // If you added created_by_admin_id in migration:
     $land->created_by_admin_id = Auth::guard('admin')->id();

    $land->preferred_districts = !empty($validated['districts'])
        ? array_map('trim', explode(',', $validated['districts']))
        : null;

    $land->preferred_locations = !empty($validated['locations'])
        ? array_map('trim', explode(',', $validated['locations']))
        : null;

    $land->land_size_unit       = $validated['land_size_unit'];
    $land->budget_per_cent_min  = $validated['budget_per_cent'] ?? null;
    $land->budget_per_cent_max  = $validated['budget_per_cent'] ?? null;
    $land->zoning_preference    = $validated['zoning_preference'] ?? null;
    $land->timeline_to_purchase = $validated['timeline_to_purchase'] ?? null;
    $land->mode_of_purchase     = $validated['mode_of_purchase'] ?? null;
    $land->advance_capacity     = $validated['advance_capacity'] ?? null;
    $land->documentation_speed  = $validated['documentation_speed'] ?? null;
    $land->property_condition   = $validated['property_condition'] ?? null;

    $land->amenities_preference = $validated['amenities'] ?? null;
    $land->infra_preference     = $validated['infrastructure'] ?? null;

    $land->save();

   

    return back()->with(
        'success',
        'Land preference saved successfully for user: ' . ($user?->name ?? $validated['user_id'])
    );
}

    /* -------------------- BUILDING: STORE (ADMIN) -------------------- */
  public function storeBuilding(Request $request)
{
    $data = $request->validate([
        'user_id'                => 'required|exists:users,id',
        'districts'              => 'nullable|string',
        'building_type'          => 'nullable|string',
        'area_min'               => 'nullable|integer',
        'area_max'               => 'nullable|integer',
        'frontage_min'           => 'nullable|integer',
        'age_preference'         => 'nullable|string',
        'total_budget'           => 'nullable|numeric',
        'micro_locations'        => 'nullable|string',
        'distance_requirements'  => 'nullable|array',
        'distance_requirements.*'=> 'string',
        'rent_expectation'       => 'nullable|numeric',
    ]);

    $adminId = Auth::guard('admin')->id();
    $userId  = $data['user_id'];

    $building = new BuyerBuildingPreference();
    $building->user_id            = $userId;
    $building->status             = 'active';
    $building->created_by_admin_id= $adminId;

    $building->preferred_districts = !empty($data['districts'])
        ? array_map('trim', explode(',', $data['districts']))
        : null;

    $building->building_type  = $data['building_type'] ?? null;
    $building->area_min       = $data['area_min'] ?? null;
    $building->area_max       = $data['area_max'] ?? null;
    $building->frontage_min   = $data['frontage_min'] ?? null;
    $building->age_preference = $data['age_preference'] ?? null;

    $building->total_budget_min = $data['total_budget'] ?? null;
    $building->total_budget_max = $data['total_budget'] ?? null;

    $building->micro_locations = !empty($data['micro_locations'])
        ? array_map('trim', explode(',', $data['micro_locations']))
        : null;

    $building->distance_requirement = !empty($data['distance_requirements'])
        ? implode(',', $data['distance_requirements'])
        : null;

    $building->rent_expectation = $data['rent_expectation'] ?? null;

    $building->save();

    $user = User::find($userId);

    return back()->with(
        'success',
        'Building preferences saved successfully for user: ' . ($user?->name ?? $userId)
    );
}


    /* -------------------- INVESTMENT: STORE (ADMIN) -------------------- */
 public function storeInvestment(Request $request)
{
    $data = $request->validate([
        'user_id'                 => 'required|exists:users,id',
        'districts'               => 'nullable|string',
        'locations'               => 'nullable|string',
        'investment_property_type'=> 'nullable|string',
        'budget_range'            => 'nullable|numeric',
        'profit_expectation_year' => 'nullable|numeric',
    ]);

    $adminId = Auth::guard('admin')->id();
    $userId  = $data['user_id'];

    $investment = new BuyerInvestmentPreference();

    $investment->user_id            = $userId;
    $investment->status             = 'active';
    $investment->created_by_admin_id= $adminId;   // works if column exists

    $investment->preferred_districts = !empty($data['districts'])
        ? array_map('trim', explode(',', $data['districts']))
        : null;

    $investment->preferred_locations = !empty($data['locations'])
        ? array_map('trim', explode(',', $data['locations']))
        : null;

    $investment->investment_property_type = $data['investment_property_type'] ?? null;
    $investment->investment_budget_min    = $data['budget_range'] ?? null;
    $investment->investment_budget_max    = $data['budget_range'] ?? null;
    $investment->profit_expectation_year  = $data['profit_expectation_year'] ?? null;

    $investment->save();

    $user = User::find($userId);

    return back()->with(
        'success',
        'Investment preferences saved successfully for user: ' . ($user?->name ?? $userId)
    );
}

    /* -------------------- STATUS UPDATES (ADMIN) -------------------- */

    public function updateLandStatus(BuyerLandPreference $land, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:active,urgent,completed',
        ]);

        $land->status = $data['status'];
        $land->save();

        return back()->with('success', 'Land requirement marked as ' . ucfirst($land->status) . '.');
    }

    public function updateBuildingStatus(BuyerBuildingPreference $building, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:active,urgent,completed',
        ]);

        $building->status = $data['status'];
        $building->save();

        return back()->with('success', 'Building requirement marked as ' . ucfirst($building->status) . '.');
    }

    public function updateInvestmentStatus(BuyerInvestmentPreference $investment, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:active,urgent,completed',
        ]);

        $investment->status = $data['status'];
        $investment->save();

        return back()->with('success', 'Investment requirement marked as ' . ucfirst($investment->status) . '.');
    }

    /* -------------------- LAND: UPDATE (MODAL, ADMIN) -------------------- */


public function updateLand(Request $request, BuyerLandPreference $land)
{
    $data = $request->validate([
        'preferred_districts'       => 'nullable|string|max:255',
        'preferred_locations'       => 'nullable|string|max:255',
        'land_size_unit'            => 'required|in:cent,acre',
        'budget_per_cent_min'       => 'nullable|numeric',
        'budget_per_cent_max'       => 'nullable|numeric',
        'zoning_preference'         => 'nullable|string|max:50',
        'timeline_to_purchase'      => 'nullable|string|max:50',
        'mode_of_purchase'          => 'nullable|string|max:50',
        'advance_capacity'          => 'nullable|numeric|min:0|max:100',
        'amenities_preference'      => 'nullable|string',
        'infra_preference' => 'nullable|string',
        'status'                    => 'nullable|in:active,urgent,completed',
    ]);

    // Preferred districts (comma separated → array)
    $land->preferred_districts = !empty($data['preferred_districts'])
        ? array_map('trim', explode(',', $data['preferred_districts']))
        : null;

    // Preferred locations (comma separated → array)
    $land->preferred_locations = !empty($data['preferred_locations'])
        ? array_map('trim', explode(',', $data['preferred_locations']))
        : null;

    // Simple scalar fields
    $land->land_size_unit       = $data['land_size_unit'];
    $land->budget_per_cent_min  = $data['budget_per_cent_min'] ?? null;
    $land->budget_per_cent_max  = $data['budget_per_cent_max'] ?? null;
    $land->zoning_preference    = $data['zoning_preference'] ?? null;
    $land->timeline_to_purchase = $data['timeline_to_purchase'] ?? null;
    $land->mode_of_purchase     = $data['mode_of_purchase'] ?? null;
    $land->advance_capacity     = $data['advance_capacity'] ?? null;

    // Amenities (comma-separated string → array)
    if (!empty($data['amenities_preference'])) {
        $land->amenities_preference = array_map(
            'trim',
            explode(',', $data['amenities_preference'])
        );
    } else {
        $land->amenities_preference = null;
    }

    // Infrastructure preference
    $land->infra_preference = $data['infrastructure_preference'] ?? null;

    // Status from the dropdown
    if (isset($data['status'])) {
        $land->status = $data['status'];
    }

    $land->save();

    return back()->with('success', 'Land preference updated successfully.');
}

    /* -------------------- BUILDING: UPDATE (MODAL, ADMIN) -------------------- */

   public function updateBuilding(Request $request, BuyerBuildingPreference $building)
{
    $data = $request->validate([
        'building_type'        => 'nullable|string|max:100',
        'preferred_districts'  => 'nullable|string|max:255',
        'micro_locations'      => 'nullable|string|max:255',
        'area_min'             => 'nullable|numeric',
        'area_max'             => 'nullable|numeric',
        'frontage_min'         => 'nullable|numeric',
        'age_preference'       => 'nullable|string|max:50',
        'total_budget_min'     => 'nullable|numeric',
        'total_budget_max'     => 'nullable|numeric',
        'distance_requirement' => 'nullable|string',
        'rent_expectation'     => 'nullable|numeric',
        'status'               => 'nullable|in:active,urgent,completed',
    ]);

    $building->building_type = $data['building_type'] ?? null;

    $building->preferred_districts = !empty($data['preferred_districts'])
        ? array_map('trim', explode(',', $data['preferred_districts']))
        : null;

    $building->micro_locations = !empty($data['micro_locations'])
        ? array_map('trim', explode(',', $data['micro_locations']))
        : null;

    $building->area_min             = $data['area_min'] ?? null;
    $building->area_max             = $data['area_max'] ?? null;
    $building->frontage_min         = $data['frontage_min'] ?? null;
    $building->age_preference       = $data['age_preference'] ?? null;
    $building->total_budget_min     = $data['total_budget_min'] ?? null;
    $building->total_budget_max     = $data['total_budget_max'] ?? null;
    $building->distance_requirement = $data['distance_requirement'] ?? null;
    $building->rent_expectation     = $data['rent_expectation'] ?? null;

    if (isset($data['status'])) {
        $building->status = $data['status'];
    }

    $building->save();

    return back()->with('success', 'Building preference updated successfully.');
}

    /* -------------------- INVESTMENT: UPDATE (MODAL, ADMIN) -------------------- */
public function updateInvestment(Request $request, BuyerInvestmentPreference $investment)
{
    // Admin can update any investment
    $data = $request->validate([
        'investment_property_type' => 'nullable|string|max:100',
        'preferred_districts'      => 'nullable|string|max:255',
        'preferred_locations'      => 'nullable|string|max:255',
        'investment_budget_min'    => 'nullable|numeric',
        'investment_budget_max'    => 'nullable|numeric',
        'profit_expectation_year'  => 'nullable|numeric|min:0|max:100',
        'status'                   => 'nullable|in:active,urgent,completed',
    ]);

    // Property type
    $investment->investment_property_type = $data['investment_property_type'] ?? null;

    // Preferred districts
    $investment->preferred_districts = !empty($data['preferred_districts'])
        ? array_map('trim', explode(',', $data['preferred_districts']))
        : null;

    // Preferred locations
    $investment->preferred_locations = !empty($data['preferred_locations'])
        ? array_map('trim', explode(',', $data['preferred_locations']))
        : null;

    // Budget
    $investment->investment_budget_min = $data['investment_budget_min'] ?? null;
    $investment->investment_budget_max = $data['investment_budget_max'] ?? null;

    // Profit expectation
    $investment->profit_expectation_year = $data['profit_expectation_year'] ?? null;

    // Status
    if (!empty($data['status'])) {
        $investment->status = $data['status'];
    }

    $investment->save();

    return back()->with('success', 'Investment preference updated successfully.');
}

public function showLand(BuyerLandPreference $land)
{
    $buyer = $land->user;

    return view('admin.buyers.land-show', compact('land', 'buyer'));
}



public function showBuilding(BuyerBuildingPreference $building)
{
    $buyer = $building->user;
    return view('admin.buyers.building-show', compact('building', 'buyer'));
}

public function showInvestment(BuyerInvestmentPreference $investment)
{
    $buyer = $investment->user;
    return view('admin.buyers.investment-show', compact('investment', 'buyer'));
}
public function destroyLand(\App\Models\BuyerLandPreference $land)
{
    $buyerId = $land->user_id; // or $land->buyer_id depending on your column
    $land->delete();

    return redirect()
        ->route('admin.buyer.properties.index', ['buyer' => $buyerId, 'tab' => 'land'])
        ->with('success', 'Land preference deleted successfully.');
}

public function destroyBuilding(\App\Models\BuyerBuildingPreference $building)
{
    $buyerId = $building->user_id;
    $building->delete();

    return redirect()
        ->route('admin.buyer.properties.index', ['buyer' => $buyerId, 'tab' => 'building'])
        ->with('success', 'Building preference deleted successfully.');
}

public function destroyInvestment(\App\Models\BuyerInvestmentPreference $investment)
{
    $buyerId = $investment->user_id;
    $investment->delete();

    return redirect()
        ->route('admin.buyer.properties.index', ['buyer' => $buyerId, 'tab' => 'investment'])
        ->with('success', 'Investment preference deleted successfully.');
}

}
