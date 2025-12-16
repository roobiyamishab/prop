@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>All Buyer Property Preferences</h1>
    <p>View land, building, and investment requirements across all buyers.</p>
  </div>

  <div class="module-content">
    <div class="buyer-req-summary">
      <div class="buyer-req-header">
        <h2>Preference Groups</h2>
        <p>Select which type of buyer requirement you want to review.</p>
      </div>

      {{-- TABS --}}
      <div class="buyer-tabs" style="gap: 12px; flex-wrap: wrap;">
        <a href="{{ route('admin.buyers.properties.all', ['tab' => 'land']) }}"
           class="buyer-tab {{ ($tab ?? 'land') === 'land' ? 'buyer-tab-active' : '' }}"
           style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
          Buyer Land Preferences
        </a>

        <a href="{{ route('admin.buyers.properties.all', ['tab' => 'building']) }}"
           class="buyer-tab {{ ($tab ?? 'land') === 'building' ? 'buyer-tab-active' : '' }}"
           style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
          Buyer Building Preferences
        </a>

        <a href="{{ route('admin.buyers.properties.all', ['tab' => 'investment']) }}"
           class="buyer-tab {{ ($tab ?? 'land') === 'investment' ? 'buyer-tab-active' : '' }}"
           style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
          Buyer Investment Preferences
        </a>
      </div>

      {{-- ACTIVE TAB CONTENT --}}
      <div style="margin-top: 24px;">

        {{-- LAND TABLE --}}
        @if(($tab ?? 'land') === 'land')
          <h3 style="margin-bottom: 12px;">All Buyer Land Preferences</h3>

          @if($landPreferences->isEmpty())
            <p style="font-size:14px; color:#6b7280;">No land preferences found.</p>
          @else
            <div class="table-responsive">
              <table id="land-table" class="buyer-table responsive-table display">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Districts</th>
                    <th>Locations</th>
                    <th>Budget / cent</th>
                    <th>Zoning</th>
                    <th>Timeline</th>
                    <th>Mode</th>
                    <th>Advance %</th>
                    <th>Status</th>
                    <th>Buyer Name</th>
                    <th>Buyer Phone</th>
                    <th>Buyer Location</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($landPreferences as $land)
                    <tr>
                      <td data-label="ID">REQ-LAND-{{ $land->id }}</td>
                      <td data-label="Districts">
                        {{ is_array($land->preferred_districts) ? implode(', ', $land->preferred_districts) : ($land->preferred_districts ?? '—') }}
                      </td>
                      <td data-label="Locations">
                        {{ is_array($land->preferred_locations) ? implode(', ', $land->preferred_locations) : ($land->preferred_locations ?? '—') }}
                      </td>
                      <td data-label="Budget / cent">
                        @if($land->budget_per_cent_min || $land->budget_per_cent_max)
                          ₹{{ number_format($land->budget_per_cent_min ?? 0) }}
                          @if($land->budget_per_cent_max && $land->budget_per_cent_max != $land->budget_per_cent_min)
                            – ₹{{ number_format($land->budget_per_cent_max) }}
                          @endif
                        @else
                          —
                        @endif
                      </td>
                      <td data-label="Zoning">{{ $land->zoning_preference ?? '—' }}</td>
                      <td data-label="Timeline">{{ $land->timeline_to_purchase ?? '—' }}</td>
                      <td data-label="Mode">{{ $land->mode_of_purchase ?? '—' }}</td>
                      <td data-label="Advance %">{{ $land->advance_capacity ? $land->advance_capacity . '%' : '—' }}</td>
                      <td data-label="Status">{{ ucfirst($land->status ?? 'active') }}</td>

                      {{-- Buyer info from relation --}}
                      <td data-label="Buyer Name">{{ optional($land->user)->name ?? '—' }}</td>
                      <td data-label="Buyer Phone">{{ optional($land->user)->phone ?? '—' }}</td>
                      <td data-label="Buyer Location">{{ optional($land->user)->location ?? '—' }}</td>

                      <td data-label="Actions">
                        <div class="table-actions">
                          <a href="{{ route('admin.buyers.preferences.land.show', $land) }}"
                             class="btn-table btn-table-view">
                            View
                          </a>
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
          <h3 style="margin-bottom: 12px;">All Buyer Building Preferences</h3>

          @if($buildingPreferences->isEmpty())
            <p style="font-size:14px; color:#6b7280;">No building preferences found.</p>
          @else
            <div class="table-responsive">
              <table id="building-table" class="buyer-table responsive-table display">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Building Type</th>
                    <th>Districts</th>
                    <th>Micro-locations</th>
                    <th>Age Preference</th>
                    <th>Total Budget</th>
                    <th>Rent Expectation</th>
                    <th>Status</th>
                    <th>Buyer Name</th>
                    <th>Buyer Phone</th>
                    <th>Buyer Location</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($buildingPreferences as $bld)
                    <tr>
                      <td data-label="ID">REQ-BLD-{{ $bld->id }}</td>
                      <td data-label="Building Type">{{ $bld->building_type ?? '—' }}</td>
                      <td data-label="Districts">
                        {{ is_array($bld->preferred_districts) ? implode(', ', $bld->preferred_districts) : ($bld->preferred_districts ?? '—') }}
                      </td>
                      <td data-label="Micro-locations">
                        {{ is_array($bld->micro_locations) ? implode(', ', $bld->micro_locations) : ($bld->micro_locations ?? '—') }}
                      </td>
                      <td data-label="Age Preference">{{ $bld->age_preference ?? '—' }}</td>
                      <td data-label="Total Budget">
                        @if($bld->total_budget_min)
                          ₹{{ number_format($bld->total_budget_min) }}
                          @if($bld->total_budget_max && $bld->total_budget_max != $bld->total_budget_min)
                            – ₹{{ number_format($bld->total_budget_max) }}
                          @endif
                        @else
                          —
                        @endif
                      </td>
                      <td data-label="Rent Expectation">
                        {{ $bld->rent_expectation ? '₹' . number_format($bld->rent_expectation) . '/month' : '—' }}
                      </td>
                      <td data-label="Status">{{ ucfirst($bld->status ?? 'active') }}</td>

                      {{-- Buyer info from relation --}}
                      <td data-label="Buyer Name">{{ optional($bld->user)->name ?? '—' }}</td>
                      <td data-label="Buyer Phone">{{ optional($bld->user)->phone ?? '—' }}</td>
                      <td data-label="Buyer Location">{{ optional($bld->user)->location ?? '—' }}</td>

                      <td data-label="Actions">
                        <div class="table-actions">
                          <a href="{{ route('admin.buyers.preferences.building.show', $bld) }}"
                             class="btn-table btn-table-view">
                            View
                          </a>
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
          <h3 style="margin-bottom: 12px;">All Buyer Investment Preferences</h3>

          @if($investmentPreferences->isEmpty())
            <p style="font-size:14px; color:#6b7280;">No investment preferences found.</p>
          @else
            <div class="table-responsive">
              <table id="investment-table" class="buyer-table responsive-table display">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Property Type</th>
                    <th>Districts</th>
                    <th>Locations</th>
                    <th>Budget Range</th>
                    <th>Profit Expectation / Year</th>
                    <th>Status</th>
                    <th>Buyer Name</th>
                    <th>Buyer Phone</th>
                    <th>Buyer Location</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($investmentPreferences as $inv)
                    <tr>
                      <td data-label="ID">REQ-INV-{{ $inv->id }}</td>
                      <td data-label="Property Type">{{ $inv->investment_property_type ?? '—' }}</td>
                      <td data-label="Districts">
                        {{ is_array($inv->preferred_districts) ? implode(', ', $inv->preferred_districts) : ($inv->preferred_districts ?? '—') }}
                      </td>
                      <td data-label="Locations">
                        {{ is_array($inv->preferred_locations) ? implode(', ', $inv->preferred_locations) : ($inv->preferred_locations ?? '—') }}
                      </td>
                      <td data-label="Budget Range">
                        @if($inv->investment_budget_min)
                          ₹{{ number_format($inv->investment_budget_min) }}
                          @if($inv->investment_budget_max && $inv->investment_budget_max != $inv->investment_budget_min)
                            – ₹{{ number_format($inv->investment_budget_max) }}
                          @endif
                        @else
                          —
                        @endif
                      </td>
                      <td data-label="Profit Expectation / Year">
                        @if($inv->profit_expectation_year)
                          {{ rtrim(rtrim(number_format($inv->profit_expectation_year, 2), '0'), '.') }}%
                        @else
                          —
                        @endif
                      </td>
                      <td data-label="Status">{{ ucfirst($inv->status ?? 'active') }}</td>

                      {{-- Buyer info from relation --}}
                      <td data-label="Buyer Name">{{ optional($inv->user)->name ?? '—' }}</td>
                      <td data-label="Buyer Phone">{{ optional($inv->user)->phone ?? '—' }}</td>
                      <td data-label="Buyer Location">{{ optional($inv->user)->location ?? '—' }}</td>

                      <td data-label="Actions">
                        <div class="table-actions">
                          <a href="{{ route('admin.buyers.preferences.investment.show', $inv) }}"
                             class="btn-table btn-table-view">
                            View
                          </a>
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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
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
