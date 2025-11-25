{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AIPropMatch - AI-Powered Real Estate Intelligence</title>

  {{-- Your custom CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

@php
    $user = auth()->user();
    $profileIncomplete = !$user || empty($user->name) || empty($user->phone) || empty($user->location);
@endphp

<body>
  <div id="app">
    <div id="dashboard-page" class="page dashboard-page">
      {{-- SIDEBAR --}}
      <div class="dashboard-sidebar">
        <div class="sidebar-header">
          <h2>AIPropMatch</h2>
        </div>

        <nav class="sidebar-nav">
          <a href="javascript:void(0)" class="sidebar-item active" onclick="showDashboardView('dashboard-home')">
            <span class="sidebar-icon">🏠</span>
            <span>Dashboard Home</span>
          </a>
          <a href="javascript:void(0)" class="sidebar-item" onclick="showDashboardView('buyer-module')">
            <span class="sidebar-icon">🔍</span>
            <span>Buyer Module</span>
          </a>
          <a href="javascript:void(0)" class="sidebar-item" onclick="showDashboardView('seller-module')">
            <span class="sidebar-icon">📋</span>
            <span>Seller Module</span>
          </a>
          <a href="javascript:void(0)" class="sidebar-item" onclick="showDashboardView('investment-module')">
            <span class="sidebar-icon">💰</span>
            <span>Investment Module</span>
          </a>

          <div class="sidebar-divider"></div>

          {{-- Logout --}}
          <a href="javascript:void(0)" class="sidebar-item"
             onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
            <span class="sidebar-icon">🚪</span>
            <span>Logout</span>
          </a>
          <form id="admin-logout-form" method="POST" action="{{ route('admin.logout') }}" style="display:none;">
            @csrf
          </form>
        </nav>
      </div>

      {{-- MAIN CONTENT --}}
      <div class="dashboard-main">
        {{-- Flash message --}}
        @if(session('success'))
          <div class="alert alert-success" style="margin: 16px;">
            {{ session('success') }}
          </div>
        @endif

        {{-- DASHBOARD HOME --}}
        <div id="dashboard-home" class="dashboard-view active">
          <div class="dashboard-header">
            <h1>Welcome to AIPropMatch</h1>
            <p>Your AI-powered real estate intelligence platform</p>
          </div>
          <div class="dashboard-cards">
            <div class="info-card">
              <div class="info-card-icon">🔍</div>
              <h3>Buyer Module</h3>
              <p>Find your perfect property with AI-powered matching</p>
              <button class="btn-secondary" onclick="showDashboardView('buyer-module')">Get Started</button>
            </div>
            <div class="info-card">
              <div class="info-card-icon">📋</div>
              <h3>Seller Module</h3>
              <p>List your property and reach qualified buyers</p>
              <button class="btn-secondary" onclick="showDashboardView('seller-module')">List Property</button>
            </div>
            <div class="info-card">
              <div class="info-card-icon">💰</div>
              <h3>Investment Module</h3>
              <p>Discover investment opportunities with high returns</p>
              <button class="btn-secondary" onclick="showDashboardView('investment-module')">Explore</button>
            </div>
          </div>

          <div class="ai-chat-placeholder">
            <div class="chat-header">
              <h3>AI Property Assistant</h3>
            </div>
            <div class="chat-messages">
              <div class="ai-message">
                <div class="message-avatar">🤖</div>
                <div class="message-content">
                  <p>Hello! I'm your AI property assistant. How can I help you today?</p>
                </div>
              </div>
            </div>
            <div class="chat-input-container">
              <input type="text" class="chat-input" placeholder="Ask me anything about properties...">
              <button class="btn-primary">Send</button>
            </div>
          </div>
        </div>

        {{-- BUYER MODULE --}}
        <div id="buyer-module" class="dashboard-view">
          <div class="dashboard-header">
            <h1>Buyer Module</h1>
            <p>Tell us what you're looking for</p>
          </div>

          <div class="module-content">
            {{-- PREFERRED LAND --}}
            <div class="accordion-card">
              <div class="accordion-header" onclick="toggleAccordion(this)">
                <h3>Preferred Land</h3>
                <span class="accordion-icon">▼</span>
              </div>
              <div class="accordion-content">
                <form class="module-form" method="POST" action="{{ route('buyer.preferences.land.store') }}">
                  @csrf
                  <div class="form-row">
                    <div class="form-group">
                      <label>Preferred District(s)</label>
                      <input type="text" name="districts" placeholder="e.g., Ernakulam, Kottayam">
                    </div>
                    <div class="form-group">
                      <label>Preferred Location(s)</label>
                      <input type="text" name="locations" placeholder="e.g., Kakkanad, Edapally">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Land Size Unit</label>
                      <select name="land_size_unit">
                        <option value="cent">Cent</option>
                        <option value="acre">Acre</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Budget Capacity per Cent (₹)</label>
                      <input type="number" name="budget_per_cent" placeholder="Enter amount">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Zoning Preference</label>
                      <select name="zoning_preference">
                        <option value="Residential">Residential</option>
                        <option value="Commercial">Commercial</option>
                        <option value="Agricultural">Agricultural</option>
                        <option value="Industrial">Industrial</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Timeline to Purchase</label>
                      <select name="timeline_to_purchase">
                        <option value="Immediate">Immediate</option>
                        <option value="Within 3 months">Within 3 months</option>
                        <option value="Within 6 months">Within 6 months</option>
                        <option value="Within 1 year">Within 1 year</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Mode of Purchase</label>
                      <select name="mode_of_purchase">
                        <option value="Cash">Cash</option>
                        <option value="Loan">Loan</option>
                        <option value="Mixed">Mixed</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Typical Advance Capacity (%)</label>
                      <input type="number" name="advance_capacity" placeholder="e.g., 20" min="0" max="100">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Documentation Speed</label>
                      <select name="documentation_speed">
                        <option value="Fast (1-2 weeks)">Fast (1-2 weeks)</option>
                        <option value="Normal (1 month)">Normal (1 month)</option>
                        <option value="Flexible">Flexible</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Preferred Property Condition</label>
                      <select name="property_condition">
                        <option value="Ready to build">Ready to build</option>
                        <option value="Need some work">Need some work</option>
                        <option value="Any condition">Any condition</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Amenities Preference</label>
                    <div class="checkbox-group">
                      <label><input type="checkbox" name="amenities[]" value="roadAccess"> Road Access</label>
                      <label><input type="checkbox" name="amenities[]" value="electricity"> Electricity</label>
                      <label><input type="checkbox" name="amenities[]" value="waterSupply"> Water Supply</label>
                      <label><input type="checkbox" name="amenities[]" value="drainage"> Drainage</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Infrastructure Preference</label>
                    <textarea name="infrastructure" placeholder="Describe your infrastructure requirements..." rows="3"></textarea>
                  </div>

                  <button type="submit" class="btn-primary">Save Preferences</button>
                </form>
              </div>
            </div>

            {{-- PREFERRED BUILDING --}}
            <div class="accordion-card">
              <div class="accordion-header" onclick="toggleAccordion(this)">
                <h3>Preferred Building</h3>
                <span class="accordion-icon">▼</span>
              </div>
              <div class="accordion-content">
                <form class="module-form" method="POST" action="{{ route('buyer.preferences.building.store') }}">
                  @csrf
                  <div class="form-row">
                    <div class="form-group">
                      <label>District(s)</label>
                      <input type="text" name="districts" placeholder="e.g., Ernakulam">
                    </div>
                    <div class="form-group">
                      <label>Type of Building</label>
                      <select name="building_type">
                        <option>Commercial</option>
                        <option>Office</option>
                        <option>Retail</option>
                        <option>Apartment</option>
                        <option>Villa Project</option>
                        <option>Mixed-Use</option>
                        <option>Hotel</option>
                        <option>Hospital</option>
                        <option>Warehouse</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Built-up Area Min (sq.ft)</label>
                      <input type="number" name="area_min" placeholder="Minimum area">
                    </div>
                    <div class="form-group">
                      <label>Built-up Area Max (sq.ft)</label>
                      <input type="number" name="area_max" placeholder="Maximum area">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Frontage Requirement (ft)</label>
                      <input type="number" name="frontage_min" placeholder="Enter frontage">
                    </div>
                    <div class="form-group">
                      <label>Age of Building Acceptable</label>
                      <select name="age_preference">
                        <option>New (0-5 years)</option>
                        <option>Recent (5-10 years)</option>
                        <option>Moderate (10-20 years)</option>
                        <option>Any age</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Total Budget (₹)</label>
                    <input type="number" name="total_budget" placeholder="Enter total budget">
                  </div>

                  <div class="form-group">
                    <label>Micro-location Preferences</label>
                    <input type="text" name="micro_locations" placeholder="Specific areas you prefer">
                  </div>

                  <div class="form-group">
                    <label>Distance Requirements</label>
                    <div class="checkbox-group">
                      <label><input type="checkbox" name="distance_requirements[]" value="nearHighway"> Near NH/SH</label>
                      <label><input type="checkbox" name="distance_requirements[]" value="nearCityCentre"> Near City Centre</label>
                      <label><input type="checkbox" name="distance_requirements[]" value="nearHospital"> Near Hospital</label>
                      <label><input type="checkbox" name="distance_requirements[]" value="nearSchool"> Near School</label>
                      <label><input type="checkbox" name="distance_requirements[]" value="nearITPark"> Near IT Park</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Rent Expectation (if looking for rental asset)</label>
                    <input type="number" name="rent_expectation" placeholder="Expected monthly rent">
                  </div>

                  <button type="submit" class="btn-primary">Save Preferences</button>
                </form>
              </div>
            </div>

            {{-- PREFERRED INVESTMENT --}}
            <div class="accordion-card">
              <div class="accordion-header" onclick="toggleAccordion(this)">
                <h3>Preferred Investment</h3>
                <span class="accordion-icon">▼</span>
              </div>
              <div class="accordion-content">
                <form class="module-form" method="POST" action="{{ route('buyer.preferences.investment.store') }}">
                  @csrf
                  <div class="form-row">
                    <div class="form-group">
                      <label>District</label>
                      <input type="text" name="districts" placeholder="Preferred district">
                    </div>
                    <div class="form-group">
                      <label>Preferred Location</label>
                      <input type="text" name="locations" placeholder="Specific location">
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Property Type</label>
                    <select name="investment_property_type">
                      <option>Land</option>
                      <option>Rental Buildings</option>
                      <option>Villas</option>
                      <option>Flats</option>
                      <option>Hospital</option>
                      <option>Any</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Budget Range (₹)</label>
                    <div class="slider-container">
                      <input
                        type="range"
                        name="budget_range"
                        min="0"
                        max="10000000"
                        step="100000"
                        value="5000000"
                        id="investment-budget"
                        oninput="updateBudgetValue(this.value)"
                      >
                      <span id="budget-value">₹50,00,000</span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Profit Expectation per Year (%)</label>
                    <input type="number" name="profit_expectation_year" placeholder="e.g., 15" min="0" max="100">
                  </div>

                  <button type="submit" class="btn-primary">Save Preferences</button>
                </form>
              </div>
            </div>
    <!-- ====== BUYING REQUIREMENTS (Light theme cards) ====== -->
       <!-- ====== BUYING REQUIREMENTS (Dynamic) ====== -->
    <div class="buyer-req-summary">
  <div class="buyer-req-header">
    <h2>Your Buying Requirements</h2>
    <p>Snapshot of what you’re looking to buy – land, buildings, and investments.</p>
  </div>

  <!-- Tabs -->
  <div class="buyer-tabs">
    <button class="buyer-tab buyer-tab-active" data-target="buyer-land-panel">Land</button>
    <button class="buyer-tab" data-target="buyer-building-panel">Buildings</button>
    <button class="buyer-tab" data-target="buyer-investment-panel">Investments</button>
  </div>

  {{-- LAND TAB --}}
<div id="buyer-land-panel" class="buyer-tab-panel buyer-tab-panel-active">
  @if($buyerLand)
    @php $landStatus = $buyerLand->status ?? 'active'; @endphp

    <div class="buyer-card">
      <div class="buyer-card-header">
        <div>
          <div class="buyer-code">REQ-LAND-{{ $buyerLand->id }}</div>
          <span class="buyer-chip
            {{ $landStatus === 'urgent' ? 'buyer-chip-danger' : '' }}
            {{ $landStatus === 'completed' ? 'buyer-chip-soft' : '' }}
            {{ $landStatus === 'active' ? 'buyer-chip-primary' : '' }}
          ">
            {{ ucfirst($landStatus) }}
          </span>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="buyer-actions">
          {{-- EDIT BUTTON – opens modal --}}
          <button type="button"
                  class="buyer-action-btn"
                  onclick="openEditModal('land')">
            Edit
          </button>

          {{-- Mark Urgent --}}
          @if($landStatus !== 'urgent')
            <form method="POST"
                  action="{{ route('buyer.preferences.land.status', $buyerLand->id) }}"
                  style="display:inline;">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="urgent">
              <button type="submit" class="buyer-action-btn buyer-action-btn-warning">
                Mark Urgent
              </button>
            </form>
          @endif

          {{-- Mark Completed --}}
          @if($landStatus !== 'completed')
            <form method="POST"
                  action="{{ route('buyer.preferences.land.status', $buyerLand->id) }}"
                  style="display:inline;">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="completed">
              <button type="submit" class="buyer-action-btn buyer-action-btn-success">
                Mark Completed
              </button>
            </form>
          @endif
        </div>
      </div>

      <div class="buyer-card-body">
        <div class="buyer-grid">
          <div>
            <div class="buyer-label">Preferred Districts</div>
            <div class="buyer-value">
              {{ $buyerLand->preferred_districts ? implode(', ', $buyerLand->preferred_districts) : '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Preferred Locations</div>
            <div class="buyer-value">
              {{ $buyerLand->preferred_locations ? implode(', ', $buyerLand->preferred_locations) : '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Budget / cent</div>
            <div class="buyer-value">
              @if($buyerLand->budget_per_cent_min || $buyerLand->budget_per_cent_max)
                ₹{{ number_format($buyerLand->budget_per_cent_min ?? 0) }}
                @if($buyerLand->budget_per_cent_max && $buyerLand->budget_per_cent_max != $buyerLand->budget_per_cent_min)
                  – ₹{{ number_format($buyerLand->budget_per_cent_max) }}
                @endif
              @else
                —
              @endif
            </div>
          </div>
          <div>
            <div class="buyer-label">Zoning</div>
            <div class="buyer-value">{{ $buyerLand->zoning_preference ?? '—' }}</div>
          </div>
          <div>
            <div class="buyer-label">Timeline</div>
            <div class="buyer-value">{{ $buyerLand->timeline_to_purchase ?? '—' }}</div>
          </div>
          <div>
            <div class="buyer-label">Mode of Purchase</div>
            <div class="buyer-value">{{ $buyerLand->mode_of_purchase ?? '—' }}</div>
          </div>
          <div>
            <div class="buyer-label">Advance Capacity</div>
            <div class="buyer-value">
              {{ $buyerLand->advance_capacity ? $buyerLand->advance_capacity . '%' : '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Amenities</div>
            <div class="buyer-value">
              @if($buyerLand->amenities_preference && count($buyerLand->amenities_preference))
                {{ implode(', ', $buyerLand->amenities_preference) }}
              @else
                —
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ========= EDIT LAND PREFERENCE MODAL ========= --}}
    <div id="edit-land-modal" class="buyer-modal-overlay" style="display:none;">
      <div class="buyer-modal">
        <div class="buyer-modal-header">
          <h2>Edit Land Preference</h2>
          <button type="button"
                  class="buyer-modal-close"
                  onclick="closeEditModal('land')">×</button>
        </div>

        <form method="POST"
              action="{{ route('buyer.preferences.land.update', $buyerLand->id) }}"
              class="module-form">
          @csrf
          @method('PATCH')

          <div class="form-row">
            <div class="form-group">
              <label>Preferred District(s)</label>
              <input type="text"
                     name="districts"
                     value="{{ old('districts', is_array($buyerLand->preferred_districts) ? implode(', ', $buyerLand->preferred_districts) : $buyerLand->preferred_districts) }}"
                     placeholder="e.g., Ernakulam, Kottayam">
            </div>
            <div class="form-group">
              <label>Preferred Location(s)</label>
              <input type="text"
                     name="locations"
                     value="{{ old('locations', is_array($buyerLand->preferred_locations) ? implode(', ', $buyerLand->preferred_locations) : $buyerLand->preferred_locations) }}"
                     placeholder="e.g., Kakkanad, Edapally">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Land Size Unit</label>
              <select name="land_size_unit">
                <option value="cent" {{ $buyerLand->land_size_unit === 'cent' ? 'selected' : '' }}>Cent</option>
                <option value="acre" {{ $buyerLand->land_size_unit === 'acre' ? 'selected' : '' }}>Acre</option>
              </select>
            </div>
            <div class="form-group">
              <label>Budget Capacity / cent (₹)</label>
              <input type="number"
                     name="budget_per_cent"
                     value="{{ old('budget_per_cent', $buyerLand->budget_per_cent_min) }}"
                     placeholder="Enter amount">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Zoning Preference</label>
              <select name="zoning_preference">
                <option value="Residential" {{ $buyerLand->zoning_preference === 'Residential' ? 'selected' : '' }}>Residential</option>
                <option value="Commercial" {{ $buyerLand->zoning_preference === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                <option value="Agricultural" {{ $buyerLand->zoning_preference === 'Agricultural' ? 'selected' : '' }}>Agricultural</option>
                <option value="Industrial" {{ $buyerLand->zoning_preference === 'Industrial' ? 'selected' : '' }}>Industrial</option>
              </select>
            </div>
            <div class="form-group">
              <label>Timeline to Purchase</label>
              <select name="timeline_to_purchase">
                <option value="Immediate" {{ $buyerLand->timeline_to_purchase === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                <option value="Within 3 months" {{ $buyerLand->timeline_to_purchase === 'Within 3 months' ? 'selected' : '' }}>Within 3 months</option>
                <option value="Within 6 months" {{ $buyerLand->timeline_to_purchase === 'Within 6 months' ? 'selected' : '' }}>Within 6 months</option>
                <option value="Within 1 year" {{ $buyerLand->timeline_to_purchase === 'Within 1 year' ? 'selected' : '' }}>Within 1 year</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Mode of Purchase</label>
              <select name="mode_of_purchase">
                <option value="Cash" {{ $buyerLand->mode_of_purchase === 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Loan" {{ $buyerLand->mode_of_purchase === 'Loan' ? 'selected' : '' }}>Loan</option>
                <option value="Mixed" {{ $buyerLand->mode_of_purchase === 'Mixed' ? 'selected' : '' }}>Mixed</option>
              </select>
            </div>
            <div class="form-group">
              <label>Typical Advance Capacity (%)</label>
              <input type="number"
                     name="advance_capacity"
                     value="{{ old('advance_capacity', $buyerLand->advance_capacity) }}"
                     min="0" max="100">
            </div>
          </div>

          <div class="form-group">
            <label>Amenities Preference</label>
            @php $amenities = $buyerLand->amenities_preference ?? []; @endphp
            <div class="checkbox-group">
              <label><input type="checkbox" name="amenities[]" value="roadAccess" {{ in_array('roadAccess', $amenities) ? 'checked' : '' }}> Road Access</label>
              <label><input type="checkbox" name="amenities[]" value="electricity" {{ in_array('electricity', $amenities) ? 'checked' : '' }}> Electricity</label>
              <label><input type="checkbox" name="amenities[]" value="waterSupply" {{ in_array('waterSupply', $amenities) ? 'checked' : '' }}> Water Supply</label>
              <label><input type="checkbox" name="amenities[]" value="drainage" {{ in_array('drainage', $amenities) ? 'checked' : '' }}> Drainage</label>
            </div>
          </div>

          <div class="form-group">
            <label>Infrastructure Preference</label>
            <textarea name="infrastructure"
                      rows="3"
                      placeholder="Describe your infrastructure requirements...">{{ old('infrastructure', $buyerLand->infrastructure_preference) }}</textarea>
          </div>

          <div class="buyer-modal-actions">
            <button type="button"
                    class="btn-secondary"
                    onclick="closeEditModal('land')">
              Cancel
            </button>
            <button type="submit" class="btn-primary">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
    {{-- ========= END EDIT LAND MODAL ========= --}}

  @else
    <p style="font-size:13px; color:#9ca3af; margin-top:8px;">
      No land buying requirement saved yet. Fill the “Preferred Land” form above.
    </p>
  @endif
</div>



  {{-- BUILDING TAB --}}
 {{-- BUILDING TAB --}}
<div id="buyer-building-panel" class="buyer-tab-panel">
  @if($buyerBuilding)
    @php
      $bldStatus   = $buyerBuilding->status ?? 'active';
      $distanceReq = $buyerBuilding->distance_requirement
        ? explode(',', $buyerBuilding->distance_requirement)
        : [];
    @endphp

    <div class="buyer-card">
      <div class="buyer-card-header">
        <div>
          <div class="buyer-code">REQ-BLD-{{ $buyerBuilding->id }}</div>
          <span class="buyer-chip
            {{ $bldStatus === 'urgent' ? 'buyer-chip-danger' : '' }}
            {{ $bldStatus === 'completed' ? 'buyer-chip-soft' : '' }}
            {{ $bldStatus === 'active' ? 'buyer-chip-primary' : '' }}
          ">
            {{ ucfirst($bldStatus) }}
          </span>
        </div>

        <div class="buyer-actions">
          {{-- EDIT → open modal --}}
          <button type="button"
                  class="buyer-action-btn"
                  onclick="openEditModal('building')">
            Edit
          </button>

          {{-- Mark Urgent --}}
          @if($bldStatus !== 'urgent')
            <form method="POST"
                  action="{{ route('buyer.preferences.building.status', $buyerBuilding->id) }}"
                  style="display:inline;">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="urgent">
              <button type="submit" class="buyer-action-btn buyer-action-btn-warning">
                Mark Urgent
              </button>
            </form>
          @endif

          {{-- Mark Completed --}}
          @if($bldStatus !== 'completed')
            <form method="POST"
                  action="{{ route('buyer.preferences.building.status', $buyerBuilding->id) }}"
                  style="display:inline;">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="completed">
              <button type="submit" class="buyer-action-btn buyer-action-btn-success">
                Mark Completed
              </button>
            </form>
          @endif
        </div>
      </div>

      <div class="buyer-card-body">
        <div class="buyer-grid">
          <div>
            <div class="buyer-label">Building Type</div>
            <div class="buyer-value">{{ $buyerBuilding->building_type ?? '—' }}</div>
          </div>
          <div>
            <div class="buyer-label">Preferred Districts</div>
            <div class="buyer-value">
              {{ $buyerBuilding->preferred_districts ? implode(', ', $buyerBuilding->preferred_districts) : '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Built-up Area</div>
            <div class="buyer-value">
              @if($buyerBuilding->area_min || $buyerBuilding->area_max)
                {{ number_format($buyerBuilding->area_min ?? 0) }} –
                {{ number_format($buyerBuilding->area_max ?? $buyerBuilding->area_min ?? 0) }} sqft
              @else
                —
              @endif
            </div>
          </div>
          <div>
            <div class="buyer-label">Budget</div>
            <div class="buyer-value">
              @if($buyerBuilding->total_budget_min)
                ₹{{ number_format($buyerBuilding->total_budget_min) }}
                @if($buyerBuilding->total_budget_max && $buyerBuilding->total_budget_max != $buyerBuilding->total_budget_min)
                  – ₹{{ number_format($buyerBuilding->total_budget_max) }}
                @endif
              @else
                —
              @endif
            </div>
          </div>
          <div>
            <div class="buyer-label">Micro-locations</div>
            <div class="buyer-value">
              {{ $buyerBuilding->micro_locations ? implode(', ', $buyerBuilding->micro_locations) : '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Distance Requirements</div>
            <div class="buyer-value">
              {{ $buyerBuilding->distance_requirement ?: '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Rent Expectation</div>
            <div class="buyer-value">
              {{ $buyerBuilding->rent_expectation ? '₹' . number_format($buyerBuilding->rent_expectation) . ' / month' : '—' }}
            </div>
          </div>
        </div>
      </div>
    </div> {{-- .buyer-card --}}

    {{-- === EDIT BUILDING PREFERENCE MODAL === --}}
    <div id="edit-building-modal" class="buyer-modal-overlay" style="display:none;">
      <div class="buyer-modal">
        <div class="buyer-modal-header">
          <h2>Edit Building Preference</h2>
          <button type="button" class="buyer-modal-close" onclick="closeEditModal('building')">×</button>
        </div>

        <form method="POST"
              action="{{ route('buyer.preferences.building.update', $buyerBuilding->id) }}"
              class="module-form">
          @csrf
          @method('PATCH')

          <div class="form-row">
            <div class="form-group">
              <label>District(s)</label>
              <input type="text"
                     name="districts"
                     value="{{ old('districts', is_array($buyerBuilding->preferred_districts) ? implode(', ', $buyerBuilding->preferred_districts) : $buyerBuilding->preferred_districts) }}"
                     placeholder="e.g., Ernakulam, Kottayam">
            </div>
            <div class="form-group">
              <label>Type of Building</label>
              <select name="building_type">
                @php
                  $types = [
                    'Commercial','Office','Retail','Apartment',
                    'Villa','Mixed-Use','Hotel','Hospital','Warehouse'
                  ];
                @endphp
                @foreach($types as $type)
                  <option value="{{ $type }}"
                          {{ ($buyerBuilding->building_type === $type) ? 'selected' : '' }}>
                    {{ $type }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Built-up Area Min (sq.ft)</label>
              <input type="number"
                     name="area_min"
                     value="{{ old('area_min', $buyerBuilding->area_min) }}"
                     placeholder="Minimum area">
            </div>
            <div class="form-group">
              <label>Built-up Area Max (sq.ft)</label>
              <input type="number"
                     name="area_max"
                     value="{{ old('area_max', $buyerBuilding->area_max) }}"
                     placeholder="Maximum area">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Frontage Requirement (ft)</label>
              <input type="number"
                     name="frontage_min"
                     value="{{ old('frontage_min', $buyerBuilding->frontage_min) }}"
                     placeholder="Enter frontage">
            </div>
            <div class="form-group">
              <label>Age of Building Acceptable</label>
              <select name="age_preference">
                @php
                  $ageOptions = [
                    'New (0-5 years)',
                    'Recent (5-10 years)',
                    'Moderate (10-20 years)',
                    'Any age',
                  ];
                @endphp
                @foreach($ageOptions as $opt)
                  <option value="{{ $opt }}"
                          {{ ($buyerBuilding->age_preference === $opt) ? 'selected' : '' }}>
                    {{ $opt }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Total Budget (₹)</label>
            <input type="number"
                   name="total_budget"
                   value="{{ old('total_budget', $buyerBuilding->total_budget_min) }}"
                   placeholder="Enter total budget">
          </div>

          <div class="form-group">
            <label>Micro-location Preferences</label>
            <input type="text"
                   name="micro_locations"
                   value="{{ old('micro_locations', is_array($buyerBuilding->micro_locations) ? implode(', ', $buyerBuilding->micro_locations) : $buyerBuilding->micro_locations) }}"
                   placeholder="Specific areas you prefer">
          </div>

          <div class="form-group">
            <label>Distance Requirements</label>
            <div class="checkbox-group">
              <label>
                <input type="checkbox"
                       name="distance_requirements[]"
                       value="nearHighway"
                       {{ in_array('nearHighway', $distanceReq) ? 'checked' : '' }}>
                Near NH/SH
              </label>
              <label>
                <input type="checkbox"
                       name="distance_requirements[]"
                       value="nearCityCentre"
                       {{ in_array('nearCityCentre', $distanceReq) ? 'checked' : '' }}>
                Near City Centre
              </label>
              <label>
                <input type="checkbox"
                       name="distance_requirements[]"
                       value="nearHospital"
                       {{ in_array('nearHospital', $distanceReq) ? 'checked' : '' }}>
                Near Hospital
              </label>
              <label>
                <input type="checkbox"
                       name="distance_requirements[]"
                       value="nearSchool"
                       {{ in_array('nearSchool', $distanceReq) ? 'checked' : '' }}>
                Near School
              </label>
              <label>
                <input type="checkbox"
                       name="distance_requirements[]"
                       value="nearITPark"
                       {{ in_array('nearITPark', $distanceReq) ? 'checked' : '' }}>
                Near IT Park
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Rent Expectation (if looking for rental asset)</label>
            <input type="number"
                   name="rent_expectation"
                   value="{{ old('rent_expectation', $buyerBuilding->rent_expectation) }}"
                   placeholder="Expected monthly rent">
          </div>

          <div class="buyer-modal-actions">
            <button type="button"
                    class="btn-secondary"
                    onclick="closeEditModal('building')">
              Cancel
            </button>
            <button type="submit" class="btn-primary">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div> {{-- #edit-building-modal --}}
  @else
    <p style="font-size:13px; color:#9ca3af; margin-top:8px;">
      No building requirement saved yet. Fill the “Preferred Building” form above.
    </p>
  @endif
</div>

  {{-- INVESTMENT TAB --}}
{{-- INVESTMENT TAB --}}
<div id="buyer-investment-panel" class="buyer-tab-panel">
  @if($buyerInvestment)
    @php $invStatus = $buyerInvestment->status ?? 'active'; @endphp

    <div class="buyer-card">
      <div class="buyer-card-header">
        <div>
          <div class="buyer-code">REQ-INV-{{ $buyerInvestment->id }}</div>
          <span class="buyer-chip
            {{ $invStatus === 'urgent' ? 'buyer-chip-danger' : '' }}
            {{ $invStatus === 'completed' ? 'buyer-chip-soft' : '' }}
            {{ $invStatus === 'active' ? 'buyer-chip-primary' : '' }}
          ">
            {{ ucfirst($invStatus) }}
          </span>
        </div>

        <div class="buyer-actions">
          {{-- OPEN EDIT MODAL --}}
          <button type="button"
                  class="buyer-action-btn"
                  onclick="openEditModal('investment')">
            Edit
          </button>

          {{-- Mark Urgent --}}
          @if($invStatus !== 'urgent')
            <form method="POST"
                  action="{{ route('buyer.preferences.investment.status', $buyerInvestment->id) }}"
                  style="display:inline;">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="urgent">
              <button type="submit" class="buyer-action-btn buyer-action-btn-warning">
                Mark Urgent
              </button>
            </form>
          @endif

          {{-- Mark Completed --}}
          @if($invStatus !== 'completed')
            <form method="POST"
                  action="{{ route('buyer.preferences.investment.status', $buyerInvestment->id) }}"
                  style="display:inline;">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="completed">
              <button type="submit" class="buyer-action-btn buyer-action-btn-success">
                Mark Completed
              </button>
            </form>
          @endif
        </div>
      </div>

      <div class="buyer-card-body">
        <div class="buyer-grid">
          <div>
            <div class="buyer-label">Property Type</div>
            <div class="buyer-value">{{ $buyerInvestment->investment_property_type ?? '—' }}</div>
          </div>
          <div>
            <div class="buyer-label">Preferred Districts</div>
            <div class="buyer-value">
              {{ $buyerInvestment->preferred_districts ? implode(', ', $buyerInvestment->preferred_districts) : '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Preferred Locations</div>
            <div class="buyer-value">
              {{ $buyerInvestment->preferred_locations ? implode(', ', $buyerInvestment->preferred_locations) : '—' }}
            </div>
          </div>
          <div>
            <div class="buyer-label">Budget Range</div>
            <div class="buyer-value">
              @if($buyerInvestment->investment_budget_min)
                ₹{{ number_format($buyerInvestment->investment_budget_min) }}
                @if($buyerInvestment->investment_budget_max && $buyerInvestment->investment_budget_max != $buyerInvestment->investment_budget_min)
                  – ₹{{ number_format($buyerInvestment->investment_budget_max) }}
                @endif
              @else
                —
              @endif
            </div>
          </div>
          <div>
            <div class="buyer-label">Profit Expectation / year</div>
            <div class="buyer-value">
              {{ $buyerInvestment->profit_expectation_year ? $buyerInvestment->profit_expectation_year . '%' : '—' }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- EDIT INVESTMENT PREFERENCE MODAL --}}
    <div id="edit-investment-modal" class="buyer-modal-overlay" style="display:none;">
      <div class="buyer-modal">
        <div class="buyer-modal-header">
          <h2>Edit Investment Preference</h2>
          <button type="button"
                  class="buyer-modal-close"
                  onclick="closeEditModal('investment')">
            ×
          </button>
        </div>

        <form method="POST"
              action="{{ route('buyer.preferences.investment.update', $buyerInvestment->id) }}"
              class="module-form">
          @csrf
          @method('PATCH')

          <div class="form-row">
            <div class="form-group">
              <label>Preferred District(s)</label>
              <input type="text"
                     name="districts"
                     value="{{ old('districts',
                        is_array($buyerInvestment->preferred_districts)
                          ? implode(', ', $buyerInvestment->preferred_districts)
                          : $buyerInvestment->preferred_districts
                     ) }}"
                     placeholder="e.g., Ernakulam, Kottayam">
            </div>
            <div class="form-group">
              <label>Preferred Location(s)</label>
              <input type="text"
                     name="locations"
                     value="{{ old('locations',
                        is_array($buyerInvestment->preferred_locations)
                          ? implode(', ', $buyerInvestment->preferred_locations)
                          : $buyerInvestment->preferred_locations
                     ) }}"
                     placeholder="e.g., Kakkanad, Vyttila">
            </div>
          </div>

          <div class="form-group">
            <label>Property Type</label>
            <select name="investment_property_type">
              @php
                $types = ['Land', 'Rental Buildings', 'Villas', 'Flats', 'Hospital', 'Any'];
                $currentType = old('investment_property_type', $buyerInvestment->investment_property_type);
              @endphp
              @foreach($types as $type)
                <option value="{{ $type }}" {{ $currentType === $type ? 'selected' : '' }}>
                  {{ $type }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Budget Range (₹)</label>
              <input type="number"
                     name="budget_range"
                     value="{{ old('budget_range', $buyerInvestment->investment_budget_min) }}"
                     placeholder="Total budget">
            </div>
            <div class="form-group">
              <label>Profit Expectation per Year (%)</label>
              <input type="number"
                     name="profit_expectation_year"
                     value="{{ old('profit_expectation_year', $buyerInvestment->profit_expectation_year) }}"
                     min="0"
                     max="100"
                     placeholder="e.g., 15">
            </div>
          </div>

          <div class="buyer-modal-actions">
            <button type="button"
                    class="btn-secondary"
                    onclick="closeEditModal('investment')">
              Cancel
            </button>
            <button type="submit" class="btn-primary">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  @else
    <p style="font-size:13px; color:#9ca3af; margin-top:8px;">
      No investment requirement saved yet. Fill the “Preferred Investment” form above.
    </p>
  @endif
</div>

</div>

    <!-- ====== END BUYING REQUIREMENTS ====== -->

    <!-- ====== END BUYING REQUIREMENTS ====== -->

            

            {{-- Dummy Matching Block --}}
            <div class="matching-results">
              <h3>AI Matching Results</h3>
              <div class="match-card">
                <div class="match-header">
                  <div class="iss-score">
                    <span class="iss-label">ISS Score</span>
                    <span class="iss-value">87</span>
                  </div>
                </div>
                <h4>Premium Plot in Kakkanad</h4>
                <p>15 cents | Residential | ₹8L per cent</p>
                <button class="btn-secondary">View Details</button>
              </div>
            </div>
          </div>
        </div>

        {{-- SELLER MODULE (placeholder – we can wire forms next) --}}
       <div id="seller-module" class="dashboard-view">
  <div class="dashboard-header">
    <h1>Seller Module</h1>
    <p>List your property</p>
  </div>

  <div class="module-content">
    {{-- LAND SALE LISTING --}}
    <div class="accordion-card">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Land Sale Listing</h3>
        <span class="accordion-icon">▼</span>
      </div>
      <div class="accordion-content">
        <form class="module-form"
              method="POST"
              action="{{ route('seller.land.store') }}"
              enctype="multipart/form-data">
          @csrf

          <h4>Location Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>District</label>
              <input type="text" name="district" placeholder="District">
            </div>
            <div class="form-group">
              <label>Taluk</label>
              <input type="text" name="taluk" placeholder="Taluk">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Village</label>
              <input type="text" name="village" placeholder="Village">
            </div>
            <div class="form-group">
              <label>Location</label>
              <input type="text" name="exact_location" placeholder="Specific location">
            </div>
          </div>
          <div class="form-group">
            <label>Landmarks</label>
            <input type="text" name="landmark" placeholder="Nearby landmarks">
          </div>
          <div class="form-group">
            <label>Google Maps Link</label>
            <input type="url" name="google_map_link" placeholder="https://maps.google.com/...">
          </div>

          <h4>Property Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Land Area</label>
              <input type="number" step="0.01" name="land_area" placeholder="Area">
            </div>
            <div class="form-group">
              <label>Unit</label>
              <select name="land_unit">
                <option value="cent">Cent</option>
                <option value="acre">Acre</option>
                <option value="sqft">Sq.ft</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Frontage (ft)</label>
              <input type="number" name="road_frontage" placeholder="Frontage">
            </div>
            <div class="form-group">
              <label>Plot Shape</label>
              <select name="plot_shape">
                <option value="Rectangle">Rectangle</option>
                <option value="Square">Square</option>
                <option value="Irregular">Irregular</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Zoning Type</label>
              <select name="zoning_type">
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
                <option value="Agricultural">Agricultural</option>
                <option value="Industrial">Industrial</option>
              </select>
            </div>
            <div class="form-group">
              <label>Ownership Type</label>
              <select name="ownership_type">
                <option value="Individual">Individual</option>
                <option value="Joint">Joint</option>
                <option value="Company">Company</option>
                <option value="POA">POA Holder</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Restrictions</label>
            <textarea name="restrictions" placeholder="Any legal restrictions or encumbrances..." rows="2"></textarea>
          </div>

          <h4>Pricing</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Expected Price per Cent/Acre (₹)</label>
              <input type="number" step="0.01" name="expected_price_per_cent" placeholder="Price">
            </div>
            <div class="form-group">
              <label>Negotiability</label>
              <select name="negotiability">
                <option value="Negotiable">Negotiable</option>
                <option value="Slightly">Slightly Negotiable</option>
                <option value="Fixed">Fixed</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Expected Advance (%)</label>
              <input type="number" name="expected_advance_pct" placeholder="Percentage" min="0" max="100">
            </div>
            <div class="form-group">
              <label>Sale Timeline</label>
              <select name="sale_timeline">
                <option value="Urgent (Within 1 month)">Urgent (Within 1 month)</option>
                <option value="Normal (1-3 months)">Normal (1-3 months)</option>
                <option value="Flexible">Flexible</option>
              </select>
            </div>
          </div>

          <h4>Land Characteristics</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Land Type</label>
              <select name="land_type">
                <option value="Dry Land">Dry Land</option>
                <option value="Wet Land">Wet Land</option>
                <option value="Garden Land">Garden Land</option>
              </select>
            </div>
            <div class="form-group">
              <label>Current Usage</label>
              <input type="text" name="current_use" placeholder="Current land usage">
            </div>
          </div>

          <h4>Amenities</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Road Width (ft)</label>
              <input type="number" name="road_width" placeholder="Road width">
            </div>
            <div class="form-group">
              <label>Electricity</label>
              <select name="electricity">
                <option value="1">Available</option>
                <option value="0">Not Available</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
  <label>Water</label>
  <select name="water">
    <option value="1">Available</option>
    <option value="0">Not Available</option>
  </select>
</div>

            <div class="form-group">
              <label>Drainage</label>
              <select name="drainage">
                <option value="1">Available</option>
                <option value="0">Not Available</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Compound Wall</label>
            <select name="compound_wall">
              <option value="Complete">Complete</option>
              <option value="Partial">Partial</option>
              <option value="None">None</option>
            </select>
          </div>

          <h4>Documents & Media</h4>
          <div class="form-group">
            <label>Land Tax Receipt</label>
            <input type="file" name="land_tax_receipt" accept=".pdf,.jpg,.png">
          </div>
          <div class="form-group">
            <label>Location Sketch</label>
            <input type="file" name="location_sketch" accept=".pdf,.jpg,.png">
          </div>
          <div class="form-group">
            <label>Photos (up to 4)</label>
            <input type="file" name="photos[]" accept="image/*" multiple>
          </div>
          <div class="form-group">
            <label>Video</label>
            <input type="file" name="video" accept="video/*">
          </div>

          <button type="submit" class="btn-primary">Submit Listing</button>
        </form>
      </div>
    </div>

    {{-- BUILDING SALE LISTING --}}
    <div class="accordion-card">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Building Sale Listing</h3>
        <span class="accordion-icon">▼</span>
      </div>
      <div class="accordion-content">
        <form class="module-form"
              method="POST"
              action="{{ route('seller.building.store') }}"
              enctype="multipart/form-data">
          @csrf

          <h4>Location Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>District</label>
              <input type="text" name="district" placeholder="District">
            </div>
            <div class="form-group">
              <label>Area / Town</label>
              <input type="text" name="area" placeholder="Area / Town">
            </div>
          </div>
          <div class="form-group">
            <label>Street Name</label>
            <input type="text" name="street_name" placeholder="Street name">
          </div>
          <div class="form-group">
            <label>Landmark</label>
            <input type="text" name="landmark" placeholder="Landmark">
          </div>
          <div class="form-group">
            <label>Google Maps Link</label>
            <input type="url" name="map_link" placeholder="https://maps.google.com/...">
          </div>

          <h4>Building Details</h4>
          <div class="form-group">
            <label>Building Type</label>
            <select name="building_type">
              <option>Commercial</option>
              <option>Office</option>
              <option>Retail</option>
              <option>Apartment</option>
              <option>Villa</option>
              <option>Mixed-Use</option>
              <option>Hotel</option>
              <option>Hospital</option>
              <option>Warehouse</option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Plot Area (sq.ft)</label>
              <input type="number" step="0.01" name="total_plot_area" placeholder="Plot area">
            </div>
            <div class="form-group">
              <label>Built-up Area (sq.ft)</label>
              <input type="number" step="0.01" name="builtup_area" placeholder="Built-up area">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Number of Floors</label>
              <input type="number" name="floors" placeholder="Floors">
            </div>
            <div class="form-group">
              <label>Construction Year</label>
              <input type="number" name="construction_year" placeholder="Year built">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Lift Available</label>
              <select name="lift_available">
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Parking Capacity</label>
              <input type="number" name="parking_slots" placeholder="Number of vehicles">
            </div>
          </div>
          <div class="form-group">
            <label>Road Frontage (ft)</label>
            <input type="number" name="road_frontage" placeholder="Frontage">
          </div>

          <h4>Pricing</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Total Price (₹)</label>
              <input type="number" step="0.01" name="expected_price" placeholder="Total price">
            </div>
            <div class="form-group">
              <label>Negotiability</label>
              <select name="negotiability">
                <option>Negotiable</option>
                <option>Slightly Negotiable</option>
                <option>Fixed</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Timeline to Sell</label>
            <select name="sell_timeline">
              <option>Urgent (Within 1 month)</option>
              <option>Normal (1-3 months)</option>
              <option>Flexible</option>
            </select>
          </div>

          <h4>Documents</h4>
          <div class="form-group">
            <label>Building Permit</label>
            <input type="file" name="building_permit" accept=".pdf,.jpg,.png">
          </div>
          <div class="form-group">
            <label>Completion Certificate</label>
            <input type="file" name="completion_certificate" accept=".pdf,.jpg,.png">
          </div>
          <div class="form-group">
            <label>Ownership Documents</label>
            <input type="file" name="ownership_documents[]" accept=".pdf,.jpg,.png" multiple>
          </div>
          <div class="form-group">
            <label>Photos</label>
            <input type="file" name="photos[]" accept="image/*" multiple>
          </div>

          <button type="submit" class="btn-primary">Submit Listing</button>
        </form>
      </div>
    </div>

    {{-- INVESTMENT OPPORTUNITY LISTING --}}
    <div class="accordion-card">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Investment Opportunity Listing</h3>
        <span class="accordion-icon">▼</span>
      </div>
      <div class="accordion-content">
        <form class="module-form"
              method="POST"
              action="{{ route('seller.investment.store') }}"
              enctype="multipart/form-data">
          @csrf

          <h4>Project Overview</h4>
          <div class="form-group">
            <label>Project Name</label>
            <input type="text" name="project_name" placeholder="Name of the project">
          </div>
          <div class="form-group">
            <label>Project Type</label>
            <select name="project_type">
              <option>Land development</option>
              <option>Rental Property</option>
              <option>Commercial Complex</option>
              <option>Residential Project</option>
              <option>Mixed Development</option>
            </select>
          </div>
          <div class="form-group">
            <label>Project Description</label>
            <textarea name="project_description" placeholder="Detailed description of the investment opportunity..." rows="4"></textarea>
          </div>

          <h4>Location</h4>
          <div class="form-row">
            <div class="form-group">
              <label>District</label>
              <input type="text" name="district" placeholder="District">
            </div>
            <div class="form-group">
              <label>Location / Micro-location</label>
              <input type="text" name="micro_location" placeholder="Specific location">
            </div>
          </div>

          <h4>Investment Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Total Project Cost (₹)</label>
              <input type="number" step="0.01" name="project_cost" placeholder="Total project cost">
            </div>
            <div class="form-group">
              <label>Investment Required (₹)</label>
              <input type="number" step="0.01" name="investment_required" placeholder="Investment required">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Profit Sharing Model</label>
              <input type="text" name="profit_sharing_model" placeholder="e.g., 60:40 to investor">
            </div>
            <div class="form-group">
              <label>Payback Period</label>
              <input type="text" name="payback_period" placeholder="e.g., 5 years">
            </div>
          </div>

          <h4>Project Status</h4>
          <div class="form-group">
            <label>Current Status</label>
            <input type="text" name="project_status" placeholder="Approvals in process / Construction started / etc.">
          </div>
          <div class="form-group">
            <label>Completion %</label>
            <div class="slider-container">
              <input type="range" name="completion_percent" min="0" max="100" step="5" value="0"
                     id="project-status" oninput="updateStatusValue(this.value)">
              <span id="status-value">0% Complete</span>
            </div>
          </div>

          <h4>Documents</h4>
          <div class="form-group">
            <label>Detailed Project Report (DPR)</label>
            <input type="file" name="dpr" accept=".pdf">
          </div>
          <div class="form-group">
            <label>Site Layout/Plan</label>
            <input type="file" name="layout_plan" accept=".pdf,.jpg,.png">
          </div>
          <div class="form-group">
            <label>Approvals & Permits</label>
            <input type="file" name="approvals[]" accept=".pdf,.jpg,.png" multiple>
          </div>
          <div class="form-group">
            <label>Financial Projections</label>
            <input type="file" name="financials" accept=".pdf,.xlsx">
          </div>

          <button type="submit" class="btn-primary">Submit Opportunity</button>
        </form>
      </div>
    </div>
<!-- ====== MY LISTINGS (Dynamic) ====== -->
<div class="seller-list-summary">
  <div class="seller-list-header">
    <h2>Your Uploaded Listings</h2>
    <p>Quick overview of land, building and investment opportunities you’ve listed.</p>
  </div>

  <!-- Tabs -->
  <div class="seller-tabs">
    <button class="seller-tab seller-tab-active" data-target="seller-land-panel">Land</button>
    <button class="seller-tab" data-target="seller-building-panel">Buildings</button>
    <button class="seller-tab" data-target="seller-investment-panel">Investments</button>
  </div>

  {{-- LAND TAB --}}
  <div id="seller-land-panel" class="seller-tab-panel seller-tab-panel-active">
    @forelse($sellerLandListings as $land)
      <div class="listing-card">
        <div class="listing-card-header">
          <div class="listing-code">
            {{ $land->property_code }} • {{ $land->district }}
          </div>
          @php
              $status = $land->status ?? 'normal';
              $statusLabel = strtoupper(str_replace('_', ' ', $status));
          @endphp
          <span class="listing-status
              @if($status === 'hot') listing-status-hot
              @elseif($status === 'urgent') listing-status-urgent
              @else listing-status-open @endif">
              {{ $statusLabel }}
          </span>
        </div>

        <div class="listing-card-body">
          <div class="listing-grid">
            <div>
              <div class="listing-label">Location</div>
              <div class="listing-value">
                {{ $land->exact_location ?: $land->landmark ?: '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Land Area</div>
              <div class="listing-value">
                {{ $land->land_area ?? '—' }} {{ $land->land_unit ?? '' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Price / {{ $land->land_unit ?? 'unit' }}</div>
              <div class="listing-value">
                {{ $land->expected_price_per_cent ? '₹' . number_format($land->expected_price_per_cent) : '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Road Frontage</div>
              <div class="listing-value">
                {{ $land->road_frontage ? $land->road_frontage . ' ft' : '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Zoning</div>
              <div class="listing-value">{{ $land->zoning_type ?? '—' }}</div>
            </div>
            <div>
              <div class="listing-label">Timeline</div>
              <div class="listing-value">{{ $land->sale_timeline ?? '—' }}</div>
            </div>
          </div>
        </div>

        <div class="listing-card-footer">
          <button class="listing-btn">View details</button>
          <button class="listing-btn">Edit status</button>
        </div>
      </div>
    @empty
      <p style="font-size:13px; color:#9ca3af; margin-top:8px;">
        No land listings yet. Use the “Land Sale Listing” form above to add your first property.
      </p>
    @endforelse
  </div>

  {{-- BUILDING TAB --}}
  <div id="seller-building-panel" class="seller-tab-panel">
    @forelse($sellerBuildingListings as $b)
      <div class="listing-card">
        <div class="listing-card-header">
          <div class="listing-code">
            {{ $b->property_code }} • {{ $b->district }}
          </div>
          <span class="listing-status listing-status-open">
            {{ strtoupper($b->status ?? 'NORMAL') }}
          </span>
        </div>

        <div class="listing-card-body">
          <div class="listing-grid">
            <div>
              <div class="listing-label">Type</div>
              <div class="listing-value">{{ $b->building_type ?? '—' }}</div>
            </div>
            <div>
              <div class="listing-label">Location</div>
              <div class="listing-value">
                {{ $b->area ? $b->area . ', ' : '' }}{{ $b->landmark ?? '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Built-up</div>
              <div class="listing-value">
                {{ $b->builtup_area ? number_format($b->builtup_area) . ' sqft' : '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Floors</div>
              <div class="listing-value">
                {{ $b->floors ? $b->floors . ' floors' : '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Total Price</div>
              <div class="listing-value">
                {{ $b->expected_price ? '₹' . number_format($b->expected_price) : '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Frontage</div>
              <div class="listing-value">
                {{ $b->road_frontage ? $b->road_frontage . ' ft' : '—' }}
              </div>
            </div>
          </div>
        </div>

        <div class="listing-card-footer">
          <button class="listing-btn">View details</button>
          <button class="listing-btn">Edit</button>
        </div>
      </div>
    @empty
      <p style="font-size:13px; color:#9ca3af; margin-top:8px;">
        No building listings yet. Use the “Building Sale Listing” form above.
      </p>
    @endforelse
  </div>

  {{-- INVESTMENT TAB --}}
  <div id="seller-investment-panel" class="seller-tab-panel">
    @forelse($sellerInvestmentListings as $inv)
      <div class="listing-card">
        <div class="listing-card-header">
          <div class="listing-code">
            {{ $inv->property_code }} • {{ $inv->project_name }}
          </div>
          <span class="listing-status listing-status-open">
            {{ strtoupper($inv->status ?? 'OPEN') }}
          </span>
        </div>

        <div class="listing-card-body">
          <div class="listing-grid">
            <div>
              <div class="listing-label">Project Type</div>
              <div class="listing-value">{{ $inv->project_type ?? '—' }}</div>
            </div>
            <div>
              <div class="listing-label">Location</div>
              <div class="listing-value">
                {{ $inv->district }} {{ $inv->micro_location ? '• ' . $inv->micro_location : '' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Project Cost</div>
              <div class="listing-value">
                {{ $inv->project_cost ? '₹' . number_format($inv->project_cost) : '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Investment Required</div>
              <div class="listing-value">
                {{ $inv->investment_required ? '₹' . number_format($inv->investment_required) : '—' }}
              </div>
            </div>
            <div>
              <div class="listing-label">Profit Sharing</div>
              <div class="listing-value">{{ $inv->profit_sharing_model ?? '—' }}</div>
            </div>
            <div>
              <div class="listing-label">Completion</div>
              <div class="listing-value">
                {{ $inv->completion_percent !== null ? $inv->completion_percent . '%' : '—' }}
              </div>
            </div>
          </div>
        </div>

        <div class="listing-card-footer">
          <button class="listing-btn">View details</button>
          <button class="listing-btn">Edit</button>
        </div>
      </div>
    @empty
      <p style="font-size:13px; color:#9ca3af; margin-top:8px;">
        No investment opportunities listed yet.
      </p>
    @endforelse
  </div>
</div>
<!-- ====== END MY LISTINGS ====== -->




  </div>
</div>


        {{-- INVESTMENT MODULE (display-only demo) --}}
        <div id="investment-module" class="dashboard-view">
          <div class="dashboard-header">
            <h1>Investment Module</h1>
            <p>Discover high-return investment opportunities</p>
          </div>

          <div class="module-content">
            <div class="investment-card">
              <div class="investment-header">
                <h3>Premium Villa Project - Kakkanad</h3>
                <div class="iss-score">
                  <span class="iss-label">ISS Score</span>
                  <span class="iss-value">92</span>
                </div>
              </div>
              <p class="investment-description">Luxury villa development in prime location with high ROI potential</p>
              <div class="investment-details">
                <div class="detail-item">
                  <span class="detail-label">Investment Required</span>
                  <span class="detail-value">₹50 Lakhs</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Expected Returns</span>
                  <span class="detail-value">18% per year</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Payback Period</span>
                  <span class="detail-value">5 years</span>
                </div>
              </div>
              <button class="btn-primary">View Details</button>
            </div>

            <div class="investment-card">
              <div class="investment-header">
                <h3>Commercial Complex - Kochi</h3>
                <div class="iss-score">
                  <span class="iss-label">ISS Score</span>
                  <span class="iss-value">85</span>
                </div>
              </div>
              <p class="investment-description">Strategic commercial development near IT hub</p>
              <div class="investment-details">
                <div class="detail-item">
                  <span class="detail-label">Investment Required</span>
                  <span class="detail-value">₹1 Crore</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Expected Returns</span>
                  <span class="detail-value">22% per year</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Payback Period</span>
                  <span class="detail-value">4 years</span>
                </div>
              </div>
              <button class="btn-primary">View Details</button>
            </div>
          </div>
        </div>
<!-- ====== MY LISTINGS (Caffeine-style cards) ====== -->

<!-- ====== END MY LISTINGS ====== -->


        
      </div>
    </div>
  </div>

  {{-- PROFILE COMPLETE MODAL --}}
  <div id="profile-complete-modal"
       class="profile-modal-overlay"
       style="{{ $profileIncomplete ? '' : 'display:none;' }}">
    <div class="profile-modal profile-modal-light">
      <div class="profile-modal-header">
        <div>
          <h2 class="profile-modal-title">Complete your profile</h2>
          <p class="profile-modal-subtitle">
            Add your basic details to get the best AI-powered matches.
          </p>
        </div>
      </div>

      <form method="POST" action="{{ route('profile.complete.update') }}" class="auth-form">
        @csrf

        <div class="form-group">
          <label for="modal-name">Full Name</label>
          <input
            type="text"
            id="modal-name"
            name="name"
            value="{{ old('name', $user?->name) }}"
            placeholder="Enter your full name"
            required
          >
          @error('name')
            <div class="input-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="modal-phone">Phone Number</label>
          <input
            type="tel"
            id="modal-phone"
            name="phone"
            value="{{ old('phone', $user?->phone) }}"
            placeholder="+91 1234567890"
            required
          >
          @error('phone')
            <div class="input-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="modal-location">Location</label>
          <input
            type="text"
            id="modal-location"
            name="location"
            value="{{ old('location', $user?->location) }}"
            placeholder="City / District / State"
            required
          >
          @error('location')
            <div class="input-error">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn-primary btn-full profile-modal-btn">
          Save & Continue
        </button>
      </form>
    </div>
  </div>

  {{-- JS --}}
  <script src="{{ asset('assets/script.js') }}"></script>
</body>
</html>
