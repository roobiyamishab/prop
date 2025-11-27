<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerLandListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerLandListingController extends Controller
{
   

    /**
     * Store a new seller land listing created by super admin.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'               => 'required|exists:users,id',

            // Location
            'district'              => 'required|string|max:120',
            'taluk'                 => 'nullable|string|max:120',
            'village'               => 'nullable|string|max:120',
            'exact_location'        => 'nullable|string|max:255',
            'landmark'              => 'nullable|string|max:255',
            'google_map_link'       => 'nullable|string',

            // Land / property details
            'land_area'             => 'nullable|numeric',
            'land_unit'             => 'nullable|string',
            'road_frontage'         => 'nullable|integer', // we’ll map road_width → road_frontage
            'plot_shape'            => 'nullable|string|max:100',

            'zoning_type'           => 'nullable|string|max:120',
            'ownership_type'        => 'nullable|string|max:100',
            'restrictions'          => 'nullable|string',

            // Pricing
            'expected_price_per_cent'=> 'nullable|numeric',
            'negotiability'         => 'nullable|string|max:50',
            'expected_advance_pct'  => 'nullable|integer|min:0|max:100',
            'sale_timeline'         => 'nullable|string|max:100',

            // Characteristics
            'land_type'             => 'nullable|string|max:100',
            'current_use'           => 'nullable|string|max:100',

            // Amenities
            'road_width'            => 'nullable|integer',
            'electricity'           => 'nullable',
            'water'                 => 'nullable',
            'drainage'              => 'nullable',
            'compound_wall'         => 'nullable', // Complete / Partial / None (from select)

            // Files
            'land_tax_receipt'      => 'nullable|file',
            'location_sketch'       => 'nullable|file',
            'photos.*'              => 'nullable|image',
            'video'                 => 'nullable|file',
        ]);

        // Super admin who is creating this listing
        $adminId = Auth::guard('admin')->id();

        // For success message
        $user = User::find($data['user_id']);

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

        // Map UI "Compound Wall" (Complete / Partial / None) → boolean column
        $compoundWallInput = $request->input('compound_wall');
        $hasCompoundWall   = in_array($compoundWallInput, ['Complete', 'Partial'], true);

        // If you want to use road_width as the stored frontage:
        $roadFrontage = $data['road_frontage'] ?? $request->input('road_width');

        SellerLandListing::create([
            'user_id'             => $data['user_id'],
            'created_by_admin_id' => $adminId,

            'property_code'       => $this->generatePropertyCode('LND'),
            'status'              => 'normal',

            // Location
            'district'            => $data['district'],
            'taluk'               => $data['taluk'] ?? null,
            'village'             => $data['village'] ?? null,
            'exact_location'      => $data['exact_location'] ?? null,
            'landmark'            => $data['landmark'] ?? null,
            'google_map_link'     => $data['google_map_link'] ?? null,

            // Land
            'land_area'           => $data['land_area'] ?? null,
            'land_unit'           => $data['land_unit'] ?? 'cent',
            'road_frontage'       => $roadFrontage ?: null,
            'plot_shape'          => $data['plot_shape'] ?? null,

            // Zoning & legal
            'zoning_type'         => $data['zoning_type'] ?? null,
            'ownership_type'      => $data['ownership_type'] ?? null,
            'restrictions'        => $data['restrictions'] ?? null,

            // Pricing
            'expected_price_per_cent' => $data['expected_price_per_cent'] ?? null,
            'negotiability'           => $data['negotiability'] ?? null,
            'expected_advance_pct'    => $data['expected_advance_pct'] ?? null,
            'sale_timeline'           => $data['sale_timeline'] ?? null,

            // Land characteristics
            'land_type'           => $data['land_type'] ?? null,
            'current_use'         => $data['current_use'] ?? null,

            // Amenities
            'electricity'         => (bool) $request->input('electricity', 0),
            'water'               => (bool) $request->input('water', 0),
            'drainage'            => (bool) $request->input('drainage', 0),
            'compound_wall'       => $hasCompoundWall,

            // Media & docs
            'documents'           => $documents ?: null,
            'photos'              => $photos ?: null,
            'videos'              => $videos ?: null,
        ]);

        return back()->with(
            'success',
            'Seller land listing saved successfully for user: ' . ($user?->name ?? $data['user_id'])
        );
    }

    /**
     * Simple property code generator: LND001, LND002, etc.
     */
    protected function generatePropertyCode(string $prefix = 'LND'): string
    {
        $last = SellerLandListing::orderByDesc('id')->first();

        if ($last && $last->property_code) {
            $number = (int) preg_replace('/\D/', '', $last->property_code);
            $number++;
        } else {
            $number = 1;
        }

        // Example: LND001, LND002 ...
        return sprintf('%s%03d', $prefix, $number);
    }
public function showLand(SellerLandListing $land)
    {
        $seller = $land->user; // assuming relation user() on the model

        return view('admin.seller.land.show', [
            'land'   => $land,
            'seller' => $seller,
        ]);
    }
 public function destroyLand(SellerLandListing $land)
    {
        $sellerId = $land->user_id;
        $land->delete();

        return redirect()
            ->route('admin.seller.properties.index', ['seller' => $sellerId, 'tab' => 'land'])
            ->with('success', 'Land listing deleted successfully.');
    }


}
