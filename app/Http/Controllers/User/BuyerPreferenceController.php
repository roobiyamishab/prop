<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BuyerLandPreference;
use App\Models\BuyerBuildingPreference;
use App\Models\BuyerInvestmentPreference;
use Illuminate\Support\Facades\Auth;

class BuyerPreferenceController extends Controller
{
    /* -------------------- LAND: STORE -------------------- */
    public function storeLand(Request $request)
    {
        $validated = $request->validate([
            'countries'           => 'nullable|string|max:255',   // NEW
            'states'              => 'nullable|string|max:255',   // NEW
            'districts'           => 'nullable|string|max:255',
            'locations'           => 'nullable|string|max:255',
            'land_size_unit'      => 'required|in:cent,acre',
            'budget_per_cent'     => 'nullable|numeric',
            'zoning_preference'   => 'nullable|string|max:50',
            'timeline_to_purchase'=> 'nullable|string|max:50',
            'mode_of_purchase'    => 'nullable|string|max:50',
            'advance_capacity'    => 'nullable|numeric|min:0|max:100',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'string|max:50',
            'documentation_speed' => 'nullable|string|max:50',
            'property_condition'  => 'nullable|string|max:100',
            'infra_preference'    => 'nullable|string',           // ✅ matches Blade + DB
        ]);

        $land = new BuyerLandPreference();

        // must set user_id because column is NOT NULL
        $land->user_id = auth()->id();
        $land->status  = 'active';

        // 🔹 Countries / States / Districts / Locations → arrays
        $land->preferred_countries = !empty($validated['countries'])
            ? array_map('trim', explode(',', $validated['countries']))
            : null;

        $land->preferred_states = !empty($validated['states'])
            ? array_map('trim', explode(',', $validated['states']))
            : null;

        $land->preferred_districts = !empty($validated['districts'])
            ? array_map('trim', explode(',', $validated['districts']))
            : null;

        $land->preferred_locations = !empty($validated['locations'])
            ? array_map('trim', explode(',', $validated['locations']))
            : null;

        // 🔹 Land size + budget
        $land->land_size_unit       = $validated['land_size_unit'];
        $land->budget_per_cent_min  = $validated['budget_per_cent'] ?? null;
        $land->budget_per_cent_max  = $validated['budget_per_cent'] ?? null;

        // 🔹 Other preferences
        $land->zoning_preference    = $validated['zoning_preference'] ?? null;
        $land->timeline_to_purchase = $validated['timeline_to_purchase'] ?? null;
        $land->mode_of_purchase     = $validated['mode_of_purchase'] ?? null;
        $land->advance_capacity     = $validated['advance_capacity'] ?? null;
        $land->documentation_speed  = $validated['documentation_speed'] ?? null;
        $land->property_condition   = $validated['property_condition'] ?? null;

        // 🔹 Arrays / JSON
        $land->amenities_preference = $validated['amenities'] ?? null;

        // Store infra_preference as array (split by comma), since it's cast as array
        $land->infra_preference = !empty($validated['infra_preference'])
            ? array_map('trim', explode(',', $validated['infra_preference']))
            : null;

        $land->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Land preference saved successfully.');
    }

