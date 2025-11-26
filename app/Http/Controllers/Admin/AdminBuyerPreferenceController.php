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
        // no ownership check here – admin can edit any user
        $validated = $request->validate([
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
            'infrastructure'       => 'nullable|string',
        ]);

        $land->preferred_districts = !empty($validated['districts'])
            ? array_map('trim', explode(',', $validated['districts']))
            : null;

        $land->preferred_locations = !empty($validated['locations'])
            ? array_map('trim', explode(',', $validated['locations']))
            : null;

        $land->land_size_unit       = $validated['land_size_unit'] ?? $land->land_size_unit;
        $land->budget_per_cent_min  = $validated['budget_per_cent'] ?? $land->budget_per_cent_min;
        $land->budget_per_cent_max  = $validated['budget_per_cent'] ?? $land->budget_per_cent_max;
        $land->zoning_preference    = $validated['zoning_preference'] ?? $land->zoning_preference;
        $land->timeline_to_purchase = $validated['timeline_to_purchase'] ?? $land->timeline_to_purchase;
        $land->mode_of_purchase     = $validated['mode_of_purchase'] ?? $land->mode_of_purchase;
        $land->advance_capacity     = $validated['advance_capacity'] ?? $land->advance_capacity;

        $land->amenities_preference = $validated['amenities'] ?? $land->amenities_preference;
        $land->infra_preference     = $validated['infrastructure'] ?? $land->infra_preference;

        $land->save();

        return back()->with('success', 'Land preference updated successfully.');
    }

    /* -------------------- BUILDING: UPDATE (MODAL, ADMIN) -------------------- */

    public function updateBuilding(Request $request, BuyerBuildingPreference $building)
    {
        $validated = $request->validate([
            'districts'               => 'nullable|string|max:255',
            'building_type'           => 'nullable|string|max:100',
            'area_min'                => 'nullable|integer',
            'area_max'                => 'nullable|integer',
            'frontage_min'            => 'nullable|integer',
            'age_preference'          => 'nullable|string|max:100',
            'total_budget'            => 'nullable|numeric',
            'micro_locations'         => 'nullable|string|max:255',
            'distance_requirements'   => 'nullable|array',
            'distance_requirements.*' => 'string|max:50',
            'rent_expectation'        => 'nullable|numeric',
        ]);

        $building->preferred_districts = !empty($validated['districts'])
            ? array_map('trim', explode(',', $validated['districts']))
            : null;

        $building->building_type = $validated['building_type'] ?? $building->building_type;

        $building->area_min = $validated['area_min'] ?? $building->area_min;
        $building->area_max = $validated['area_max'] ?? $building->area_max;

        $building->frontage_min   = $validated['frontage_min'] ?? $building->frontage_min;
        $building->age_preference = $validated['age_preference'] ?? $building->age_preference;

        if (array_key_exists('total_budget', $validated)) {
            $building->total_budget_min = $validated['total_budget'];
            $building->total_budget_max = $validated['total_budget'];
        }

        $building->micro_locations = !empty($validated['micro_locations'])
            ? array_map('trim', explode(',', $validated['micro_locations']))
            : null;

        if (!empty($validated['distance_requirements'])) {
            $building->distance_requirement = implode(',', $validated['distance_requirements']);
        } else {
            $building->distance_requirement = null;
        }

        $building->rent_expectation = $validated['rent_expectation'] ?? $building->rent_expectation;

        $building->save();

        return back()->with('success', 'Building preference updated successfully.');
    }

    /* -------------------- INVESTMENT: UPDATE (MODAL, ADMIN) -------------------- */

    public function updateInvestment(Request $request, BuyerInvestmentPreference $investment)
    {
        $validated = $request->validate([
            'districts'                => 'nullable|string|max:255',
            'locations'                => 'nullable|string|max:255',
            'investment_property_type' => 'nullable|string|max:100',
            'budget_range'             => 'nullable|numeric',
            'profit_expectation_year'  => 'nullable|numeric|min:0|max:100',
        ]);

        $investment->preferred_districts = !empty($validated['districts'])
            ? array_map('trim', explode(',', $validated['districts']))
            : null;

        $investment->preferred_locations = !empty($validated['locations'])
            ? array_map('trim', explode(',', $validated['locations']))
            : null;

        $investment->investment_property_type =
            $validated['investment_property_type'] ?? $investment->investment_property_type;

        if (array_key_exists('budget_range', $validated)) {
            $investment->investment_budget_min = $validated['budget_range'];
            $investment->investment_budget_max = $validated['budget_range'];
        }

        if (array_key_exists('profit_expectation_year', $validated)) {
            $investment->profit_expectation_year = $validated['profit_expectation_year'];
        }

        $investment->save();

        return back()->with('success', 'Investment preference updated successfully.');
    }
}
