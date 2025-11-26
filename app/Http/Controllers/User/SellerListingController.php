<?php

namespace App\Http\Controllers\User;
use Illuminate\Support\Arr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerLandListing;
use App\Models\SellerBuildingListing;
use App\Models\SellerInvestmentListing;
use App\Models\PropertyCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 

class SellerListingController extends Controller
{
    /* ---------- LAND ---------- */
   public function storeLand(Request $request)
    {
        $data = $request->validate([
            'district'               => 'required|string|max:120',
            'taluk'                  => 'nullable|string|max:120',
            'village'                => 'nullable|string|max:120',
            'exact_location'         => 'nullable|string|max:255',
            'landmark'               => 'nullable|string|max:255',
            'google_map_link'        => 'nullable|string',
            'land_area'              => 'nullable|numeric',
            'land_unit'              => 'nullable|string',
            'road_frontage'          => 'nullable|integer',
            'plot_shape'             => 'nullable|string|max:100',
            'zoning_type'            => 'nullable|string|max:120',
            'ownership_type'         => 'nullable|string|max:100',
            'restrictions'           => 'nullable|string',
            'expected_price_per_cent'=> 'nullable|numeric',
            'negotiability'          => 'nullable|string|max:50',
            'expected_advance_pct'   => 'nullable|integer|min:0|max:100',
            'sale_timeline'          => 'nullable|string|max:100',
            'land_type'              => 'nullable|string|max:100',
            'current_use'            => 'nullable|string|max:100',

            // booleans
            'electricity'            => 'nullable',
            'water'                  => 'nullable',
            'drainage'               => 'nullable',

            // files
            'land_tax_receipt'       => 'nullable|file',
            'location_sketch'        => 'nullable|file',
            'photos.*'               => 'nullable|image',
            'video'                  => 'nullable|file',
        ]);

        $userId = auth()->id();   // 👈 must be logged in

        // --- FILE UPLOADS ---
        $documents = [];
        if ($request->hasFile('land_tax_receipt')) {
            $documents['land_tax_receipt'] = $request->file('land_tax_receipt')
                ->store('land/documents', 'public');
        }
        if ($request->hasFile('location_sketch')) {
            $documents['location_sketch'] = $request->file('location_sketch')
                ->store('land/documents', 'public');
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
            // 🔹 these three MUST go in and are in $fillable
            'user_id'       => $userId,
            'property_code' => $this->generatePropertyCode('LND'),
            'status'        => 'normal',

            'district'               => $data['district'],
            'taluk'                  => $data['taluk'] ?? null,
            'village'                => $data['village'] ?? null,
            'exact_location'         => $data['exact_location'] ?? null,
            'landmark'               => $data['landmark'] ?? null,
            'google_map_link'        => $data['google_map_link'] ?? null,

            'land_area'              => $data['land_area'] ?? null,
            'land_unit'              => $data['land_unit'] ?? 'cent',
            'road_frontage'          => $data['road_frontage'] ?? null,
            'plot_shape'             => $data['plot_shape'] ?? null,

            'zoning_type'            => $data['zoning_type'] ?? null,
            'ownership_type'         => $data['ownership_type'] ?? null,
            'restrictions'           => $data['restrictions'] ?? null,

            'expected_price_per_cent'=> $data['expected_price_per_cent'] ?? null,
            'negotiability'          => $data['negotiability'] ?? null,
            'expected_advance_pct'   => $data['expected_advance_pct'] ?? null,
            'sale_timeline'          => $data['sale_timeline'] ?? null,

            'land_type'              => $data['land_type'] ?? null,
            'current_use'            => $data['current_use'] ?? null,

            'electricity'            => $request->boolean('electricity'),
            'water'                  => $request->boolean('water'),
            'drainage'               => $request->boolean('drainage'),

            // if you’re using dropdown Complete/Partial/None:
            // 'compound_wall'         => $request->input('compound_wall'),

            'documents'             => $documents ?: null,
            'photos'                => $photos ?: null,
            'videos'                => $videos ?: null,
        ]);