    /* -------------------- BUILDING: STORE -------------------- */
  public function storeBuilding(Request $request)
{
    $userId = auth()->id();

    $data = $request->validate([
        // NEW: dropdown-based location
        'building_country'         => 'nullable|string|max:150',
        'building_state'           => 'nullable|string|max:150',
        'building_district'        => 'nullable|string|max:150',

        // OLD: free-text districts (optional, for backward compatibility)
        'districts'               => 'nullable|string',

        'building_type'           => 'nullable|string|max:120',
        'area_min'                => 'nullable|integer',
        'area_max'                => 'nullable|integer',
        'frontage_min'            => 'nullable|integer',
        'age_preference'          => 'nullable|string|max:100',
        'total_budget'            => 'nullable|numeric',
        'micro_locations'         => 'nullable|string',
        'distance_requirements'   => 'nullable|array',
        'distance_requirements.*' => 'string',
        'rent_expectation'        => 'nullable|numeric',
    ]);

    // Build arrays for JSON fields
    $preferredCountries = !empty($data['building_country'])
        ? [$data['building_country']]
        : null;

    $preferredStates = !empty($data['building_state'])
        ? [$data['building_state']]
        : null;

    // Districts: combine dropdown + old free-text if present
    $preferredDistricts = [];

    if (!empty($data['building_district'])) {
        $preferredDistricts[] = $data['building_district'];
    }

    if (!empty($data['districts'])) {
        $textDistricts = array_map('trim', explode(',', $data['districts']));
        $preferredDistricts = array_merge($preferredDistricts, $textDistricts);
    }

    if (empty($preferredDistricts)) {
        $preferredDistricts = null;
    } else {
        // remove duplicates & empty values
        $preferredDistricts = array_values(array_filter(array_unique($preferredDistricts)));
        if (!count($preferredDistricts)) {
            $preferredDistricts = null;
        }
    }

    BuyerBuildingPreference::updateOrCreate(
        ['user_id' => $userId],
        [
            'status'               => 'active',

            // 🔹 NEW: country/state/district as JSON arrays
            'preferred_countries'  => $preferredCountries,
            'preferred_states'     => $preferredStates,
            'preferred_districts'  => $preferredDistricts,

            'building_type'        => $data['building_type'] ?? null,
            'area_min'             => $data['area_min'] ?? null,
            'area_max'             => $data['area_max'] ?? null,
            'frontage_min'         => $data['frontage_min'] ?? null,
            'age_preference'       => $data['age_preference'] ?? null,
            'total_budget_min'     => $data['total_budget'] ?? null,
            'total_budget_max'     => $data['total_budget'] ?? null,

            'micro_locations'      => !empty($data['micro_locations'])
                ? array_map('trim', explode(',', $data['micro_locations']))
                : null,

            'distance_requirement' => !empty($data['distance_requirements'])
                ? implode(',', $data['distance_requirements'])
                : null,

            'rent_expectation'     => $data['rent_expectation'] ?? null,
        ]
    );

    return back()->with('success', 'Building preferences saved successfully.');
}

    /* -------------------- INVESTMENT: STORE -------------------- */
  public function storeInvestment(Request $request)
{
    $userId = auth()->id();

    $data = $request->validate([
        // NEW: location hierarchy for investment
        'investment_country'        => 'nullable|string|max:255',
        'investment_state'          => 'nullable|string|max:255',
        'investment_district'       => 'nullable|string|max:255',

        // OLD free-text (kept for backward compatibility)
        'districts'                 => 'nullable|string',
        'locations'                 => 'nullable|string',

        'investment_property_type'  => 'nullable|string|max:150',
        'budget_range'              => 'nullable|numeric',
        'profit_expectation_year'   => 'nullable|numeric|min:0|max:100',
    ]);

    BuyerInvestmentPreference::updateOrCreate(
        ['user_id' => $userId],
        [
            'status' => 'active',

            // 🔹 Country / State
            'preferred_countries' => !empty($data['investment_country'])
                ? [$data['investment_country']]
                : null,

            'preferred_states' => !empty($data['investment_state'])
                ? [$data['investment_state']]
                : null,

            // 🔹 District – prefer the single select, fall back to old "districts" text
            'preferred_districts' => !empty($data['investment_district'])
                ? [$data['investment_district']]
                : (
                    !empty($data['districts'])
                        ? array_map('trim', explode(',', $data['districts']))
                        : null
                ),

            // 🔹 Micro locations (comma-separated string → array)
            'preferred_locations' => !empty($data['locations'])
                ? array_map('trim', explode(',', $data['locations']))
                : null,

            // 🔹 Core investment fields
            'investment_property_type' => $data['investment_property_type'] ?? null,
            'investment_budget_min'    => $data['budget_range'] ?? null,
            'investment_budget_max'    => $data['budget_range'] ?? null,
            'profit_expectation_year'  => $data['profit_expectation_year'] ?? null,
        ]
    );

    return back()->with('success', 'Investment preferences saved successfully.');
}

