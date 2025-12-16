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

        dd($request);


        $data = $request->validate([
            'user_id'               => 'required|exists:users,id',

            // ✅ NEW: Location IDs
            'country_id'            => 'nullable|integer',
            'state_id'              => 'nullable|integer',
            'district_id'           => 'nullable|integer',

            // Location (string)
            'district'              => 'required|string|max:120',
            'taluk'                 => 'nullable|string|max:120',
            'village'               => 'nullable|string|max:120',
            'exact_location'        => 'nullable|string|max:255',
            'landmark'              => 'nullable|string|max:255',
            'google_map_link'       => 'nullable|string',

            // Land / property details
            'land_area'             => 'nullable|numeric',
            'land_unit'             => 'nullable|string',
            'road_frontage'         => 'nullable|integer',
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

            // ✅ NEW: Location IDs
            'country_id'          => $data['country_id'] ?? null,
            'state_id'            => $data['state_id'] ?? null,
            'district_id'         => $data['district_id'] ?? null,

            // Location (string)
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

        return sprintf('%s%03d', $prefix, $number);
    }

    public function showLand(SellerLandListing $land)
    {
        $seller = $land->user;

        return view('admin.seller.land.show', [
            'land'   => $land,
            'seller' => $seller,
        ]);
    }

    public function editLand(SellerLandListing $land)
    {
        $seller = $land->user;

        return view('admin.seller.land.edit', [
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

    public function updateLand(Request $request, SellerLandListing $land)
    {
        $data = $request->validate([
            // ✅ NEW: Location IDs
            'country_id'           => 'nullable|integer',
            'state_id'             => 'nullable|integer',
            'district_id'          => 'nullable|integer',

            // Location (string)
            'district'             => 'required|string|max:120',
            'taluk'                => 'nullable|string|max:120',
            'village'              => 'nullable|string|max:120',
            'exact_location'       => 'nullable|string|max:255',
            'landmark'             => 'nullable|string|max:255',
            'survey_no'            => 'nullable|string|max:120',
            'google_map_link'      => 'nullable|string',

            'land_area'            => 'nullable|numeric',
            'land_unit'            => 'nullable|string|in:cent,acre,sqft',
            'proximity'            => 'nullable|string|max:150',
            'road_frontage'        => 'nullable|integer',
            'plot_shape'           => 'nullable|string|max:100',

            'zoning_type'          => 'nullable|string|max:120',
            'ownership_type'       => 'nullable|string|max:100',
            'restrictions'         => 'nullable|string',

            'expected_price_per_cent'=> 'nullable|numeric',
            'negotiability'        => 'nullable|string|max:50',
            'expected_advance_pct' => 'nullable|integer|min:0|max:100',
            'min_offer_expected'   => 'nullable|numeric',
            'market_value_info'    => 'nullable|string',

            'land_type'            => 'nullable|string|max:100',
            'current_use'          => 'nullable|string|max:100',

            'electricity'          => 'nullable',
            'water'                => 'nullable',
            'drainage'             => 'nullable',
            'compound_wall'        => 'nullable',

            'sale_timeline'        => 'nullable|string|max:100',

            // uploads
            'documents.*'          => 'nullable|file',
            'photos.*'             => 'nullable|image',
            'videos.*'             => 'nullable|file',

            'status'               => 'required|in:normal,hot,urgent,sold,booked,off_market',
        ]);

        // ✅ NEW: IDs
        $land->country_id  = $data['country_id'] ?? null;
        $land->state_id    = $data['state_id'] ?? null;
        $land->district_id = $data['district_id'] ?? null;

        // --- NORMAL FIELDS ---
        $land->district        = $data['district'];
        $land->taluk           = $data['taluk']           ?? null;
        $land->village         = $data['village']         ?? null;
        $land->exact_location  = $data['exact_location']  ?? null;
        $land->landmark        = $data['landmark']        ?? null;
        $land->survey_no       = $data['survey_no']       ?? null;
        $land->google_map_link = $data['google_map_link'] ?? null;

        $land->land_area       = $data['land_area']       ?? null;
        $land->land_unit       = $data['land_unit']       ?? $land->land_unit;
        $land->proximity       = $data['proximity']       ?? null;
        $land->road_frontage   = $data['road_frontage']   ?? null;
        $land->plot_shape      = $data['plot_shape']      ?? null;

        $land->zoning_type         = $data['zoning_type']         ?? null;
        $land->ownership_type      = $data['ownership_type']      ?? null;
        $land->restrictions        = $data['restrictions']        ?? null;
        $land->expected_price_per_cent = $data['expected_price_per_cent'] ?? null;
        $land->negotiability       = $data['negotiability']       ?? null;
        $land->expected_advance_pct= $data['expected_advance_pct']?? null;
        $land->min_offer_expected  = $data['min_offer_expected']  ?? null;
        $land->market_value_info   = $data['market_value_info']   ?? null;

        $land->land_type      = $data['land_type']   ?? null;
        $land->current_use    = $data['current_use'] ?? null;

        $land->electricity    = $request->boolean('electricity');
        $land->water          = $request->boolean('water');
        $land->drainage       = $request->boolean('drainage');
        $land->compound_wall  = $request->boolean('compound_wall');

        $land->sale_timeline  = $data['sale_timeline'] ?? null;
        $land->status         = $data['status'];

        // --- HANDLE EXISTING JSON ARRAYS ---
        $documents = $land->documents ?? [];
        if (is_string($documents)) $documents = json_decode($documents, true) ?: [];
        if (!is_array($documents)) $documents = [];

        $photos = $land->photos ?? [];
        if (is_string($photos)) $photos = json_decode($photos, true) ?: [];
        if (!is_array($photos)) $photos = [];

        $videos = $land->videos ?? [];
        if (is_string($videos)) $videos = json_decode($videos, true) ?: [];
        if (!is_array($videos)) $videos = [];

        // --- UPLOAD NEW FILES ---
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                if ($doc) $documents[] = $doc->store('land/documents', 'public');
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo) $photos[] = $photo->store('land/photos', 'public');
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                if ($video) $videos[] = $video->store('land/videos', 'public');
            }
        }

        $land->documents = $documents ?: null;
        $land->photos    = $photos ?: null;
        $land->videos    = $videos ?: null;

        $land->save();

        return redirect()
            ->route('admin.seller.land.show', $land)
            ->with('success', 'Land listing updated successfully.');
    }
}