        return back()->with('success', 'Land listing submitted successfully.');
    }


    /* ---------- BUILDING ---------- */
  public function storeBuilding(Request $request)
{
    $data = $request->validate([
        'district'          => 'required|string|max:120',
        'area'              => 'nullable|string|max:120',
        'street_name'       => 'nullable|string|max:255',
        'landmark'          => 'nullable|string|max:255',
        'map_link'          => 'nullable|string',
        'building_type'     => 'nullable|string|max:150',
        'total_plot_area'   => 'nullable|numeric',
        'builtup_area'      => 'nullable|numeric',
        'floors'            => 'nullable|integer',
        'construction_year' => 'nullable|integer',
        'lift_available'    => 'nullable',
        'parking_slots'     => 'nullable|integer',
        'road_frontage'     => 'nullable|integer',
        'expected_price'    => 'nullable|numeric',
        'negotiability'     => 'nullable|string|max:100',
        'sell_timeline'     => 'nullable|string|max:100',

        // files
        'building_permit'        => 'nullable|file',
        'completion_certificate' => 'nullable|file',
        'ownership_documents.*'  => 'nullable|file',
        'photos.*'               => 'nullable|image',
    ]);

    $userId = auth()->id();   // 👈 THIS is critical

    // ----- DOCUMENTS -----
    $documents = [];

    if ($request->hasFile('building_permit')) {
        $documents['building_permit'] = $request->file('building_permit')
            ->store('building/documents', 'public');
    }

    if ($request->hasFile('completion_certificate')) {
        $documents['completion_certificate'] = $request->file('completion_certificate')
            ->store('building/documents', 'public');
    }

    if ($request->hasFile('ownership_documents')) {
        $ownershipDocs = [];
        foreach ($request->file('ownership_documents') as $doc) {
            $ownershipDocs[] = $doc->store('building/documents', 'public');
        }
        $documents['ownership_documents'] = $ownershipDocs;
    }

    // ----- PHOTOS -----
    $photos = [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $photos[] = $photo->store('building/photos', 'public');
        }
    }

    // ----- CREATE ROW WITH user_id -----
    SellerBuildingListing::create([
        'user_id'           => $userId,
        'property_code'     => $this->generatePropertyCode('BLD'),
        'status'            => 'normal',

        'district'          => $data['district'],
        'area'              => $data['area'] ?? null,
        'street_name'       => $data['street_name'] ?? null,
        'landmark'          => $data['landmark'] ?? null,
        'map_link'          => $data['map_link'] ?? null,
        'building_type'     => $data['building_type'] ?? null,
        'total_plot_area'   => $data['total_plot_area'] ?? null,
        'builtup_area'      => $data['builtup_area'] ?? null,
        'floors'            => $data['floors'] ?? null,
        'construction_year' => $data['construction_year'] ?? null,
        'lift_available'    => $request->boolean('lift_available'),
        'parking_slots'     => $data['parking_slots'] ?? null,
        'road_frontage'     => $data['road_frontage'] ?? null,
        'expected_price'    => $data['expected_price'] ?? null,
        'negotiability'     => $data['negotiability'] ?? null,
        'sell_timeline'     => $data['sell_timeline'] ?? null,

        'documents'         => $documents ?: null,
        'photos'            => $photos ?: null,
    ]);

    return back()->with('success', 'Building listing submitted successfully.');
}
    /* ---------- INVESTMENT ---------- */
    public function storeInvestment(Request $request)
    {
        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'project_type'        => 'nullable|string|max:200',
            'project_description' => 'nullable|string',
            'district'            => 'nullable|string|max:100',
            'micro_location'      => 'nullable|string|max:255',
            'project_cost'        => 'nullable|numeric',
            'investment_required' => 'nullable|numeric',
            'profit_sharing_model'=> 'nullable|string|max:255',
            'payback_period'      => 'nullable|string|max:100',
            'project_status'      => 'nullable|string|max:150',
            'completion_percent'  => 'nullable|integer|min:0|max:100',

            'dpr'           => 'nullable|file',
            'layout_plan'   => 'nullable|file',
            'approvals.*'   => 'nullable|file',
            'financials'    => 'nullable|file',
        ]);

        $userId = auth()->id();

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
            'user_id'             => $userId,
            'property_code'       => $this->generatePropertyCode('INV'),
            'status'              => 'normal',

            'project_name'        => $data['project_name'],
            'project_type'        => $data['project_type'] ?? null,
            'district'            => $data['district'] ?? null,
            'micro_location'      => $data['micro_location'] ?? null,
            'landmark'            => null,
            'map_link'            => null,
            'project_cost'        => $data['project_cost'] ?? null,
            'investment_required' => $data['investment_required'] ?? null,
            'profit_sharing_model'=> $data['profit_sharing_model'] ?? null,
            'payback_period'      => $data['payback_period'] ?? null,
            'project_status'      => $data['project_status'] ?? null,
            'completion_percent'  => $data['completion_percent'] ?? null,
            'documents'           => $documents ?: null,
        ]);

        return back()->with('success', 'Investment opportunity submitted successfully.');
    }

    /* ---------- PROPERTY CODE GENERATOR ---------- */
    protected function generatePropertyCode(string $prefix): string
    {
        $last = PropertyCode::where('prefix', $prefix)->max('number');
        $nextNumber = $last ? $last + 1 : 100;

        PropertyCode::create([
            'prefix' => $prefix,
            'number' => $nextNumber,
        ]);

        return $prefix . $nextNumber; // e.g., LND100, BLD101, INV102
    }