    /* -------------------- STATUS UPDATES -------------------- */

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

    /* -------------------- LAND: UPDATE (MODAL) -------------------- */

    public function updateLand(Request $request, BuyerLandPreference $land)
    {
        // optional: ownership check
        if ($land->user_id !== auth()->id()) {
            abort(403, 'You are not allowed to edit this preference.');
        }

        $validated = $request->validate([
            'countries'           => 'nullable|string|max:255',
            'states'              => 'nullable|string|max:255',
            'districts'           => 'nullable|string|max:255',
            'locations'           => 'nullable|string|max:255',
            'land_size_unit'      => 'required|in:cent,acre',
            'budget_per_cent'     => 'nullable|numeric',
            'zoning_preference'   => 'nullable|string|max:50',
            'timeline_to_purchase'=> 'nullable|string|max:50',
            'mode_of_purchase'    => 'nullable|string|max:50',
            'advance_capacity'    => 'nullable|numeric|min:0|max:100',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'string|max:50',
            'infra_preference'    => 'nullable|string',
        ]);

        $land->preferred_countries = !empty($validated['countries'])
            ? array_map('trim', explode(',', $validated['countries']))
            : null;

        $land->preferred_states = !empty($validated['states'])
            ? array_map('trim', explode(',', $validated['states']))
            : null;

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

        $land->infra_preference = !empty($validated['infra_preference'])
            ? array_map('trim', explode(',', $validated['infra_preference']))
            : $land->infra_preference;

        $land->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Land preference updated successfully.');
    }

    /* -------------------- BUILDING: UPDATE (MODAL) -------------------- */

    public function updateBuilding(Request $request, BuyerBuildingPreference $building)
    {
        // ownership check – prevents editing others’ preferences
        if ($building->user_id !== auth()->id()) {
            abort(403, 'You are not allowed to edit this preference.');
        }

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

        // preferred districts (JSON)
        $building->preferred_districts = !empty($validated['districts'])
            ? array_map('trim', explode(',', $validated['districts']))
            : null;

        // building type
        $building->building_type = $validated['building_type'] ?? $building->building_type;

        // built-up area
        $building->area_min = $validated['area_min'] ?? $building->area_min;
        $building->area_max = $validated['area_max'] ?? $building->area_max;

        // frontage
        $building->frontage_min   = $validated['frontage_min'] ?? $building->frontage_min;
        $building->age_preference = $validated['age_preference'] ?? $building->age_preference;

        // budget (we store same value in min & max as earlier)
        if (array_key_exists('total_budget', $validated)) {
            $building->total_budget_min = $validated['total_budget'];
            $building->total_budget_max = $validated['total_budget'];
        }

        // micro locations (JSON)
        $building->micro_locations = !empty($validated['micro_locations'])
            ? array_map('trim', explode(',', $validated['micro_locations']))
            : null;

        // distance requirements → comma-separated string
        if (!empty($validated['distance_requirements'])) {
            $building->distance_requirement = implode(',', $validated['distance_requirements']);
        } else {
            $building->distance_requirement = null;
        }

        // rent expectation
        $building->rent_expectation = $validated['rent_expectation'] ?? $building->rent_expectation;

        $building->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Building preference updated successfully.');
    }

    /* -------------------- INVESTMENT: UPDATE (MODAL) -------------------- */

    public function updateInvestment(Request $request, BuyerInvestmentPreference $investment)
    {
        if ($investment->user_id !== auth()->id()) {
            abort(403, 'You are not allowed to edit this preference.');
        }

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

        return redirect()
            ->route('dashboard')
            ->with('success', 'Investment preference updated successfully.');
    }
}
