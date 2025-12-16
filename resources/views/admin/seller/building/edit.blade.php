@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Edit Building Listing – {{ $building->property_code }}</h1>
    <p>Update building details, pricing, documents, and listing status.</p>
  </div>

  <div class="module-content">

    <a href="{{ route('admin.seller.properties.index', ['seller' => $building->user_id, 'tab' => 'building']) }}"
       class="btn-table btn-table-view"
       style="margin-bottom: 16px; display:inline-flex;">
      ← Back to All Buildings
    </a>

    {{-- MAIN EDIT CARD --}}
    <div class="detail-card">
      <form class="module-form"
            method="POST"
            action="{{ route('admin.seller.building.update', $building) }}"
            enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- BASIC DETAILS --}}
        <h3 class="section-title">Basic Details</h3>

        <div class="form-row">
          <div class="form-group">
            <label>District</label>
            <input type="text" name="district"
                   value="{{ old('district', $building->district) }}"
                   placeholder="District">
          </div>
          <div class="form-group">
            <label>Area</label>
            <input type="text" name="area"
                   value="{{ old('area', $building->area) }}"
                   placeholder="Area / Locality">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Street Name</label>
            <input type="text" name="street_name"
                   value="{{ old('street_name', $building->street_name) }}"
                   placeholder="Street / Road name">
          </div>
          <div class="form-group">
            <label>Landmark</label>
            <input type="text" name="landmark"
                   value="{{ old('landmark', $building->landmark) }}"
                   placeholder="Nearby landmark">
          </div>
        </div>

        <div class="form-group">
          <label>Google Map Link</label>
          <input type="url" name="map_link"
                 value="{{ old('map_link', $building->map_link) }}"
                 placeholder="https://maps.google.com/...">
        </div>

        {{-- BUILDING SPECS --}}
        <h3 class="section-title" style="margin-top:24px;">Building Specifications</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Building Type</label>
            <input type="text" name="building_type"
                   value="{{ old('building_type', $building->building_type) }}"
                   placeholder="Commercial / Hospital / Hotel ...">
          </div>
          <div class="form-group">
            <label>Total Plot Area (sqft)</label>
            <input type="number" step="0.01" name="total_plot_area"
                   value="{{ old('total_plot_area', $building->total_plot_area) }}"
                   placeholder="e.g. 5000">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Built-up Area (sqft)</label>
            <input type="number" step="0.01" name="builtup_area"
                   value="{{ old('builtup_area', $building->builtup_area) }}"
                   placeholder="e.g. 12000">
          </div>
          <div class="form-group">
            <label>No. of Floors</label>
            <input type="number" name="floors"
                   value="{{ old('floors', $building->floors) }}"
                   placeholder="e.g. 5">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Construction Year</label>
            <input type="number" name="construction_year"
                   value="{{ old('construction_year', $building->construction_year) }}"
                   placeholder="e.g. 2015">
          </div>
          <div class="form-group">
            <label>Building Age</label>
            <input type="text" name="building_age"
                   value="{{ old('building_age', $building->building_age) }}"
                   placeholder="e.g. 8 years">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Condition</label>
            <input type="text" name="structure_condition"
                   value="{{ old('structure_condition', $building->structure_condition) }}"
                   placeholder="Good / Excellent / Needs renovation">
          </div>
          <div class="form-group">
            <label>Lift Available?</label>
            <select name="lift_available">
              <option value="0" {{ !$building->lift_available ? 'selected' : '' }}>No</option>
              <option value="1" {{ $building->lift_available ? 'selected' : '' }}>Yes</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Parking Slots</label>
            <input type="number" name="parking_slots"
                   value="{{ old('parking_slots', $building->parking_slots) }}"
                   placeholder="Number of cars">
          </div>
          <div class="form-group">
            <label>Road Frontage (ft)</label>
            <input type="number" name="road_frontage"
                   value="{{ old('road_frontage', $building->road_frontage) }}"
                   placeholder="Frontage width">
          </div>
        </div>

        {{-- PRICING --}}
        <h3 class="section-title" style="margin-top:24px;">Pricing Details</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Expected Price (₹)</label>
            <input type="number" step="0.01" name="expected_price"
                   value="{{ old('expected_price', $building->expected_price) }}"
                   placeholder="Total expected price">
          </div>
          <div class="form-group">
            <label>Price per Sqft (₹)</label>
            <input type="number" step="0.01" name="price_per_sqft"
                   value="{{ old('price_per_sqft', $building->price_per_sqft) }}"
                   placeholder="Rate per sqft">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Negotiability</label>
            <input type="text" name="negotiability"
                   value="{{ old('negotiability', $building->negotiability) }}"
                   placeholder="Negotiable / Slightly / Fixed">
          </div>
          <div class="form-group">
            <label>Expected Advance (%)</label>
            <input type="number" name="expected_advance_pct"
                   value="{{ old('expected_advance_pct', $building->expected_advance_pct) }}"
                   placeholder="e.g. 10">
          </div>
        </div>

        {{-- STATUS --}}
        <h3 class="section-title" style="margin-top:24px;">Listing Status</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Status</label>
            <select name="status">
              @foreach(['normal','hot','urgent','sold','booked','off_market'] as $st)
                <option value="{{ $st }}" {{ $building->status === $st ? 'selected' : '' }}>
                  {{ ucfirst($st) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- EXISTING DOCUMENTS --}}
        <h3 class="section-title" style="margin-top:24px;">Documents</h3>

        @if($building->documents && count($building->documents))
          <div class="form-row" style="flex-wrap: wrap; gap: 8px;">
            @foreach($building->documents as $doc)
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

        {{-- EXISTING PHOTOS --}}
        <h3 class="section-title" style="margin-top:24px;">Photos</h3>

        @if($building->photos && count($building->photos))
          <div class="photo-grid" style="display:flex; flex-wrap:wrap; gap:12px;">
            @foreach($building->photos as $photo)
              <div>
                <img src="{{ asset('storage/' . $photo) }}"
                     alt="Building photo"
                     style="width:150px; height:auto; border-radius:8px; object-fit:cover;">
              </div>
            @endforeach
          </div>
        @else
          <p style="font-size: 13px; color:#6b7280;">No photos uploaded yet.</p>
        @endif

        <div class="form-group" style="margin-top: 12px;">
          <label>Add More Photos</label>
          <input type="file" name="photos[]" multiple accept="image/*">
        </div>

        <div style="margin-top: 24px; display:flex; gap:12px; flex-wrap:wrap;">
          <button type="submit" class="btn-primary">
            Save Changes
          </button>

          {{-- DELETE BUTTON --}}
         
        </div>

      </form>
    </div>

  </div>
</div>
@endsection
