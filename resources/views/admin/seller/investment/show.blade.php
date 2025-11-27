@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Investment Listing – {{ $investment->property_code }}</h1>
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
      <a href="{{ route('admin.seller.properties.index', ['seller' => $seller->id, 'tab' => 'investment']) }}"
         class="btn-table btn-table-view">
        ← Back to All Investment Listings
      </a>

      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        {{-- EDIT BUTTON (optional, only if route exists) --}}
        @if(Route::has('admin.seller.investment.edit'))
          <a href="{{ route('admin.seller.investment.edit', $investment) }}"
             class="btn-table btn-table-view">
            ✏️ Edit Listing
          </a>
        @endif

        {{-- DELETE BUTTON (optional, only if route exists) --}}
        @if(Route::has('admin.seller.investment.destroy'))
          <form method="POST"
                action="{{ route('admin.seller.investment.destroy', $investment) }}"
                onsubmit="return confirm('Are you sure you want to delete this investment listing?');">
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
        <span class="detail-value">{{ $investment->property_code }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value">
          <span class="buyer-chip
            {{ $investment->status === 'urgent' ? 'buyer-chip-danger' : '' }}
            {{ $investment->status === 'hot' ? 'buyer-chip-primary' : '' }}
            {{ in_array($investment->status, ['sold','booked','off_market']) ? 'buyer-chip-soft' : '' }}">
            {{ ucfirst($investment->status) }}
          </span>
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Added By</span>
        <span class="detail-value">
          @if($investment->createdByAdmin)
            Admin: {{ $investment->createdByAdmin->name }}
          @else
            Seller: {{ $seller->name }}
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Created At</span>
        <span class="detail-value">{{ $investment->created_at?->format('d M Y, h:i A') ?? '—' }}</span>
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

    {{-- PROJECT DETAILS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Project Details</h3>

      <div class="detail-row">
        <span class="detail-label">Project Name</span>
        <span class="detail-value">{{ $investment->project_name ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Project Type</span>
        <span class="detail-value">{{ $investment->project_type ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Project Status</span>
        <span class="detail-value">{{ $investment->project_status ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Completion %</span>
        <span class="detail-value">
          {{ $investment->completion_percent !== null ? $investment->completion_percent . '%' : '—' }}
        </span>
      </div>
    </div>

    {{-- LOCATION DETAILS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Location Details</h3>

      <div class="detail-row">
        <span class="detail-label">District</span>
        <span class="detail-value">{{ $investment->district ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Micro Location</span>
        <span class="detail-value">{{ $investment->micro_location ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Landmark</span>
        <span class="detail-value">{{ $investment->landmark ?? '—' }}</span>
      </div>

      @if(!empty($investment->map_link))
        <div class="detail-row">
          <span class="detail-label">Map Link</span>
          <span class="detail-value">
            <a href="{{ $investment->map_link }}" target="_blank" rel="noopener">
              Open in Maps ↗
            </a>
          </span>
        </div>
      @endif
    </div>

    {{-- INVESTMENT & FINANCIALS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Investment & Financials</h3>

      <div class="detail-row">
        <span class="detail-label">Project Cost</span>
        <span class="detail-value">
          {{ $investment->project_cost ? '₹' . number_format($investment->project_cost) : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Investment Required</span>
        <span class="detail-value">
          {{ $investment->investment_required ? '₹' . number_format($investment->investment_required) : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Profit Sharing Model</span>
        <span class="detail-value">{{ $investment->profit_sharing_model ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Payback Period</span>
        <span class="detail-value">{{ $investment->payback_period ?? '—' }}</span>
      </div>
    </div>

    {{-- DOCUMENTS --}}
    <div class="detail-card" style="margin-bottom:20px;">
      <h3 style="margin-bottom:10px;">Documents</h3>

      <div class="detail-row" style="flex-direction:column; align-items:flex-start;">
        <span class="detail-label" style="margin-bottom:6px;">Project Documents</span>
        <div class="detail-value" style="width:100%;">
          @php
            $documents = is_array($investment->documents) ? $investment->documents : [];
          @endphp

          @if(count($documents))
            <ul style="padding-left:18px; margin:0;">
              @foreach($documents as $key => $docPath)
                <li style="margin-bottom:4px;">
                  <a href="{{ asset('storage/' . $docPath) }}" target="_blank" rel="noopener">
                    {{ ucwords(str_replace('_', ' ', is_string($key) ? $key : 'Document')) }} ↗
                  </a>
                </li>
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