public function updateLandStatus(Request $request, $id)
{
    $data = $request->validate([
        'status' => ['required', 'string', 'in:normal,hot,urgent,sold,closed'],
        // 'status_note' => ['nullable', 'string', 'max:1000'],
    ]);

    // ✅ Load existing row instead of creating a new one
    $land = SellerLandListing::where('id', $id)
        ->where('user_id', auth()->id()) // optional: ensure current user owns it
        ->firstOrFail();

    $land->status = $data['status'];
    // $land->status_note = $data['status_note'] ?? null;
    $land->save();  // ✅ UPDATE, not INSERT

    return back()->with('success', 'Land listing status updated.');
}

public function updateBuildingStatus(Request $request, $id)
{
    $data = $request->validate([
        'status' => ['required', 'string', 'in:normal,hot,urgent,sold,closed'],
    ]);

    $building = SellerBuildingListing::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $building->status = $data['status'];
    $building->save();

    return back()->with('success', 'Building listing status updated.');
}

public function updateInvestmentStatus(Request $request, $id)
{
    $data = $request->validate([
        'status' => ['required', 'string', 'in:normal,hot,urgent,sold,closed'],
    ]);

    $inv = SellerInvestmentListing::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $inv->status = $data['status'];
    $inv->save();

    return back()->with('success', 'Investment listing status updated.');
}
  public function updateLand(Request $request, SellerLandListing $land)
{
    $data = $request->validate([
        'district'               => 'required|string|max:120',
        'taluk'                  => 'nullable|string|max:120',
        'village'                => 'nullable|string|max:120',
        'exact_location'         => 'nullable|string|max:255',
        'landmark'               => 'nullable|string|max:255',
        'google_map_link'        => 'nullable|string',
        'land_area'              => 'nullable|numeric',
        'land_unit'              => 'nullable|string',
        'road_frontage'          => 'nullable|integer',
        'plot_shape'             => 'nullable|string|max:100',
        'zoning_type'            => 'nullable|string|max:120',
        'ownership_type'         => 'nullable|string|max:100',
        'restrictions'           => 'nullable|string',
        'expected_price_per_cent'=> 'nullable|numeric',
        'negotiability'          => 'nullable|string|max:50',
        'expected_advance_pct'   => 'nullable|integer|min:0|max:100',
        'sale_timeline'          => 'nullable|string|max:100',
        'land_type'              => 'nullable|string|max:100',
        'current_use'            => 'nullable|string|max:100',

        // booleans
        'electricity'            => 'nullable',
        'water'                  => 'nullable',
        'drainage'               => 'nullable',

        // files
        'land_tax_receipt'       => 'nullable|file',
        'location_sketch'        => 'nullable|file',
        'photos.*'               => 'nullable|image',
        'video'                  => 'nullable|file',
    ]);

    // (Optional) ensure only owner can edit
    // abort_if($land->user_id !== auth()->id(), 403);

    /* --- FILES: start from existing arrays and merge / replace --- */

    $documents = is_array($land->documents) ? $land->documents : [];

    if ($request->hasFile('land_tax_receipt')) {
        $documents['land_tax_receipt'] = $request->file('land_tax_receipt')
            ->store('land/documents', 'public');
    }

    if ($request->hasFile('location_sketch')) {
        $documents['location_sketch'] = $request->file('location_sketch')
            ->store('land/documents', 'public');
    }

    // photos: append new ones to existing
    $photos = is_array($land->photos) ? $land->photos : [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $photos[] = $photo->store('land/photos', 'public');
        }
    }

    // video: replace if new one is uploaded
    $videos = is_array($land->videos) ? $land->videos : [];
    if ($request->hasFile('video')) {
        $videos = [];
        $videos[] = $request->file('video')->store('land/videos', 'public');
    }

    /* --- UPDATE FIELDS (same structure as storeLand) --- */

    $land->district               = $data['district'];
    $land->taluk                  = $data['taluk'] ?? null;
    $land->village                = $data['village'] ?? null;
    $land->exact_location         = $data['exact_location'] ?? null;
    $land->landmark               = $data['landmark'] ?? null;
    $land->google_map_link        = $data['google_map_link'] ?? null;

    $land->land_area              = $data['land_area'] ?? null;
    $land->land_unit              = $data['land_unit'] ?? 'cent';
    $land->road_frontage          = $data['road_frontage'] ?? null;
    $land->plot_shape             = $data['plot_shape'] ?? null;

    $land->zoning_type            = $data['zoning_type'] ?? null;
    $land->ownership_type         = $data['ownership_type'] ?? null;
    $land->restrictions           = $data['restrictions'] ?? null;

    $land->expected_price_per_cent= $data['expected_price_per_cent'] ?? null;
    $land->negotiability          = $data['negotiability'] ?? null;
    $land->expected_advance_pct   = $data['expected_advance_pct'] ?? null;
    $land->sale_timeline          = $data['sale_timeline'] ?? null;

    $land->land_type              = $data['land_type'] ?? null;
    $land->current_use            = $data['current_use'] ?? null;

    // 👇 same boolean handling as storeLand
    $land->electricity            = $request->boolean('electricity');
    $land->water                  = $request->boolean('water');
    $land->drainage               = $request->boolean('drainage');

   // $land->compound_wall          = !empty($request->input('compound_wall'));

    $land->documents              = $documents ?: null;
    $land->photos                 = $photos ?: null;
    $land->videos                 = $videos ?: null;

    $land->save();

    return back()->with('success', 'Land listing updated successfully.');
}


    /* ---------- BUILDING UPDATE ---------- */
