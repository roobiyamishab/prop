@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Land Listing – {{ $land->property_code }}</h1>
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
      <a href="{{ route('admin.seller.properties.index', ['seller' => $seller->id, 'tab' => 'land']) }}"
         class="btn-table btn-table-view">
        ← Back to All Land Listings
      </a>

      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        {{-- EDIT BUTTON (make sure the route exists) --}}
        {{-- If you already have admin.seller.land.update via PATCH in a separate form,
             you can instead link to an edit page if you create it. --}}
        @if(Route::has('admin.seller.land.edit'))
          <a href="{{ route('admin.seller.land.edit', $land) }}"
             class="btn-table btn-table-view">
            ✏️ Edit Listing
          </a>
        @endif

        {{-- DELETE BUTTON --}}
        @if(Route::has('admin.seller.land.destroy'))
          <form method="POST"
                action="{{ route('admin.seller.land.destroy', $land) }}"
                onsubmit="return confirm('Are you sure you want to delete this listing? This action cannot be undone.');">
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
        <span class="detail-value">{{ $land->property_code }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value">
          <span class="buyer-chip
            {{ $land->status === 'urgent' ? 'buyer-chip-danger' : '' }}
            {{ $land->status === 'hot' ? 'buyer-chip-primary' : '' }}
            {{ in_array($land->status, ['sold','booked','off_market']) ? 'buyer-chip-soft' : '' }}">
            {{ ucfirst($land->status) }}
          </span>
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Added By</span>
        <span class="detail-value">
          @if($land->createdByAdmin)
            Admin: {{ $land->createdByAdmin->name }}
          @else
            Seller: {{ $seller->name }}
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Created At</span>
        <span class="detail-value">{{ $land->created_at?->format('d M Y, h:i A') ?? '—' }}</span>
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
        <span class="detail-value">{{ $land->district ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Taluk</span>
        <span class="detail-value">{{ $land->taluk ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Village</span>
        <span class="detail-value">{{ $land->village ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Exact Location</span>
        <span class="detail-value">{{ $land->exact_location ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Landmark</span>
        <span class="detail-value">{{ $land->landmark ?? '—' }}</span>
      </div>

      @if(!empty($land->google_map_link))
        <div class="detail-row">
          <span class="detail-label">Google Map</span>
          <span class="detail-value">
            <a href="{{ $land->google_map_link }}" target="_blank" rel="noopener">
              Open in Google Maps ↗
            </a>
          </span>
        </div>
      @endif
    </div>

    {{-- LAND & ZONING DETAILS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Land & Zoning</h3>

      <div class="detail-row">
        <span class="detail-label">Land Area</span>
        <span class="detail-value">
          @if($land->land_area)
            {{ rtrim(rtrim(number_format($land->land_area, 2), '0'), '.') }} {{ $land->land_unit }}
          @else
            —
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Proximity</span>
        <span class="detail-value">{{ $land->proximity ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Road Frontage (ft)</span>
        <span class="detail-value">{{ $land->road_frontage ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Plot Shape</span>
        <span class="detail-value">{{ $land->plot_shape ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Zoning Type</span>
        <span class="detail-value">{{ $land->zoning_type ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Ownership Type</span>
        <span class="detail-value">{{ $land->ownership_type ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Restrictions</span>
        <span class="detail-value">{{ $land->restrictions ?? '—' }}</span>
      </div>
    </div>

    {{-- PRICING & SALE DETAILS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Pricing & Sale Details</h3>

      <div class="detail-row">
        <span class="detail-label">Expected Price / Cent</span>
        <span class="detail-value">
          {{ $land->expected_price_per_cent ? '₹' . number_format($land->expected_price_per_cent) : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Negotiability</span>
        <span class="detail-value">{{ $land->negotiability ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Expected Advance (%)</span>
        <span class="detail-value">
          {{ $land->expected_advance_pct !== null ? $land->expected_advance_pct . '%' : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Minimum Offer Expected</span>
        <span class="detail-value">
          {{ $land->min_offer_expected ? '₹' . number_format($land->min_offer_expected) : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Sale Timeline</span>
        <span class="detail-value">{{ $land->sale_timeline ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Market Value Info</span>
        <span class="detail-value">{{ $land->market_value_info ?? '—' }}</span>
      </div>
    </div>

    {{-- LAND CONDITION & AMENITIES --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Land Condition & Amenities</h3>

      <div class="detail-row">
        <span class="detail-label">Land Type</span>
        <span class="detail-value">{{ $land->land_type ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Current Use</span>
        <span class="detail-value">{{ $land->current_use ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Electricity</span>
        <span class="detail-value">{{ $land->electricity ? 'Available' : 'Not Available' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Water</span>
        <span class="detail-value">{{ $land->water ? 'Available' : 'Not Available' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Drainage</span>
        <span class="detail-value">{{ $land->drainage ? 'Available' : 'Not Available' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Compound Wall</span>
        <span class="detail-value">
          @if(is_bool($land->compound_wall))
            {{ $land->compound_wall ? 'Available' : 'Not Available' }}
          @else
            {{ $land->compound_wall ?? '—' }}
          @endif
        </span>
      </div>
    </div>

    {{-- MEDIA: PHOTOS / DOCUMENTS / VIDEOS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Media & Documents</h3>

      {{-- Photos --}}
      <div class="detail-row" style="flex-direction:column; align-items:flex-start;">
        <span class="detail-label" style="margin-bottom:6px;">Photos</span>
        <div class="detail-value" style="width:100%;">
          @php
            $photos = is_array($land->photos) ? $land->photos : [];
          @endphp

          @if(count($photos))
            <div style="display:flex; flex-wrap:wrap; gap:10px;">
              @foreach($photos as $photo)
                <div style="width:140px; height:100px; overflow:hidden; border-radius:8px; border:1px solid #e5e7eb;">
                  <img src="{{ asset('storage/' . $photo) }}"
                       alt="Land photo"
                       style="width:100%; height:100%; object-fit:cover;">
                </div>
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
            $documents = is_array($land->documents) ? $land->documents : [];
          @endphp

          @if(count($documents))
            <ul style="padding-left:18px; margin:0;">
              @foreach($documents as $key => $docPath)
                <li style="margin-bottom:4px;">
                  <a href="{{ asset('storage/' . $docPath) }}" target="_blank" rel="noopener">
                    {{ ucwords(str_replace('_', ' ', $key)) }} ↗
                  </a>
                </li>
              @endforeach
            </ul>
          @else
            <p style="font-size:14px; color:#6b7280;">No documents uploaded.</p>
          @endif
        </div>
      </div>

      {{-- Videos --}}
      <div class="detail-row" style="flex-direction:column; align-items:flex-start;">
        <span class="detail-label" style="margin-bottom:6px;">Videos</span>
        <div class="detail-value" style="width:100%;">
          @php
            $videos = is_array($land->videos) ? $land->videos : [];
          @endphp

          @if(count($videos))
            @foreach($videos as $video)
              <div style="margin-bottom:10px;">
                <video controls style="max-width:100%; border-radius:8px; border:1px solid #e5e7eb;">
                  <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
            @endforeach
          @else
            <p style="font-size:14px; color:#6b7280;">No videos uploaded.</p>
          @endif
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
