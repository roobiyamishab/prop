<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SellerLandListing;
use App\Models\SellerBuildingListing;
use App\Models\SellerInvestmentListing;
use App\Models\PropertyCode;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerListingController extends Controller
{
    /* =========================================================
     * Helpers
     * ========================================================= */

    private function locationNamesFromIds(?int $countryId, ?int $stateId, ?int $districtId): array
    {
        $countryName  = null;
        $stateName    = null;
        $districtName = null;

        if (!empty($countryId)) {
            $countryName = DB::table('countries')->where('id', $countryId)->value('name');
        }
        if (!empty($stateId)) {
            $stateName = DB::table('states')->where('id', $stateId)->value('name');
        }
        if (!empty($districtId)) {
            $districtName = DB::table('cities')->where('id', $districtId)->value('name');
        }

        return [$countryName, $stateName, $districtName];
    }

    /* =========================================================
     * LAND: STORE
     * ========================================================= */
    public function storeLand(Request $request)
    {
        $data = $request->validate([
            // IDs (preferred)
            'country_id'   => 'nullable|exists:countries,id',
            'state_id'     => 'nullable|exists:states,id',
            'district_id'  => 'nullable|exists:cities,id',

            // district text is only required if district_id is empty
            'district'     => 'nullable|required_without:district_id|string|max:120',

            'taluk'        => 'nullable|string|max:120',
            'village'      => 'nullable|string|max:120',
            'exact_location' => 'nullable|string|max:255',
            'landmark'     => 'nullable|string|max:255',
            'google_map_link' => 'nullable|string',

            'land_area'    => 'nullable|numeric',
            'land_unit'    => 'nullable|in:cent,acre,sqft',
            'road_frontage'=> 'nullable|integer',
            'plot_shape'   => 'nullable|string|max:100',
            'zoning_type'  => 'nullable|string|max:120',
            'ownership_type'=> 'nullable|string|max:100',
            'restrictions' => 'nullable|string',

            'expected_price_per_cent' => 'nullable|numeric',
            'negotiability'=> 'nullable|string|max:50',
            'expected_advance_pct' => 'nullable|integer|min:0|max:100',
            'sale_timeline'=> 'nullable|string|max:100',
            'land_type'    => 'nullable|string|max:100',
            'current_use'  => 'nullable|string|max:100',

            // select sends 0/1
            'electricity'  => 'nullable|in:0,1',
            'water'        => 'nullable|in:0,1',
            'drainage'     => 'nullable|in:0,1',

            // files
            'land_tax_receipt' => 'nullable|file',
            'location_sketch'  => 'nullable|file',
            'photos.*'         => 'nullable|image',
            'video'            => 'nullable|file',
        ]);

        $userId = Auth::id();

        // Names from IDs (for fallback to district string)
        [, , $districtNameFromId] = $this->locationNamesFromIds(
            $data['country_id'] ?? null,
            $data['state_id'] ?? null,
            $data['district_id'] ?? null
        );

        $finalDistrictText = $districtNameFromId ?: ($data['district'] ?? null);

        // FILE UPLOADS
        $documents = [];
        if ($request->hasFile('land_tax_receipt')) {
            $documents['land_tax_receipt'] = $request->file('land_tax_receipt')->store('land/documents', 'public');
        }
        if ($request->hasFile('location_sketch')) {
            $documents['location_sketch'] = $request->file('location_sketch')->store('land/documents', 'public');
        }

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('land/photos', 'public');
            }
        }

        $videos = [];
        if ($request->hasFile('video')) {
            $videos[] = $request->file('video')->store('land/videos', 'public');
        }

        SellerLandListing::create([
            'user_id'       => $userId,
            'property_code' => $this->generatePropertyCode('LND'),
            'status'        => 'normal',

            // IDs
            'country_id'    => $data['country_id'] ?? null,
            'state_id'      => $data['state_id'] ?? null,
            'district_id'   => $data['district_id'] ?? null,

            // text (your table has district text)
            'district'      => $finalDistrictText,
            'taluk'         => $data['taluk'] ?? null,
            'village'       => $data['village'] ?? null,
            'exact_location'=> $data['exact_location'] ?? null,
            'landmark'      => $data['landmark'] ?? null,
            'google_map_link'=> $data['google_map_link'] ?? null,

            'land_area'     => $data['land_area'] ?? null,
            'land_unit'     => $data['land_unit'] ?? 'cent',
            'road_frontage' => $data['road_frontage'] ?? null,
            'plot_shape'    => $data['plot_shape'] ?? null,

            'zoning_type'   => $data['zoning_type'] ?? null,
            'ownership_type'=> $data['ownership_type'] ?? null,
            'restrictions'  => $data['restrictions'] ?? null,

            'expected_price_per_cent' => $data['expected_price_per_cent'] ?? null,
            'negotiability' => $data['negotiability'] ?? null,
            'expected_advance_pct' => $data['expected_advance_pct'] ?? null,
            'sale_timeline' => $data['sale_timeline'] ?? null,

            'land_type'     => $data['land_type'] ?? null,
            'current_use'   => $data['current_use'] ?? null,

            'electricity'   => ((int)($data['electricity'] ?? 0) === 1),
            'water'         => ((int)($data['water'] ?? 0) === 1),
            'drainage'      => ((int)($data['drainage'] ?? 0) === 1),

            'documents'     => !empty($documents) ? $documents : null,
            'photos'        => !empty($photos) ? $photos : null,
            'videos'        => !empty($videos) ? $videos : null,
        ]);

        return back()->with('success', 'Land listing submitted successfully.');
    }

    /* =========================================================
     * BUILDING: STORE
     * ========================================================= */
    public function storeBuilding(Request $request)
    {
        $data = $request->validate([
            'country_id'   => 'nullable|exists:countries,id',
            'state_id'     => 'nullable|exists:states,id',
            'district_id'  => 'nullable|exists:cities,id',

            'district'     => 'nullable|required_without:district_id|string|max:120',

            'area'              => 'nullable|string|max:120',
            'street_name'       => 'nullable|string|max:255',
            'landmark'          => 'nullable|string|max:255',
            'map_link'          => 'nullable|string',

            'building_type'     => 'nullable|string|max:150',
            'total_plot_area'   => 'nullable|numeric',
            'builtup_area'      => 'nullable|numeric',
            'floors'            => 'nullable|integer',
            'construction_year' => 'nullable|integer',
            'lift_available'    => 'nullable|in:0,1',
            'parking_slots'     => 'nullable|integer',
            'road_frontage'     => 'nullable|integer',

            'expected_price'    => 'nullable|numeric',
            'negotiability'     => 'nullable|string|max:100',
            'sell_timeline'     => 'nullable|string|max:100',

            'building_permit'        => 'nullable|file',
            'completion_certificate' => 'nullable|file',
            'ownership_documents.*'  => 'nullable|file',
            'photos.*'               => 'nullable|image',
        ]);

        $userId = Auth::id();

        [, , $districtNameFromId] = $this->locationNamesFromIds(
            $data['country_id'] ?? null,
            $data['state_id'] ?? null,
            $data['district_id'] ?? null
        );
        $finalDistrictText = $districtNameFromId ?: ($data['district'] ?? null);

        $documents = [];

        if ($request->hasFile('building_permit')) {
            $documents['building_permit'] = $request->file('building_permit')->store('building/documents', 'public');
        }
        if ($request->hasFile('completion_certificate')) {
            $documents['completion_certificate'] = $request->file('completion_certificate')->store('building/documents', 'public');
        }
        if ($request->hasFile('ownership_documents')) {
            $ownershipDocs = [];
            foreach ($request->file('ownership_documents') as $doc) {
                $ownershipDocs[] = $doc->store('building/documents', 'public');
            }
            $documents['ownership_documents'] = $ownershipDocs;
        }

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('building/photos', 'public');
            }
        }

        SellerBuildingListing::create([
            'user_id'       => $userId,
            'property_code' => $this->generatePropertyCode('BLD'),
            'status'        => 'normal',

            'country_id'    => $data['country_id'] ?? null,
            'state_id'      => $data['state_id'] ?? null,
            'district_id'   => $data['district_id'] ?? null,

            'district'      => $finalDistrictText,
            'area'          => $data['area'] ?? null,
            'street_name'   => $data['street_name'] ?? null,
            'landmark'      => $data['landmark'] ?? null,
            'map_link'      => $data['map_link'] ?? null,

            'building_type'     => $data['building_type'] ?? null,
            'total_plot_area'   => $data['total_plot_area'] ?? null,
            'builtup_area'      => $data['builtup_area'] ?? null,
            'floors'            => $data['floors'] ?? null,
            'construction_year' => $data['construction_year'] ?? null,
            'lift_available'    => ((int)($data['lift_available'] ?? 0) === 1),
            'parking_slots'     => $data['parking_slots'] ?? null,
            'road_frontage'     => $data['road_frontage'] ?? null,

            'expected_price'    => $data['expected_price'] ?? null,
            'negotiability'     => $data['negotiability'] ?? null,
            'sell_timeline'     => $data['sell_timeline'] ?? null,

            'documents'         => !empty($documents) ? $documents : null,
            'photos'            => !empty($photos) ? $photos : null,
        ]);

        return back()->with('success', 'Building listing submitted successfully.');
    }

    /* =========================================================
     * INVESTMENT: STORE
     * ========================================================= */
    public function storeInvestment(Request $request)
    {
        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'project_type'        => 'nullable|string|max:200',
            // 'project_description' => 'nullable|string',

            'country_id'   => 'nullable|exists:countries,id',
            'state_id'     => 'nullable|exists:states,id',
            'district_id'  => 'nullable|exists:cities,id',

            'district'     => 'nullable|required_without:district_id|string|max:120',
            'micro_location' => 'nullable|string|max:255',

            'project_cost'        => 'nullable|numeric',
            'investment_required' => 'nullable|numeric',
            'profit_sharing_model'=> 'nullable|string|max:255',
            'payback_period'      => 'nullable|string|max:100',
            'project_status'      => 'nullable|string|max:150',
            'completion_percent'  => 'nullable|integer|min:0|max:100',

            'dpr'         => 'nullable|file',
            'layout_plan' => 'nullable|file',
            'approvals.*' => 'nullable|file',
            'financials'  => 'nullable|file',
        ]);

        $userId = Auth::id();

        [, , $districtNameFromId] = $this->locationNamesFromIds(
            $data['country_id'] ?? null,
            $data['state_id'] ?? null,
            $data['district_id'] ?? null
        );
        $finalDistrictText = $districtNameFromId ?: ($data['district'] ?? null);

        $documents = [];
        if ($request->hasFile('dpr')) {
            $documents['dpr'] = $request->file('dpr')->store('investment/documents', 'public');
        }
        if ($request->hasFile('layout_plan')) {
            $documents['layout_plan'] = $request->file('layout_plan')->store('investment/documents', 'public');
        }
        if ($request->hasFile('approvals')) {
            $docs = [];
            foreach ($request->file('approvals') as $doc) {
                $docs[] = $doc->store('investment/documents', 'public');
            }
            $documents['approvals'] = $docs;
        }
        if ($request->hasFile('financials')) {
            $documents['financials'] = $request->file('financials')->store('investment/documents', 'public');
        }

        SellerInvestmentListing::create([
            'user_id'       => $userId,
            'property_code' => $this->generatePropertyCode('INV'),
            'status'        => 'normal',

            'country_id'    => $data['country_id'] ?? null,
            'state_id'      => $data['state_id'] ?? null,
            'district_id'   => $data['district_id'] ?? null,

            'project_name'        => $data['project_name'],
            'project_type'        => $data['project_type'] ?? null,
            // 'project_description' => $data['project_description'] ?? null,

            'district'            => $finalDistrictText,
            'micro_location'      => $data['micro_location'] ?? null,

            'project_cost'        => $data['project_cost'] ?? null,
            'investment_required' => $data['investment_required'] ?? null,
            'profit_sharing_model'=> $data['profit_sharing_model'] ?? null,
            'payback_period'      => $data['payback_period'] ?? null,
            'project_status'      => $data['project_status'] ?? null,
            'completion_percent'  => $data['completion_percent'] ?? null,

            'documents'           => !empty($documents) ? $documents : null,
        ]);

        return back()->with('success', 'Investment opportunity submitted successfully.');
    }

    /* =========================================================
     * PROPERTY CODE GENERATOR
     * ========================================================= */
    protected function generatePropertyCode(string $prefix): string
    {
        $last = PropertyCode::where('prefix', $prefix)->max('number');
        $nextNumber = $last ? $last + 1 : 100;

        PropertyCode::create([
            'prefix' => $prefix,
            'number' => $nextNumber,
        ]);

        return $prefix . $nextNumber;
    }

    /* =========================================================
     * STATUS UPDATES (OWNER ONLY)
     * ========================================================= */
    public function updateLandStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:normal,hot,urgent,sold,closed,booked,off_market'],
        ]);

        $land = SellerLandListing::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $land->status = $data['status'];
        $land->save();

        return back()->with('success', 'Land listing status updated.');
    }

    public function updateBuildingStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:normal,hot,urgent,sold,closed,booked,off_market'],
        ]);

        $building = SellerBuildingListing::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $building->status = $data['status'];
        $building->save();

        return back()->with('success', 'Building listing status updated.');
    }

    public function updateInvestmentStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:normal,hot,urgent,sold,closed,booked,off_market'],
        ]);

        $inv = SellerInvestmentListing::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $inv->status = $data['status'];
        $inv->save();

        return back()->with('success', 'Investment listing status updated.');
    }

    /* =========================================================
     * LAND: UPDATE (OWNER ONLY)
     * ========================================================= */
   public function updateLand(Request $request, $id)
{
    // ✅ Option B: fetch by id + user_id (no abort_if needed)
    $land = SellerLandListing::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $data = $request->validate([
        'country_id'   => 'nullable|exists:countries,id',
        'state_id'     => 'nullable|exists:states,id',
        'district_id'  => 'nullable|exists:cities,id',

        // district text only required if district_id not provided
        'district'     => 'nullable|required_without:district_id|string|max:120',

        'taluk'        => 'nullable|string|max:120',
        'village'      => 'nullable|string|max:120',
        'exact_location' => 'nullable|string|max:255',
        'landmark'     => 'nullable|string|max:255',
        'survey_no'    => 'nullable|string|max:120',
        'google_map_link' => 'nullable|string',

        'land_area'    => 'nullable|numeric',
        'land_unit'    => 'nullable|in:cent,acre,sqft',
        'proximity'    => 'nullable|string|max:150',
        'road_frontage'=> 'nullable|integer',
        'plot_shape'   => 'nullable|string|max:100',

        'zoning_type'  => 'nullable|string|max:120',
        'ownership_type'=> 'nullable|string|max:100',
        'restrictions' => 'nullable|string',

        'expected_price_per_cent' => 'nullable|numeric',
        'negotiability'=> 'nullable|string|max:50',
        'expected_advance_pct' => 'nullable|integer|min:0|max:100',
        'min_offer_expected'   => 'nullable|numeric',
        'market_value_info'    => 'nullable|string',

        'land_type'    => 'nullable|string|max:100',
        'current_use'  => 'nullable|string|max:100',

        // your form sends 1/0
        'electricity'  => 'nullable|in:0,1',
        'water'        => 'nullable|in:0,1',
        'drainage'     => 'nullable|in:0,1',
        'compound_wall'=> 'nullable|in:0,1',

        'sale_timeline'=> 'nullable|string|max:100',

        // uploads (same names as your modal)
        'land_tax_receipt' => 'nullable|file',
        'location_sketch'  => 'nullable|file',
        'photos.*'         => 'nullable|image',
        'video'            => 'nullable|file',
    ]);

    // ✅ keep existing JSON arrays safely
    $documents = is_array($land->documents) ? $land->documents : (json_decode($land->documents, true) ?: []);
    $photos    = is_array($land->photos)    ? $land->photos    : (json_decode($land->photos, true) ?: []);
    $videos    = is_array($land->videos)    ? $land->videos    : (json_decode($land->videos, true) ?: []);

    // ✅ if district_id present, use DB name, else fallback to typed district
    $districtNameFromId = null;
    if (!empty($data['district_id'])) {
        $districtNameFromId = \App\Models\City::where('id', $data['district_id'])->value('name');
    }
    $finalDistrictText = $districtNameFromId ?: ($data['district'] ?? $land->district);

    // ✅ update ONLY if field exists in request (prevents nulling)
    $land->country_id = $request->filled('country_id') ? $data['country_id'] : $land->country_id;
    $land->state_id   = $request->filled('state_id')   ? $data['state_id']   : $land->state_id;
    $land->district_id= $request->filled('district_id')? $data['district_id']: $land->district_id;

    if ($request->has('district_id') || $request->has('district')) {
        $land->district = $finalDistrictText;
    }

    foreach ([
        'taluk','village','exact_location','landmark','survey_no','google_map_link',
        'land_area','land_unit','proximity','road_frontage','plot_shape',
        'zoning_type','ownership_type','restrictions',
        'expected_price_per_cent','negotiability','expected_advance_pct',
        'min_offer_expected','market_value_info',
        'land_type','current_use','sale_timeline',
    ] as $field) {
        if ($request->has($field)) {
            $land->{$field} = $data[$field] ?? null;
        }
    }

    // ✅ booleans (only update if field present)
    if ($request->has('electricity'))   $land->electricity   = ((int)$data['electricity'] === 1);
    if ($request->has('water'))         $land->water         = ((int)$data['water'] === 1);
    if ($request->has('drainage'))      $land->drainage      = ((int)$data['drainage'] === 1);
    if ($request->has('compound_wall')) $land->compound_wall = ((int)$data['compound_wall'] === 1);

    // ✅ uploads: keep old + replace single where needed
    if ($request->hasFile('land_tax_receipt')) {
        $documents['land_tax_receipt'] = $request->file('land_tax_receipt')->store('land/documents', 'public');
    }
    if ($request->hasFile('location_sketch')) {
        $documents['location_sketch'] = $request->file('location_sketch')->store('land/documents', 'public');
    }

    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $photos[] = $photo->store('land/photos', 'public');
        }
    }

    if ($request->hasFile('video')) {
        // replace video
        $videos = [];
        $videos[] = $request->file('video')->store('land/videos', 'public');
    }

    $land->documents = !empty($documents) ? $documents : null;
    $land->photos    = !empty($photos) ? $photos : null;
    $land->videos    = !empty($videos) ? $videos : null;

    $land->save();

    return back()->with('success', 'Land listing updated successfully.');
}

    /* =========================================================
     * BUILDING: UPDATE (OWNER ONLY)
     * ========================================================= */
    public function updateBuilding(Request $request, $id)
    {
        $building = SellerBuildingListing::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'country_id'   => 'nullable|exists:countries,id',
            'state_id'     => 'nullable|exists:states,id',
            'district_id'  => 'nullable|exists:cities,id',

            'district'     => 'nullable|required_without:district_id|string|max:120',

            'area'              => 'nullable|string|max:120',
            'street_name'       => 'nullable|string|max:255',
            'landmark'          => 'nullable|string|max:255',
            'map_link'          => 'nullable|string',

            'building_type'     => 'nullable|string|max:150',
            'total_plot_area'   => 'nullable|numeric',
            'builtup_area'      => 'nullable|numeric',
            'floors'            => 'nullable|integer',
            'construction_year' => 'nullable|integer',
            'lift_available'    => 'nullable|in:0,1',
            'parking_slots'     => 'nullable|integer',
            'road_frontage'     => 'nullable|integer',

            'expected_price'    => 'nullable|numeric',
            'negotiability'     => 'nullable|string|max:100',
            'sell_timeline'     => 'nullable|string|max:100',

            'building_permit'        => 'nullable|file',
            'completion_certificate' => 'nullable|file',
            'ownership_documents.*'  => 'nullable|file',
            'photos.*'               => 'nullable|image',
        ]);

        [, , $districtNameFromId] = $this->locationNamesFromIds(
            $data['country_id'] ?? null,
            $data['state_id'] ?? null,
            $data['district_id'] ?? null
        );
        $finalDistrictText = $districtNameFromId ?: ($data['district'] ?? null);

        $building->country_id  = $data['country_id'] ?? null;
        $building->state_id    = $data['state_id'] ?? null;
        $building->district_id = $data['district_id'] ?? null;

        $building->district    = $finalDistrictText;
        $building->area        = $data['area'] ?? null;
        $building->street_name = $data['street_name'] ?? null;
        $building->landmark    = $data['landmark'] ?? null;
        $building->map_link    = $data['map_link'] ?? null;

        $building->building_type     = $data['building_type'] ?? null;
        $building->total_plot_area   = $data['total_plot_area'] ?? null;
        $building->builtup_area      = $data['builtup_area'] ?? null;
        $building->floors            = $data['floors'] ?? null;
        $building->construction_year = $data['construction_year'] ?? null;
        $building->lift_available    = ((int)($data['lift_available'] ?? 0) === 1);
        $building->parking_slots     = $data['parking_slots'] ?? null;
        $building->road_frontage     = $data['road_frontage'] ?? null;

        $building->expected_price = $data['expected_price'] ?? null;
        $building->negotiability  = $data['negotiability'] ?? null;
        $building->sell_timeline  = $data['sell_timeline'] ?? null;

        $documents = is_array($building->documents) ? $building->documents : [];

        if ($request->hasFile('building_permit')) {
            $documents['building_permit'] = $request->file('building_permit')->store('building/documents', 'public');
        }
        if ($request->hasFile('completion_certificate')) {
            $documents['completion_certificate'] = $request->file('completion_certificate')->store('building/documents', 'public');
        }
        if ($request->hasFile('ownership_documents')) {
            $ownershipDocs = Arr::get($documents, 'ownership_documents', []);
            foreach ($request->file('ownership_documents') as $doc) {
                $ownershipDocs[] = $doc->store('building/documents', 'public');
            }
            $documents['ownership_documents'] = $ownershipDocs;
        }

        $photos = is_array($building->photos) ? $building->photos : [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('building/photos', 'public');
            }
        }

        $building->documents = !empty($documents) ? $documents : null;
        $building->photos    = !empty($photos) ? $photos : null;

        $building->save();

        return back()->with('success', 'Building listing updated successfully.');
    }

    /* =========================================================
     * INVESTMENT: UPDATE (OWNER ONLY)
     * ========================================================= */
    public function updateInvestment(Request $request, $id)
    {
        $investment = SellerInvestmentListing::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'project_name'       => 'required|string|max:255',
            'project_type'       => 'nullable|string|max:200',
            // 'project_description'=> 'nullable|string',

            'country_id'   => 'nullable|exists:countries,id',
            'state_id'     => 'nullable|exists:states,id',
            'district_id'  => 'nullable|exists:cities,id',

            'district'     => 'nullable|required_without:district_id|string|max:120',
            'micro_location' => 'nullable|string|max:255',

            'project_cost'        => 'nullable|numeric',
            'investment_required' => 'nullable|numeric',
            'profit_sharing_model'=> 'nullable|string|max:255',
            'payback_period'      => 'nullable|string|max:100',
            'project_status'      => 'nullable|string|max:255',
            'completion_percent'  => 'nullable|integer|min:0|max:100',

            'project_report' => 'nullable|file',
            'approvals.*'    => 'nullable|file',
        ]);

        [, , $districtNameFromId] = $this->locationNamesFromIds(
            $data['country_id'] ?? null,
            $data['state_id'] ?? null,
            $data['district_id'] ?? null
        );
        $finalDistrictText = $districtNameFromId ?: ($data['district'] ?? null);

        $investment->project_name        = $data['project_name'];
        $investment->project_type        = $data['project_type'] ?? null;
        // $investment->project_description = $data['project_description'] ?? null;

        $investment->country_id  = $data['country_id'] ?? null;
        $investment->state_id    = $data['state_id'] ?? null;
        $investment->district_id = $data['district_id'] ?? null;

        $investment->district       = $finalDistrictText;
        $investment->micro_location = $data['micro_location'] ?? null;

        $investment->project_cost        = $data['project_cost'] ?? null;
        $investment->investment_required = $data['investment_required'] ?? null;
        $investment->profit_sharing_model= $data['profit_sharing_model'] ?? null;
        $investment->payback_period      = $data['payback_period'] ?? null;
        $investment->project_status      = $data['project_status'] ?? null;
        $investment->completion_percent  = $data['completion_percent'] ?? null;

        $documents = is_array($investment->documents) ? $investment->documents : [];

        if ($request->hasFile('project_report')) {
            $documents['project_report'] = $request->file('project_report')->store('investment/documents', 'public');
        }

        if ($request->hasFile('approvals')) {
            $approvalDocs = Arr::get($documents, 'approvals', []);
            foreach ($request->file('approvals') as $doc) {
                $approvalDocs[] = $doc->store('investment/documents', 'public');
            }
            $documents['approvals'] = $approvalDocs;
        }

        $investment->documents = !empty($documents) ? $documents : null;
        $investment->save();

        return back()->with('success', 'Investment listing updated successfully.');
    }
}
