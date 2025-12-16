@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Edit Land Listing – {{ $land->property_code }}</h1>
    <p>Update land details, pricing, documents, and listing status.</p>
  </div>

  <div class="module-content">

    <a href="{{ route('admin.seller.properties.index', ['seller' => $land->user_id, 'tab' => 'land']) }}"
       class="btn-table btn-table-view"
       style="margin-bottom: 16px; display:inline-flex;">
      ← Back to All Land Listings
    </a>

    {{-- extra class edit-land-card for padding/margin --}}
    <div class="detail-card edit-land-card">
      {{-- EDIT FORM --}}
      {{-- extra class edit-land-form for row spacing --}}
      <form class="module-form edit-land-form"
            method="POST"
            action="{{ route('admin.seller.land.update', $land) }}"
            enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <h3 class="section-title">Location Details</h3>

        <div class="form-row">
          <div class="form-group">
            <label>District</label>
            <input type="text" name="district"
                   value="{{ old('district', $land->district) }}"
                   placeholder="District">
          </div>
          <div class="form-group">
            <label>Taluk</label>
            <input type="text" name="taluk"
                   value="{{ old('taluk', $land->taluk) }}"
                   placeholder="Taluk">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Village</label>
            <input type="text" name="village"
                   value="{{ old('village', $land->village) }}"
                   placeholder="Village">
          </div>
          <div class="form-group">
            <label>Exact Location</label>
            <input type="text" name="exact_location"
                   value="{{ old('exact_location', $land->exact_location) }}"
                   placeholder="Specific location">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Landmark</label>
            <input type="text" name="landmark"
                   value="{{ old('landmark', $land->landmark) }}"
                   placeholder="Nearby landmark">
          </div>
          <div class="form-group">
            <label>Survey No.</label>
            <input type="text" name="survey_no"
                   value="{{ old('survey_no', $land->survey_no) }}"
                   placeholder="Survey number">
          </div>
        </div>

        <div class="form-group">
          <label>Google Map Link</label>
          <input type="url" name="google_map_link"
                 value="{{ old('google_map_link', $land->google_map_link) }}"
                 placeholder="https://maps.google.com/...">
        </div>

        <h3 class="section-title">Land Details</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Land Area</label>
            <input type="number" step="0.01" name="land_area"
                   value="{{ old('land_area', $land->land_area) }}"
                   placeholder="Area">
          </div>
          <div class="form-group">
            <label>Unit</label>
            <select name="land_unit">
              <option value="cent" {{ $land->land_unit === 'cent' ? 'selected' : '' }}>Cent</option>
              <option value="acre" {{ $land->land_unit === 'acre' ? 'selected' : '' }}>Acre</option>
              <option value="sqft" {{ $land->land_unit === 'sqft' ? 'selected' : '' }}>Sq.ft</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Proximity</label>
            <input type="text" name="proximity"
                   value="{{ old('proximity', $land->proximity) }}"
                   placeholder="NH / SH / Town / Others">
          </div>
          <div class="form-group">
            <label>Road Frontage (ft)</label>
            <input type="number" name="road_frontage"
                   value="{{ old('road_frontage', $land->road_frontage) }}"
                   placeholder="Frontage width">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Plot Shape</label>
            <select name="plot_shape">
              <option value="">Select</option>
              <option value="Rectangle" {{ $land->plot_shape === 'Rectangle' ? 'selected' : '' }}>Rectangle</option>
              <option value="Square" {{ $land->plot_shape === 'Square' ? 'selected' : '' }}>Square</option>
              <option value="Irregular" {{ $land->plot_shape === 'Irregular' ? 'selected' : '' }}>Irregular</option>
            </select>
          </div>
          <div class="form-group">
            <label>Land Type</label>
            <input type="text" name="land_type"
                   value="{{ old('land_type', $land->land_type) }}"
                   placeholder="Dry / Wet / Garden etc.">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Current Use</label>
            <input type="text" name="current_use"
                   value="{{ old('current_use', $land->current_use) }}"
                   placeholder="Vacant / Agricultural / Parking ...">
          </div>
          <div class="form-group">
            <label>Sale Timeline</label>
            <input type="text" name="sale_timeline"
                   value="{{ old('sale_timeline', $land->sale_timeline) }}"
                   placeholder="Immediate / 1–3 months / Flexible">
          </div>
        </div>

        <h3 class="section-title">Zoning & Legal</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Zoning Type</label>
            <input type="text" name="zoning_type"
                   value="{{ old('zoning_type', $land->zoning_type) }}"
                   placeholder="Residential / Commercial / Industrial ...">
          </div>
          <div class="form-group">
            <label>Ownership Type</label>
            <input type="text" name="ownership_type"
                   value="{{ old('ownership_type', $land->ownership_type) }}"
                   placeholder="Individual / Joint / Company / POA">
          </div>
        </div>

        <div class="form-group">
          <label>Restrictions</label>
          <textarea name="restrictions" rows="2"
                    placeholder="CRZ, wetland, hill tract etc.">{{ old('restrictions', $land->restrictions) }}</textarea>
        </div>

        <h3 class="section-title">Pricing</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Expected Price per Cent / Acre (₹)</label>
            <input type="number" step="0.01" name="expected_price_per_cent"
                   value="{{ old('expected_price_per_cent', $land->expected_price_per_cent) }}"
                   placeholder="Price">
          </div>
          <div class="form-group">
            <label>Negotiability</label>
            <input type="text" name="negotiability"
                   value="{{ old('negotiability', $land->negotiability) }}"
                   placeholder="Negotiable / Slightly / Fixed">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Expected Advance (%)</label>
            <input type="number" name="expected_advance_pct"
                   value="{{ old('expected_advance_pct', $land->expected_advance_pct) }}"
                   min="0" max="100" placeholder="Advance %">
          </div>
          <div class="form-group">
            <label>Minimum Offer Expected (₹)</label>
            <input type="number" step="0.01" name="min_offer_expected"
                   value="{{ old('min_offer_expected', $land->min_offer_expected) }}"
                   placeholder="Min acceptable">
          </div>
        </div>

        <div class="form-group">
          <label>Market Value Info</label>
          <textarea name="market_value_info" rows="2"
                    placeholder="Recent deals / guideline value etc.">{{ old('market_value_info', $land->market_value_info) }}</textarea>
        </div>

        <h3 class="section-title">Amenities</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Electricity</label>
            <select name="electricity">
              <option value="0" {{ !$land->electricity ? 'selected' : '' }}>Not Available</option>
              <option value="1" {{ $land->electricity ? 'selected' : '' }}>Available</option>
            </select>
          </div>
          <div class="form-group">
            <label>Water</label>
            <select name="water">
              <option value="0" {{ !$land->water ? 'selected' : '' }}>Not Available</option>
              <option value="1" {{ $land->water ? 'selected' : '' }}>Available</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Drainage</label>
            <select name="drainage">
              <option value="0" {{ !$land->drainage ? 'selected' : '' }}>Not Available</option>
              <option value="1" {{ $land->drainage ? 'selected' : '' }}>Available</option>
            </select>
          </div>
          <div class="form-group">
            <label>Compound Wall</label>
            <select name="compound_wall">
              <option value="0" {{ !$land->compound_wall ? 'selected' : '' }}>No</option>
              <option value="1" {{ $land->compound_wall ? 'selected' : '' }}>Yes</option>
            </select>
          </div>
        </div>

        <h3 class="section-title">Listing Status</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Status</label>
            <select name="status">
              @foreach(['normal','hot','urgent','sold','booked','off_market'] as $st)
                <option value="{{ $st }}" {{ $land->status === $st ? 'selected' : '' }}>
                  {{ ucfirst($st) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <h3 class="section-title">Documents & Media</h3>

        @if($land->documents && count($land->documents))
          <div class="form-row" style="flex-wrap: wrap; gap: 8px;">
            @foreach($land->documents as $doc)
              <a href="{{ asset('storage/' . $doc) }}" target="_blank"
                 class="btn-table btn-table-view">
                View Document
              </a>
            @endforeach
          </div>
        @else
          <p style="font-size: 13px; color:#6b7280;">No documents uploaded yet.</p>
        @endif

        <div class="form-group" style="margin-top: 12px;">
          <label>Add More Documents</label>
          <input type="file" name="documents[]" multiple>
        </div>

        @if($land->photos && count($land->photos))
          <div class="photo-grid" style="display:flex; flex-wrap:wrap; gap:12px; margin-top:12px;">
            @foreach($land->photos as $photo)
              <div>
                <img src="{{ asset('storage/' . $photo) }}"
                     style="width:150px; border-radius:8px; object-fit:cover;">
              </div>
            @endforeach
          </div>
        @else
          <p style="font-size: 13px; color:#6b7280; margin-top:12px;">No photos uploaded yet.</p>
        @endif

        <div class="form-group" style="margin-top: 12px;">
          <label>Add More Photos</label>
          <input type="file" name="photos[]" multiple accept="image/*">
        </div>

        <div style="margin-top: 24px;">
          <button type="submit" class="btn-primary">
            Save Changes
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
    /* extra breathing room inside card */
    .edit-land-card {
        padding: 24px 32px;
        margin-top: 16px;
    }

    /* nice horizontal + vertical gaps between fields */
    .edit-land-form .form-row {
        display: flex;
        gap: 20px;          /* space between left/right columns */
        margin-bottom: 16px;/* space between rows */
        flex-wrap: wrap;
    }

    .edit-land-form .form-group {
        flex: 1 1 0;
    }

    .edit-land-form .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .edit-land-form .form-group input,
    .edit-land-form .form-group select,
    .edit-land-form .form-group textarea {
        width: 100%;
    }

    .edit-land-form .section-title {
        font-size: 18px;
        font-weight: 600;
        margin: 24px 0 12px;
    }

    .edit-land-form .section-title:first-of-type {
        margin-top: 0; /* first title sticks to top padding */
    }
</style>
@endpush