public function updateBuilding(Request $request, $id)
{
    // 🔹 Always load an existing building by ID
    $building = SellerBuildingListing::findOrFail($id);

    // (optional) ensure only owner can edit
    // abort_if($building->user_id !== auth()->id(), 403);

    $data = $request->validate([
        'district'          => 'required|string|max:120',
        'area'              => 'nullable|string|max:120',
        'street_name'       => 'nullable|string|max:255',
        'landmark'          => 'nullable|string|max:255',
        'map_link'          => 'nullable|string',
        'building_type'     => 'nullable|string|max:150',
        'total_plot_area'   => 'nullable|numeric',
        'builtup_area'      => 'nullable|numeric',
        'floors'            => 'nullable|integer',
        'construction_year' => 'nullable|integer',
        'lift_available'    => 'nullable',
        'parking_slots'     => 'nullable|integer',
        'road_frontage'     => 'nullable|integer',
        'expected_price'    => 'nullable|numeric',
        'negotiability'     => 'nullable|string|max:100',
        'sell_timeline'     => 'nullable|string|max:100',

        // files
        'building_permit'        => 'nullable|file',
        'completion_certificate' => 'nullable|file',
        'ownership_documents.*'  => 'nullable|file',
        'photos.*'               => 'nullable|image',
    ]);

    // ---------- UPDATE FIELDS ONLY (NO CREATE) ----------
    $building->district          = $data['district'];
    $building->area              = $data['area'] ?? null;
    $building->street_name       = $data['street_name'] ?? null;
    $building->landmark          = $data['landmark'] ?? null;
    $building->map_link          = $data['map_link'] ?? null;
    $building->building_type     = $data['building_type'] ?? null;
    $building->total_plot_area   = $data['total_plot_area'] ?? null;
    $building->builtup_area      = $data['builtup_area'] ?? null;
    $building->floors            = $data['floors'] ?? null;
    $building->construction_year = $data['construction_year'] ?? null;
    $building->lift_available    = $request->boolean('lift_available');
    $building->parking_slots     = $data['parking_slots'] ?? null;
    $building->road_frontage     = $data['road_frontage'] ?? null;
    $building->expected_price    = $data['expected_price'] ?? null;
    $building->negotiability     = $data['negotiability'] ?? null;
    $building->sell_timeline     = $data['sell_timeline'] ?? null;

    // ---------- DOCUMENTS JSON ----------
    $documents = is_array($building->documents) ? $building->documents : [];

    if ($request->hasFile('building_permit')) {
        $documents['building_permit'] = $request->file('building_permit')
            ->store('building/documents', 'public');
    }

    if ($request->hasFile('completion_certificate')) {
        $documents['completion_certificate'] = $request->file('completion_certificate')
            ->store('building/documents', 'public');
    }

    if ($request->hasFile('ownership_documents')) {
        $ownershipDocs = Arr::get($documents, 'ownership_documents', []);
        foreach ($request->file('ownership_documents') as $doc) {
            $ownershipDocs[] = $doc->store('building/documents', 'public');
        }
        $documents['ownership_documents'] = $ownershipDocs;
    }

    // ---------- PHOTOS JSON ----------
    $photos = is_array($building->photos) ? $building->photos : [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $photos[] = $photo->store('building/photos', 'public');
        }
    }

    $building->documents = $documents ?: null;
    $building->photos    = $photos ?: null;

    // ✅ This will now always be an UPDATE, never an INSERT
    $building->save();

    return back()->with('success', 'Building listing updated successfully.');
}

    /* ---------- INVESTMENT UPDATE ---------- */
   public function updateInvestment(Request $request, $id)
{
    $investment = SellerInvestmentListing::findOrFail($id);

    // (optional) ensure only owner can edit
    // abort_if($investment->user_id !== auth()->id(), 403);

    $data = $request->validate([
        'project_name'        => 'required|string|max:255',
        'project_type'        => 'nullable|string|max:150',
     //   'project_description' => 'nullable|string',

        'district'            => 'nullable|string|max:120',
        'micro_location'      => 'nullable|string|max:255',

        'project_cost'        => 'nullable|numeric',
        'investment_required' => 'nullable|numeric',
        'profit_sharing_model'=> 'nullable|string|max:255',
        'payback_period'      => 'nullable|string|max:100',

        'project_status'      => 'nullable|string|max:255',
        'completion_percent'  => 'nullable|integer|min:0|max:100',

        // documents
        'project_report'      => 'nullable|file',
        'approvals.*'         => 'nullable|file',
    ]);

    // 🔹 SIMPLE FIELD UPDATES
    $investment->project_name        = $data['project_name'];
    $investment->project_type        = $data['project_type'] ?? null;
  //  $investment->project_description = $data['project_description'] ?? null;

    $investment->district       = $data['district'] ?? null;
    $investment->micro_location = $data['micro_location'] ?? null;

    $investment->project_cost        = $data['project_cost'] ?? null;
    $investment->investment_required = $data['investment_required'] ?? null;
    $investment->profit_sharing_model= $data['profit_sharing_model'] ?? null;
    $investment->payback_period      = $data['payback_period'] ?? null;

    $investment->project_status     = $data['project_status'] ?? null;
    $investment->completion_percent = $data['completion_percent'] ?? null;

    // 🔹 MERGE DOCUMENTS
    $documents = is_array($investment->documents) ? $investment->documents : [];

    if ($request->hasFile('project_report')) {
        $documents['project_report'] = $request->file('project_report')
            ->store('investment/documents', 'public');
    }

    if ($request->hasFile('approvals')) {
        $approvalDocs = Arr::get($documents, 'approvals', []);
        foreach ($request->file('approvals') as $doc) {
            $approvalDocs[] = $doc->store('investment/documents', 'public');
        }
        $documents['approvals'] = $approvalDocs;
    }

    $investment->documents = $documents ?: null;

    // ✅ UPDATE, not INSERT
    $investment->save();

    return back()->with('success', 'Investment listing updated successfully.');
}


}
