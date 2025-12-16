@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>All Properties for {{ $seller->name }}</h1>
    <p>Quick navigation to this seller’s land, building, and investment listings.</p>
  </div>

  <div class="module-content">
    <div class="buyer-req-summary">
      <div class="buyer-req-header">
        <h2>Seller Listing Groups</h2>
        <p>Select what you want to view in detail.</p>
      </div>

      {{-- TABS --}}
      <div class="buyer-tabs" style="gap: 12px; flex-wrap: wrap;">
        <a href="{{ route('admin.seller.properties.index', ['seller' => $seller->id, 'tab' => 'land']) }}"
           class="buyer-tab {{ ($tab ?? 'land') === 'land' ? 'buyer-tab-active' : '' }}"
           style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
          All Seller Land Listings
        </a>

        <a href="{{ route('admin.seller.properties.index', ['seller' => $seller->id, 'tab' => 'building']) }}"
           class="buyer-tab {{ ($tab ?? 'land') === 'building' ? 'buyer-tab-active' : '' }}"
           style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
          All Seller Building Listings
        </a>

        <a href="{{ route('admin.seller.properties.index', ['seller' => $seller->id, 'tab' => 'investment']) }}"
           class="buyer-tab {{ ($tab ?? 'land') === 'investment' ? 'buyer-tab-active' : '' }}"
           style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
          All Seller Investment Listings
        </a>
      </div>

      {{-- ACTIVE TAB CONTENT --}}
      <div style="margin-top: 24px;">

        {{-- LAND TABLE --}}
        @if(($tab ?? 'land') === 'land')
          <h3 style="margin-bottom: 12px;">All Seller Land Listings</h3>

          @if($landListings->isEmpty())
            <p style="font-size:14px; color:#6b7280;">No land listings found for this seller.</p>
          @else
            <div class="table-responsive">
              <table id="land-table" class="buyer-table responsive-table display">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>District</th>
                    <th>Land Area</th>
                    <th>Unit</th>
                    <th>Price / Cent</th>
                    <th>Status</th>
                    <th>Timeline</th>
                    {{-- NEW: seller info --}}
                    <th>Seller Name</th>
                    <th>Seller Phone</th>
                    <th>Seller Location</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($landListings as $land)
                    <tr>
                      <td data-label="Code">{{ $land->property_code }}</td>
                      <td data-label="District">{{ $land->district ?? '—' }}</td>
                      <td data-label="Land Area">
                        {{ $land->land_area ? rtrim(rtrim(number_format($land->land_area, 2), '0'), '.') : '—' }}
                      </td>
                      <td data-label="Unit">{{ $land->land_unit ?? '—' }}</td>
                      <td data-label="Price / Cent">
                        {{ $land->expected_price_per_cent ? '₹' . number_format($land->expected_price_per_cent) : '—' }}
                      </td>
                      <td data-label="Status">{{ ucfirst($land->status ?? 'normal') }}</td>
                      <td data-label="Timeline">{{ $land->sale_timeline ?? '—' }}</td>

                      {{-- NEW: seller info (same for all rows on this page) --}}
                      <td data-label="Seller Name">{{ $seller->name }}</td>
                      <td data-label="Seller Phone">{{ $seller->phone ?? '—' }}</td>
                      <td data-label="Seller Location">{{ $seller->location ?? '—' }}</td>

                      <td data-label="Actions">
  <div class="table-actions" style="display:flex; gap:6px; flex-wrap:wrap;">
    <a href="{{ route('admin.seller.land.show', $land) }}" class="btn-table btn-table-view">
      View
    </a>

    <a href="{{ route('admin.seller.land.edit', $land) }}" class="btn-table btn-table-edit">
      Edit
    </a>

    <form method="POST"
          action="{{ route('admin.seller.land.destroy', $land) }}"
          onsubmit="return confirm('Are you sure you want to delete this land listing?');">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-table btn-table-danger">
        Delete
      </button>
    </form>
  </div>
