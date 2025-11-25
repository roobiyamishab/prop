<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerLandListing;
use App\Models\SellerBuildingListing;
use App\Models\SellerInvestmentListing;
use App\Models\PropertyCode;
use Illuminate\Support\Facades\Storage;

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

    $userId = auth()->id();

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

    // --- CREATE ROW ---
    SellerLandListing::create([
        'user_id'               => $userId,
        'property_code'         => $this->generatePropertyCode('LND'),
        'status'                => 'normal',

        'district'              => $data['district'],
        'taluk'                 => $data['taluk'] ?? null,
        'village'               => $data['village'] ?? null,
        'exact_location'        => $data['exact_location'] ?? null,
        'landmark'              => $data['landmark'] ?? null,
        'google_map_link'       => $data['google_map_link'] ?? null,

        'land_area'             => $data['land_area'] ?? null,
        'land_unit'             => $data['land_unit'] ?? 'cent',
        'road_frontage'         => $data['road_frontage'] ?? null,
        'plot_shape'            => $data['plot_shape'] ?? null,

        'zoning_type'           => $data['zoning_type'] ?? null,
        'ownership_type'        => $data['ownership_type'] ?? null,
        'restrictions'          => $data['restrictions'] ?? null,

        'expected_price_per_cent'=> $data['expected_price_per_cent'] ?? null,
        'negotiability'         => $data['negotiability'] ?? null,
        'expected_advance_pct'  => $data['expected_advance_pct'] ?? null,
        'sale_timeline'         => $data['sale_timeline'] ?? null,
 
        'land_type'             => $data['land_type'] ?? null,
        'current_use'           => $data['current_use'] ?? null,

        // 👇 these 3 are NEVER null now
        'electricity'           => $request->boolean('electricity'),
        'water'                 => $request->boolean('water'),
        'drainage'              => $request->boolean('drainage'),

        'compound_wall'         => !empty($request->input('compound_wall')),

        'photos'                => $photos ?: null,
        'videos'                => $videos ?: null,
        'documents'             => $documents ?: null,
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
            'lift_available'    => 'nullable|boolean',
            'parking_slots'     => 'nullable|integer',
            'road_frontage'     => 'nullable|integer',
            'expected_price'    => 'nullable|numeric',
            'negotiability'     => 'nullable|string|max:100',
            'sell_timeline'     => 'nullable|string|max:100',

            'building_permit'       => 'nullable|file',
            'completion_certificate'=> 'nullable|file',
            'ownership_documents.*' => 'nullable|file',
            'photos.*'              => 'nullable|image',
        ]);

        $userId = auth()->id();

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
            $docs = [];
            foreach ($request->file('ownership_documents') as $doc) {
                $docs[] = $doc->store('building/documents', 'public');
            }
            $documents['ownership_documents'] = $docs;
        }

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('building/photos', 'public');
            }
        }

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
            'lift_available'    => isset($data['lift_available']) ? (bool)$data['lift_available'] : false,
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
}
