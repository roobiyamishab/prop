<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerBuildingListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerBuildingListingController extends Controller
{
    /**
     * Store a new seller building listing created by super admin.
     */
    public function storeBuilding(Request $request)
    {
        $data = $request->validate([
            'user_id'              => 'required|exists:users,id',

            // ✅ NEW: Location IDs
            'country_id'           => 'nullable|integer',
            'state_id'             => 'nullable|integer',
            'district_id'          => 'nullable|integer',

            // Location
            'district'             => 'required|string|max:120', // keep as text for backward compatibility
            'area'                 => 'nullable|string|max:120',
            'street_name'          => 'nullable|string|max:255',
            'landmark'             => 'nullable|string|max:255',
            'map_link'             => 'nullable|string',

            // Type
            'building_type'        => 'nullable|string|max:150',

            // Specs
            'total_plot_area'      => 'nullable|numeric',
            'builtup_area'         => 'nullable|numeric',
            'floors'               => 'nullable|integer',
            'construction_year'    => 'nullable|integer',
            'building_age'         => 'nullable|string|max:100',
            'structure_condition'  => 'nullable|string|max:150',

            'lift_available'       => 'nullable',
            'parking_slots'        => 'nullable|integer',
            'road_frontage'        => 'nullable|integer',

            // Pricing
            'expected_price'       => 'nullable|numeric',
            'price_per_sqft'       => 'nullable|numeric',
            'negotiability'        => 'nullable|string|max:100',
            'expected_advance_pct' => 'nullable|integer|min:0|max:100',

            // Timeline
            'sell_timeline'        => 'nullable|string|max:100',

            // Files
            'documents.*'          => 'nullable|file',
            'photos.*'             => 'nullable|image',
        ]);

        $adminId = Auth::guard('admin')->id();
        $user    = User::find($data['user_id']);

        // ---- FILE UPLOADS ----
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                if ($doc) {
                    $documents[] = $doc->store('building/documents', 'public');
                }
            }
        }

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo) {
                    $photos[] = $photo->store('building/photos', 'public');
                }
            }
        }

        SellerBuildingListing::create([
            'user_id'              => $data['user_id'],
            'created_by_admin_id'  => $adminId,

            'property_code'        => $this->generatePropertyCode('BLD'),
            'status'               => 'normal',

            // ✅ NEW: Location IDs
            'country_id'           => $data['country_id'] ?? null,
            'state_id'             => $data['state_id'] ?? null,
            'district_id'          => $data['district_id'] ?? null,

            // Location
            'district'             => $data['district'], // keep string
            'area'                 => $data['area'] ?? null,
            'street_name'          => $data['street_name'] ?? null,
            'landmark'             => $data['landmark'] ?? null,
            'map_link'             => $data['map_link'] ?? null,

            // Type
            'building_type'        => $data['building_type'] ?? null,

            // Specs
            'total_plot_area'      => $data['total_plot_area'] ?? null,
            'builtup_area'         => $data['builtup_area'] ?? null,
            'floors'               => $data['floors'] ?? null,
            'construction_year'    => $data['construction_year'] ?? null,
            'building_age'         => $data['building_age'] ?? null,
            'structure_condition'  => $data['structure_condition'] ?? null,

            'lift_available'       => $request->boolean('lift_available'),
            'parking_slots'        => $data['parking_slots'] ?? null,
            'road_frontage'        => $data['road_frontage'] ?? null,

            // Pricing
            'expected_price'       => $data['expected_price'] ?? null,
            'price_per_sqft'       => $data['price_per_sqft'] ?? null,
            'negotiability'        => $data['negotiability'] ?? null,
            'expected_advance_pct' => $data['expected_advance_pct'] ?? null,

            // Timeline
            'sell_timeline'        => $data['sell_timeline'] ?? null,

            // Media & docs
            'documents'            => $documents ?: null,
            'photos'               => $photos ?: null,
        ]);

        return back()->with(
            'success',
            'Seller building listing saved successfully for user: ' . ($user?->name ?? $data['user_id'])
        );
    }

    /**
     * Simple property code generator: BLD001, BLD002, etc.
     */
    protected function generatePropertyCode(string $prefix = 'BLD'): string
    {
        $last = SellerBuildingListing::orderByDesc('id')->first();

        if ($last && $last->property_code) {
            $number = (int) preg_replace('/\D/', '', $last->property_code);
            $number++;
        } else {
            $number = 1;
        }

        return sprintf('%s%03d', $prefix, $number);
    }

    public function showBuilding(SellerBuildingListing $building)
    {
        $seller = $building->user;

        return view('admin.seller.building.show', [
            'building' => $building,
            'seller'   => $seller,
        ]);
    }

    public function destroyBuilding(SellerBuildingListing $building)
    {
        $sellerId = $building->user_id;
        $building->delete();

        return redirect()
            ->route('admin.seller.properties.index', ['seller' => $sellerId, 'tab' => 'building'])
            ->with('success', 'Building listing deleted successfully.');
    }

    public function editBuilding(SellerBuildingListing $building)
    {
        $seller = $building->user;

        return view('admin.seller.building.edit', [
            'building' => $building,
            'seller'   => $seller,
        ]);
    }

    public function updateBuilding(Request $request, SellerBuildingListing $building)
    {
        $data = $request->validate([
            // ✅ NEW: Location IDs
            'country_id'            => 'nullable|integer',
            'state_id'              => 'nullable|integer',
            'district_id'           => 'nullable|integer',

            // Location
            'district'              => 'nullable|string|max:120',
            'area'                  => 'nullable|string|max:120',
            'street_name'           => 'nullable|string|max:255',
            'landmark'              => 'nullable|string|max:255',
            'map_link'              => 'nullable|string',

            'building_type'         => 'nullable|string|max:150',
            'total_plot_area'       => 'nullable|numeric',
            'builtup_area'          => 'nullable|numeric',
            'floors'                => 'nullable|integer',
            'construction_year'     => 'nullable|integer',
            'building_age'          => 'nullable|string|max:100',
            'structure_condition'   => 'nullable|string|max:150',

            'lift_available'        => 'nullable',
            'parking_slots'         => 'nullable|integer',
            'road_frontage'         => 'nullable|integer',

            'expected_price'        => 'nullable|numeric',
            'price_per_sqft'        => 'nullable|numeric',
            'negotiability'         => 'nullable|string|max:100',
            'expected_advance_pct'  => 'nullable|integer',

            'status'                => 'required|in:normal,hot,urgent,sold,booked,off_market',

            // files
            'documents.*'           => 'nullable|file',
            'photos.*'              => 'nullable|image',
        ]);

        // ---- HANDLE EXISTING JSON ARRAYS ----
        $existingDocs = $building->documents ?? [];
        if (is_string($existingDocs)) $existingDocs = json_decode($existingDocs, true) ?: [];
        if (!is_array($existingDocs)) $existingDocs = [];

        $existingPhotos = $building->photos ?? [];
        if (is_string($existingPhotos)) $existingPhotos = json_decode($existingPhotos, true) ?: [];
        if (!is_array($existingPhotos)) $existingPhotos = [];

        // ---- UPLOAD NEW DOCUMENTS ----
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                if ($doc) $existingDocs[] = $doc->store('building/documents', 'public');
            }
        }

        // ---- UPLOAD NEW PHOTOS ----
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo) $existingPhotos[] = $photo->store('building/photos', 'public');
            }
        }

        // ✅ NEW: Save IDs
        $building->country_id  = $data['country_id'] ?? $building->country_id;
        $building->state_id    = $data['state_id'] ?? $building->state_id;
        $building->district_id = $data['district_id'] ?? $building->district_id;

        // ---- UPDATE FIELDS ----
        $building->district            = $data['district'] ?? $building->district;
        $building->area                = $data['area'] ?? $building->area;
        $building->street_name         = $data['street_name'] ?? $building->street_name;
        $building->landmark            = $data['landmark'] ?? $building->landmark;
        $building->map_link            = $data['map_link'] ?? $building->map_link;

        $building->building_type       = $data['building_type'] ?? $building->building_type;
        $building->total_plot_area     = $data['total_plot_area'] ?? $building->total_plot_area;
        $building->builtup_area        = $data['builtup_area'] ?? $building->builtup_area;
        $building->floors              = $data['floors'] ?? $building->floors;
        $building->construction_year   = $data['construction_year'] ?? $building->construction_year;
        $building->building_age        = $data['building_age'] ?? $building->building_age;
        $building->structure_condition = $data['structure_condition'] ?? $building->structure_condition;

        $building->lift_available      = $request->boolean('lift_available');
        $building->parking_slots       = $data['parking_slots'] ?? $building->parking_slots;
        $building->road_frontage       = $data['road_frontage'] ?? $building->road_frontage;

        $building->expected_price      = $data['expected_price'] ?? $building->expected_price;
        $building->price_per_sqft      = $data['price_per_sqft'] ?? $building->price_per_sqft;
        $building->negotiability       = $data['negotiability'] ?? $building->negotiability;
        $building->expected_advance_pct= $data['expected_advance_pct'] ?? $building->expected_advance_pct;

        $building->status              = $data['status'];

        // Save JSON fields
        $building->documents = $existingDocs ?: null;
        $building->photos    = $existingPhotos ?: null;

        $building->save();

        return redirect()
            ->route('admin.seller.building.show', $building)
            ->with('success', 'Building listing updated successfully.');
    }
}