</td>

                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        @endif

        {{-- BUILDING TABLE --}}
        @if(($tab ?? 'land') === 'building')
          <h3 style="margin-bottom: 12px;">All Seller Building Listings</h3>

          @if($buildingListings->isEmpty())
            <p style="font-size:14px; color:#6b7280;">No building listings found for this seller.</p>
          @else
            <div class="table-responsive">
              <table id="building-table" class="buyer-table responsive-table display">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>District</th>
                    <th>Area</th>
                    <th>Built-up (sqft)</th>
                    <th>Expected Price</th>
                    <th>Status</th>
                    {{-- NEW: seller info --}}
                    <th>Seller Name</th>
                    <th>Seller Phone</th>
                    <th>Seller Location</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($buildingListings as $bld)
                    <tr>
                      <td data-label="Code">{{ $bld->property_code }}</td>
                      <td data-label="Type">{{ $bld->building_type ?? '—' }}</td>
                      <td data-label="District">{{ $bld->district ?? '—' }}</td>
                      <td data-label="Area">{{ $bld->area ?? '—' }}</td>
                      <td data-label="Built-up (sqft)">
                        {{ $bld->builtup_area ? number_format($bld->builtup_area) : '—' }}
                      </td>
                      <td data-label="Expected Price">
                        {{ $bld->expected_price ? '₹' . number_format($bld->expected_price) : '—' }}
                      </td>
                      <td data-label="Status">{{ ucfirst($bld->status ?? 'normal') }}</td>

                      {{-- NEW: seller info --}}
                      <td data-label="Seller Name">{{ $seller->name }}</td>
                      <td data-label="Seller Phone">{{ $seller->phone ?? '—' }}</td>
                      <td data-label="Seller Location">{{ $seller->location ?? '—' }}</td>

                     <td data-label="Actions">
  <div class="table-actions" style="display:flex; gap:6px; flex-wrap:wrap;">
    <a href="{{ route('admin.seller.building.show', $bld) }}" class="btn-table btn-table-view">
      View
    </a>

    <a href="{{ route('admin.seller.building.edit', $bld) }}" class="btn-table btn-table-edit">
      Edit
    </a>

    <form method="POST"
          action="{{ route('admin.seller.building.destroy', $bld) }}"
          onsubmit="return confirm('Are you sure you want to delete this building listing?');">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-table btn-table-danger">
        Delete
      </button>
    </form>
  </div>
</td>

                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        @endif

        {{-- INVESTMENT TABLE --}}
        @if(($tab ?? 'land') === 'investment')
          <h3 style="margin-bottom: 12px;">All Seller Investment Listings</h3>

          @if($investmentListings->isEmpty())
            <p style="font-size:14px; color:#6b7280;">No investment listings found for this seller.</p>
          @else
            <div class="table-responsive">
              <table id="investment-table" class="buyer-table responsive-table display">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Project Name</th>
                    <th>Type</th>
                    <th>District</th>
                    <th>Project Cost</th>
                   
                    <th>Status</th>
                    {{-- NEW: seller info --}}
                    <th>Seller Name</th>
                    <th>Seller Phone</th>
                    <th>Seller Location</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($investmentListings as $inv)
                    <tr>
                      <td data-label="Code">{{ $inv->property_code }}</td>
                      <td data-label="Project Name">{{ $inv->project_name ?? '—' }}</td>
                      <td data-label="Type">{{ $inv->project_type ?? '—' }}</td>
                      <td data-label="District">{{ $inv->district ?? '—' }}</td>
                      <td data-label="Project Cost">
                        {{ $inv->project_cost ? '₹' . number_format($inv->project_cost) : '—' }}
                      </td>
                     
                      <td data-label="Status">{{ ucfirst($inv->status ?? 'normal') }}</td>

                      {{-- NEW: seller info --}}
                      <td data-label="Seller Name">{{ $seller->name }}</td>
                      <td data-label="Seller Phone">{{ $seller->phone ?? '—' }}</td>
                      <td data-label="Seller Location">{{ $seller->location ?? '—' }}</td>

                     <td data-label="Actions">
  <div class="table-actions" style="display:flex; gap:6px; flex-wrap:wrap;">
    <a href="{{ route('admin.seller.investment.show', $inv) }}" class="btn-table btn-table-view">
      View
    </a>

    <a href="{{ route('admin.seller.investment.edit', $inv) }}" class="btn-table btn-table-edit">
      Edit
    </a>

    <form method="POST"
          action="{{ route('admin.seller.investment.destroy', $inv) }}"
          onsubmit="return confirm('Are you sure you want to delete this investment listing?');">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-table btn-table-danger">
        Delete
      </button>
    </form>
  </div>
</td>

                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        @endif

      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    {{-- jQuery + DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('.buyer-table').DataTable({
                pageLength: 10,
                lengthChange: false,
                ordering: true
            });
        });
    </script>
@endpush
