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

            // Location
            'district'             => 'required|string|max:120',
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

            'lift_available'       => 'nullable',        // checkbox / select (1/0)
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

        // Documents → JSON array
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                $documents[] = $doc->store('building/documents', 'public');
            }
        }

        // Photos → JSON array
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('building/photos', 'public');
            }
        }

        SellerBuildingListing::create([
            'user_id'              => $data['user_id'],
            'created_by_admin_id'  => $adminId,

            'property_code'        => $this->generatePropertyCode('BLD'),
            'status'               => 'normal',

            // Location
            'district'             => $data['district'],
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
}
