@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Building Listing – {{ $building->property_code }}</h1>
    <p>
      Seller: {{ $seller->name }}
      @if(!empty($seller->phone))
        · {{ $seller->phone }}
      @endif
      @if(!empty($seller->location))
        · {{ $seller->location }}
      @endif
    </p>
  </div>

  <div class="module-content">
    {{-- Top actions --}}
    <div style="display:flex; justify-content:space-between; margin-bottom:16px; gap:12px; flex-wrap:wrap;">
      <a href="{{ route('admin.seller.properties.index', ['seller' => $seller->id, 'tab' => 'building']) }}"
         class="btn-table btn-table-view">
        ← Back to All Building Listings
      </a>

      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        {{-- EDIT BUTTON (optional) --}}
        @if(Route::has('admin.seller.building.edit'))
          <a href="{{ route('admin.seller.building.edit', $building) }}"
             class="btn-table btn-table-view">
            ✏️ Edit Listing
          </a>
        @endif

        {{-- DELETE BUTTON (optional) --}}
        @if(Route::has('admin.seller.building.destroy'))
          <form method="POST"
                action="{{ route('admin.seller.building.destroy', $building) }}"
                onsubmit="return confirm('Are you sure you want to delete this building listing?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-table btn-table-danger">
              🗑 Delete
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- STATUS + META CARD --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <div class="detail-row">
        <span class="detail-label">Property Code</span>
        <span class="detail-value">{{ $building->property_code }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value">
          <span class="buyer-chip
            {{ $building->status === 'urgent' ? 'buyer-chip-danger' : '' }}
            {{ $building->status === 'hot' ? 'buyer-chip-primary' : '' }}
            {{ in_array($building->status, ['sold','booked','off_market']) ? 'buyer-chip-soft' : '' }}">
            {{ ucfirst($building->status) }}
          </span>
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Added By</span>
        <span class="detail-value">
          @if($building->createdByAdmin)
            Admin: {{ $building->createdByAdmin->name }}
          @else
            Seller: {{ $seller->name }}
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Created At</span>
        <span class="detail-value">{{ $building->created_at?->format('d M Y, h:i A') ?? '—' }}</span>
      </div>
    </div>

    {{-- SELLER DETAILS CARD --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Seller Details</h3>

      <div class="detail-row">
        <span class="detail-label">Name</span>
        <span class="detail-value">{{ $seller->name }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Phone</span>
        <span class="detail-value">{{ $seller->phone ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Location</span>
        <span class="detail-value">{{ $seller->location ?? '—' }}</span>
      </div>
    </div>

    {{-- LOCATION DETAILS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Location Details</h3>

      <div class="detail-row">
        <span class="detail-label">District</span>
        <span class="detail-value">{{ $building->district ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Area / Locality</span>
        <span class="detail-value">{{ $building->area ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Street Name</span>
        <span class="detail-value">{{ $building->street_name ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Landmark</span>
        <span class="detail-value">{{ $building->landmark ?? '—' }}</span>
      </div>

      @if(!empty($building->map_link))
        <div class="detail-row">
          <span class="detail-label">Map Link</span>
          <span class="detail-value">
            <a href="{{ $building->map_link }}" target="_blank" rel="noopener">
              Open in Maps ↗
            </a>
          </span>
        </div>
      @endif
    </div>

    {{-- BUILDING SPECIFICATIONS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Building Specifications</h3>

      <div class="detail-row">
        <span class="detail-label">Building Type</span>
        <span class="detail-value">{{ $building->building_type ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Total Plot Area</span>
        <span class="detail-value">
          {{ $building->total_plot_area ? rtrim(rtrim(number_format($building->total_plot_area, 2), '0'), '.') . ' sqft' : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Built-up Area</span>
        <span class="detail-value">
          {{ $building->builtup_area ? rtrim(rtrim(number_format($building->builtup_area, 2), '0'), '.') . ' sqft' : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">No. of Floors</span>
        <span class="detail-value">{{ $building->floors ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Construction Year</span>
        <span class="detail-value">{{ $building->construction_year ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Building Age</span>
        <span class="detail-value">{{ $building->building_age ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Structure Condition</span>
        <span class="detail-value">{{ $building->structure_condition ?? '—' }}</span>
      </div>
    </div>

    {{-- FEATURES & AMENITIES --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Features & Amenities</h3>

      <div class="detail-row">
        <span class="detail-label">Lift Available</span>
        <span class="detail-value">{{ $building->lift_available ? 'Yes' : 'No' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Parking Slots</span>
        <span class="detail-value">{{ $building->parking_slots ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Road Frontage (ft)</span>
        <span class="detail-value">{{ $building->road_frontage ?? '—' }}</span>
      </div>
    </div>

    {{-- PRICING & SALE DETAILS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Pricing & Sale Details</h3>

      <div class="detail-row">
        <span class="detail-label">Expected Price</span>
        <span class="detail-value">
          {{ $building->expected_price ? '₹' . number_format($building->expected_price) : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Price per Sqft</span>
        <span class="detail-value">
          {{ $building->price_per_sqft ? '₹' . rtrim(rtrim(number_format($building->price_per_sqft, 2), '0'), '.') : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Negotiability</span>
        <span class="detail-value">{{ $building->negotiability ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Expected Advance (%)</span>
        <span class="detail-value">
          {{ $building->expected_advance_pct !== null ? $building->expected_advance_pct . '%' : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Sell Timeline</span>
        <span class="detail-value">{{ $building->sell_timeline ?? '—' }}</span>
      </div>
    </div>

    {{-- MEDIA: PHOTOS & DOCUMENTS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Media & Documents</h3>

      {{-- Photos --}}
      <div class="detail-row" style="flex-direction:column; align-items:flex-start;">
        <span class="detail-label" style="margin-bottom:6px;">Photos</span>
        <div class="detail-value" style="width:100%;">
          @php
            $photos = $building->photos;
            if (is_string($photos)) {
                $photos = json_decode($photos, true) ?: [];
            }
            if (!is_array($photos)) {
                $photos = [];
            }
          @endphp

          @if(count($photos))
            <div style="display:flex; flex-wrap:wrap; gap:10px;">
              @foreach($photos as $photo)
                @php
                  $photoPath = is_array($photo) ? ($photo['path'] ?? reset($photo)) : $photo;
                @endphp
                @if($photoPath)
                  <div style="width:140px; height:100px; overflow:hidden; border-radius:8px; border:1px solid #e5e7eb;">
                    <img src="{{ asset('storage/' . $photoPath) }}"
                         alt="Building photo"
                         style="width:100%; height:100%; object-fit:cover;">
                  </div>
                @endif
              @endforeach
            </div>
          @else
            <p style="font-size:14px; color:#6b7280;">No photos uploaded.</p>
          @endif
        </div>
      </div>

      {{-- Documents --}}
      <div class="detail-row" style="flex-direction:column; align-items:flex-start;">
        <span class="detail-label" style="margin-bottom:6px;">Documents</span>
        <div class="detail-value" style="width:100%;">
          @php
            $documents = $building->documents;
            if (is_string($documents)) {
                $documents = json_decode($documents, true) ?: [];
            }
            if (!is_array($documents)) {
                $documents = [];
            }
          @endphp

          @if(count($documents))
            <ul style="padding-left:18px; margin:0;">
              @foreach($documents as $key => $docItem)
                @php
                  $path  = null;
                  $label = null;

                  if (is_array($docItem)) {
                      $path  = $docItem['path']  ?? (is_string(reset($docItem)) ? reset($docItem) : null);
                      $label = $docItem['label'] ?? null;
                  } else {
                      $path = $docItem;
                  }

                  if (!$label) {
                      if (is_string($key)) {
                          $label = ucwords(str_replace('_', ' ', $key));
                      } else {
                          $label = 'Document ' . ($loop->iteration);
                      }
                  }
                @endphp

                @if($path)
                  <li style="margin-bottom:4px;">
                    <a href="{{ asset('storage/' . $path) }}" target="_blank" rel="noopener">
                      {{ $label }} ↗
                    </a>
                  </li>
                @endif
              @endforeach
            </ul>
          @else
            <p style="font-size:14px; color:#6b7280;">No documents uploaded.</p>
          @endif
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
