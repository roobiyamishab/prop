{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AIPropMatch - AI-Powered Real Estate Intelligence</title>

  <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

@php
  $user = auth()->user();
  $profileIncomplete = !$user || empty($user->name) || empty($user->phone) || empty($user->location);

  // Group states & districts for JS usage (ONLY ONCE)
  $statesByCountry   = $states->groupBy('country_id');
  $districtsByState  = $cities->groupBy('state_id');
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

          <div class="sidebar-divider"></div>

          {{-- User Logout --}}
          <a href="javascript:void(0)" class="sidebar-item"
             onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();">
            <span class="sidebar-icon">🚪</span>
            <span>Logout</span>
          </a>

          <form id="user-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
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
                      <label>Preferred Country</label>
                      <select name="countries" id="land-country-select">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                          <option value="{{ $country->name }}" data-id="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Preferred State</label>
                      <select name="states" id="land-state-select">
                        <option value="">Select State</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Preferred District</label>
                      <select name="districts" id="land-district-select">
                        <option value="">Select District</option>
                      </select>
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
                    <textarea name="infra_preference" placeholder="Describe your infrastructure requirements..." rows="3"></textarea>
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
                      <label>Preferred Country</label>
                      <select name="building_country" id="building-country-select">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                          <option value="{{ $country->name }}" data-id="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Preferred State</label>
                      <select name="building_state" id="building-state-select">
                        <option value="">Select State</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Preferred District</label>
                      <select name="building_district" id="building-district-select">
                        <option value="">Select District</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Micro-location Preferences</label>
                      <input type="text" name="micro_locations" placeholder="Specific areas you prefer">
                    </div>
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
                      <label>Preferred Country</label>
                      <select name="investment_country" id="investment-country-select">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                          <option value="{{ $country->name }}" data-id="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Preferred State</label>
                      <select name="investment_state" id="investment-state-select">
                        <option value="">Select State</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Preferred District</label>
                      <select name="investment_district" id="investment-district-select">
                        <option value="">Select District</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Preferred Location(s)</label>
                      <input type="text" name="locations" placeholder="e.g., Kakkanad, Vyttila">
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
                    <input type="number" name="budget_range" placeholder="Enter budget">
                  </div>

                  <div class="form-group">
                    <label>Profit Expectation per Year (%)</label>
                    <input type="number" name="profit_expectation_year" placeholder="e.g., 15" min="0" max="100">
                  </div>

                  <button type="submit" class="btn-primary">Save Preferences</button>
                </form>
              </div>
            </div>

            {{-- Your Buying Requirements summary stays as you already have --}}
            {{-- (Keeping it untouched) --}}
            {!! '' !!}
          </div>
        </div>

        {{-- SELLER MODULE --}}
{{-- ========================= SELLER MODULE (FULL) ========================= --}}
<div id="seller-module" class="dashboard-view">
  <div class="dashboard-header">
    <h1>Seller Module</h1>
    <p>List your property</p>
  </div>

  @php
    // Group states & districts for JS usage (ONE place only)
    $statesByCountry  = $states->groupBy('country_id')->map(fn($g) => $g->values());
    $districtsByState = $cities->groupBy('state_id')->map(fn($g) => $g->values());
  @endphp

  <div class="module-content">

    {{-- ======================= 1) LAND SALE LISTING (CREATE) ======================= --}}
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
              <label>Country</label>
              <select name="country_id"
                      class="js-cascade-country"
                      data-scope="seller-land-create">
                <option value="">Select Country</option>
                @foreach($countries as $c)
                  <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
              {{-- optional: keep old text for backward compatibility --}}
              <input type="hidden" name="country" value="">
            </div>

            <div class="form-group">
              <label>State</label>
              <select name="state_id"
                      class="js-cascade-state"
                      data-scope="seller-land-create">
                <option value="">Select State</option>
              </select>
              <input type="hidden" name="state" value="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>District</label>
              <select name="district_id"
                      class="js-cascade-district"
                      data-scope="seller-land-create">
                <option value="">Select District</option>
              </select>
              <input type="hidden" name="district" value="">
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

    {{-- ======================= 2) BUILDING SALE LISTING (CREATE) ======================= --}}
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
              <label>Country</label>
              <select name="country_id"
                      class="js-cascade-country"
                      data-scope="seller-building-create">
                <option value="">Select Country</option>
                @foreach($countries as $c)
                  <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
              <input type="hidden" name="country" value="">
            </div>

            <div class="form-group">
              <label>State</label>
              <select name="state_id"
                      class="js-cascade-state"
                      data-scope="seller-building-create">
                <option value="">Select State</option>
              </select>
              <input type="hidden" name="state" value="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>District</label>
              <select name="district_id"
                      class="js-cascade-district"
                      data-scope="seller-building-create">
                <option value="">Select District</option>
              </select>
              <input type="hidden" name="district" value="">
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

    {{-- ======================= 3) INVESTMENT OPPORTUNITY (CREATE) ======================= --}}
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
            <textarea name="project_description" rows="4"
              placeholder="Detailed description of the investment opportunity..."></textarea>
          </div>

          <h4>Location</h4>

          <div class="form-row">
            <div class="form-group">
              <label>Country</label>
              <select name="country_id"
                      class="js-cascade-country"
                      data-scope="seller-investment-create">
                <option value="">Select Country</option>
                @foreach($countries as $c)
                  <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
              <input type="hidden" name="country" value="">
            </div>

            <div class="form-group">
              <label>State</label>
              <select name="state_id"
                      class="js-cascade-state"
                      data-scope="seller-investment-create">
                <option value="">Select State</option>
              </select>
              <input type="hidden" name="state" value="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>District</label>
              <select name="district_id"
                      class="js-cascade-district"
                      data-scope="seller-investment-create">
                <option value="">Select District</option>
              </select>
              <input type="hidden" name="district" value="">
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

    {{-- ======================= SELLER LISTINGS (TABS) ======================= --}}
    <div class="seller-list-summary">
      <div class="seller-list-header">
        <h2>Your Uploaded Listings</h2>
        <p>Quick overview of land, building and investment opportunities you’ve listed.</p>
      </div>

      <div class="seller-tabs">
        <button class="seller-tab seller-tab-active" data-target="seller-land-panel">Land</button>
        <button class="seller-tab" data-target="seller-building-panel">Buildings</button>
        <button class="seller-tab" data-target="seller-investment-panel">Investments</button>
      </div>

      {{-- ======================= LAND LISTINGS ======================= --}}
      <div id="seller-land-panel" class="seller-tab-panel seller-tab-panel-active">
        @forelse($sellerLandListings as $land)
          @php
            $status = strtolower($land->status ?? 'normal');
            $statusLabel = strtoupper(str_replace('_', ' ', $status));

            $countryName  = $land->country?->name ?? $land->country ?? null;
            $stateName    = $land->state?->name ?? $land->state ?? null;
            $districtName = $land->districtRel?->name ?? $land->district?->name ?? $land->district ?? null;
          @endphp

          <div class="listing-card">
            <div class="listing-card-header">
              <div class="listing-code">
                {{ $land->property_code }}
                • {{ $districtName ?: '—' }}
                @if(!empty($stateName)) • {{ $stateName }} @endif
                @if(!empty($countryName)) • {{ $countryName }} @endif
              </div>

              <span class="listing-status
                @if($status === 'hot') listing-status-hot
                @elseif($status === 'urgent') listing-status-urgent
                @elseif($status === 'sold') listing-status-sold
                @elseif($status === 'closed') listing-status-closed
                @else listing-status-open @endif">
                {{ $statusLabel }}
              </span>
            </div>

            <div class="listing-card-body">
              <div class="listing-grid">
                <div>
                  <div class="listing-label">Location</div>
                  <div class="listing-value">{{ $land->exact_location ?: $land->landmark ?: '—' }}</div>
                </div>
                <div>
                  <div class="listing-label">Land Area</div>
                  <div class="listing-value">{{ $land->land_area ?? '—' }} {{ $land->land_unit ?? '' }}</div>
                </div>
                <div>
                  <div class="listing-label">Price / {{ $land->land_unit ?? 'unit' }}</div>
                  <div class="listing-value">
                    {{ $land->expected_price_per_cent ? '₹' . number_format($land->expected_price_per_cent) : '—' }}
                  </div>
                </div>
                <div>
                  <div class="listing-label">Road Frontage</div>
                  <div class="listing-value">{{ $land->road_frontage ? $land->road_frontage . ' ft' : '—' }}</div>
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
              <button type="button"
                      class="listing-btn js-edit-status-btn"
                      data-url="{{ route('seller.land.status.update', $land->id) }}"
                      data-current-status="{{ $status }}">
                Edit status
              </button>

              <button type="button"
                      class="listing-btn js-edit-details-btn"
                      data-modal-id="edit-land-modal-{{ $land->id }}">
                Edit details
              </button>
            </div>
          </div>

          {{-- =================== LAND EDIT MODAL (WITH CASCADE + IDs) =================== --}}
         {{-- =================== LAND EDIT MODAL (FULL + CASCADE + FILES + IDs) =================== --}}
<div id="edit-land-modal-{{ $land->id }}" class="details-modal hidden">
  <div class="details-modal-backdrop"></div>

  <div class="details-modal-content">
    <div class="details-modal-header">
      <h3>Edit Land – {{ $land->property_code }}</h3>
      <button type="button" class="details-modal-close" aria-label="Close">&times;</button>
    </div>

    <form method="POST"
          action="{{ route('seller.land.update', $land->id) }}"
          enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <div class="details-modal-body">
        <div class="details-grid">

          {{-- ================= LOCATION ================= --}}
          <h4 class="form-section-title">Location Details</h4>

          <div class="form-group">
            <label class="form-label">Country</label>
            <select name="country_id"
                    class="form-input js-cascade-country"
                    data-scope="seller-land-edit-{{ $land->id }}">
              <option value="">Select Country</option>
              @foreach($countries as $c)
                <option value="{{ $c->id }}"
                  {{ (int)old('country_id', $land->country_id) === (int)$c->id ? 'selected' : '' }}>
                  {{ $c->name }}
                </option>
              @endforeach
            </select>
            {{-- optional legacy string (only if you still store it) --}}
            <input type="hidden" name="country" value="{{ old('country', $land->country) }}">
          </div>

          <div class="form-group">
            <label class="form-label">State</label>
            <select name="state_id"
                    class="form-input js-cascade-state"
                    data-scope="seller-land-edit-{{ $land->id }}"
                    data-selected="{{ old('state_id', $land->state_id) }}">
              <option value="">Select State</option>
            </select>
            <input type="hidden" name="state" value="{{ old('state', $land->state) }}">
          </div>

          <div class="form-group">
            <label class="form-label">District</label>
            <select name="district_id"
                    class="form-input js-cascade-district"
                    data-scope="seller-land-edit-{{ $land->id }}"
                    data-selected="{{ old('district_id', $land->district_id) }}">
              <option value="">Select District</option>
            </select>
            <input type="hidden" name="district" value="{{ old('district', $land->district) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Taluk</label>
            <input type="text" name="taluk" class="form-input"
                   value="{{ old('taluk', $land->taluk) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Village</label>
            <input type="text" name="village" class="form-input"
                   value="{{ old('village', $land->village) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Exact Location</label>
            <input type="text" name="exact_location" class="form-input"
                   value="{{ old('exact_location', $land->exact_location) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Landmark</label>
            <input type="text" name="landmark" class="form-input"
                   value="{{ old('landmark', $land->landmark) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Survey No</label>
            <input type="text" name="survey_no" class="form-input"
                   value="{{ old('survey_no', $land->survey_no) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Google Maps Link</label>
            <input type="url" name="google_map_link" class="form-input"
                   value="{{ old('google_map_link', $land->google_map_link) }}">
          </div>

          {{-- ================= LAND / PROPERTY ================= --}}
          <h4 class="form-section-title">Property Details</h4>

          <div class="form-group">
            <label class="form-label">Land Area</label>
            <input type="number" step="0.01" name="land_area" class="form-input"
                   value="{{ old('land_area', $land->land_area) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Unit</label>
            @php $unit = old('land_unit', $land->land_unit ?? 'cent'); @endphp
            <select name="land_unit" class="form-input">
              <option value="cent" {{ $unit==='cent' ? 'selected' : '' }}>Cent</option>
              <option value="acre" {{ $unit==='acre' ? 'selected' : '' }}>Acre</option>
              <option value="sqft" {{ $unit==='sqft' ? 'selected' : '' }}>Sq.ft</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Proximity</label>
            <input type="text" name="proximity" class="form-input"
                   value="{{ old('proximity', $land->proximity) }}"
                   placeholder="NH / SH / Town / Others">
          </div>

          <div class="form-group">
            <label class="form-label">Road Frontage (ft)</label>
            <input type="number" name="road_frontage" class="form-input"
                   value="{{ old('road_frontage', $land->road_frontage) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Plot Shape</label>
            @php $shape = old('plot_shape', $land->plot_shape); @endphp
            <select name="plot_shape" class="form-input">
              <option value="">Select</option>
              <option value="Square" {{ $shape==='Square' ? 'selected' : '' }}>Square</option>
              <option value="Rectangle" {{ $shape==='Rectangle' ? 'selected' : '' }}>Rectangle</option>
              <option value="Irregular" {{ $shape==='Irregular' ? 'selected' : '' }}>Irregular</option>
            </select>
          </div>

          {{-- ================= ZONING & LEGAL ================= --}}
          <h4 class="form-section-title">Zoning & Legal</h4>

          <div class="form-group">
            <label class="form-label">Zoning Type</label>
            <input type="text" name="zoning_type" class="form-input"
                   value="{{ old('zoning_type', $land->zoning_type) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Ownership Type</label>
            <input type="text" name="ownership_type" class="form-input"
                   value="{{ old('ownership_type', $land->ownership_type) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Restrictions</label>
            <textarea name="restrictions" class="form-input" rows="3"
              placeholder="Any legal restrictions...">{{ old('restrictions', $land->restrictions) }}</textarea>
          </div>

          {{-- ================= PRICING ================= --}}
          <h4 class="form-section-title">Pricing</h4>

          <div class="form-group">
            <label class="form-label">Expected Price per Unit (₹)</label>
            <input type="number" step="0.01" name="expected_price_per_cent" class="form-input"
                   value="{{ old('expected_price_per_cent', $land->expected_price_per_cent) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Negotiability</label>
            @php $neg = old('negotiability', $land->negotiability); @endphp
            <select name="negotiability" class="form-input">
              <option value="">Select</option>
              <option value="Negotiable" {{ $neg==='Negotiable' ? 'selected' : '' }}>Negotiable</option>
              <option value="Slightly Negotiable" {{ $neg==='Slightly Negotiable' ? 'selected' : '' }}>Slightly Negotiable</option>
              <option value="Fixed" {{ $neg==='Fixed' ? 'selected' : '' }}>Fixed</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Expected Advance (%)</label>
            <input type="number" name="expected_advance_pct" class="form-input" min="0" max="100"
                   value="{{ old('expected_advance_pct', $land->expected_advance_pct) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Minimum Offer Expected (₹)</label>
            <input type="number" step="0.01" name="min_offer_expected" class="form-input"
                   value="{{ old('min_offer_expected', $land->min_offer_expected) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Market Value Info</label>
            <textarea name="market_value_info" class="form-input" rows="3"
              placeholder="Any market value notes...">{{ old('market_value_info', $land->market_value_info) }}</textarea>
          </div>

          {{-- ================= CONDITION ================= --}}
          <h4 class="form-section-title">Condition</h4>

          <div class="form-group">
            <label class="form-label">Land Type</label>
            <input type="text" name="land_type" class="form-input"
                   value="{{ old('land_type', $land->land_type) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Current Use</label>
            <input type="text" name="current_use" class="form-input"
                   value="{{ old('current_use', $land->current_use) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Electricity</label>
            @php $el = (int)old('electricity', $land->electricity ? 1 : 0); @endphp
            <select name="electricity" class="form-input">
              <option value="1" {{ $el===1 ? 'selected' : '' }}>Available</option>
              <option value="0" {{ $el===0 ? 'selected' : '' }}>Not Available</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Water</label>
            @php $wa = (int)old('water', $land->water ? 1 : 0); @endphp
            <select name="water" class="form-input">
              <option value="1" {{ $wa===1 ? 'selected' : '' }}>Available</option>
              <option value="0" {{ $wa===0 ? 'selected' : '' }}>Not Available</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Drainage</label>
            @php $dr = (int)old('drainage', $land->drainage ? 1 : 0); @endphp
            <select name="drainage" class="form-input">
              <option value="1" {{ $dr===1 ? 'selected' : '' }}>Available</option>
              <option value="0" {{ $dr===0 ? 'selected' : '' }}>Not Available</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Compound Wall</label>
            @php $cw = old('compound_wall', $land->compound_wall ? 1 : 0); @endphp
            <select name="compound_wall" class="form-input">
              <option value="1" {{ (int)$cw===1 ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ (int)$cw===0 ? 'selected' : '' }}>No</option>
            </select>
          </div>

          {{-- ================= SALE TIMELINE ================= --}}
          <h4 class="form-section-title">Sale Timeline</h4>

          <div class="form-group">
            <label class="form-label">Sale Timeline</label>
            <input type="text" name="sale_timeline" class="form-input"
                   value="{{ old('sale_timeline', $land->sale_timeline) }}">
          </div>

          {{-- ================= DOCUMENTS & MEDIA ================= --}}
          <h4 class="form-section-title">Documents & Media</h4>

          <div class="form-group">
            <label class="form-label">Land Tax Receipt</label>
            <input type="file" name="land_tax_receipt" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($land->documents['land_tax_receipt']))
              <small style="color:#6b7280;">Existing: {{ basename($land->documents['land_tax_receipt']) }}</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Location Sketch</label>
            <input type="file" name="location_sketch" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($land->documents['location_sketch']))
              <small style="color:#6b7280;">Existing: {{ basename($land->documents['location_sketch']) }}</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Add Photos (multi)</label>
            <input type="file" name="photos[]" class="form-input" accept="image/*" multiple>
            @if(!empty($land->photos) && is_array($land->photos))
              <small style="color:#6b7280;">Existing: {{ count($land->photos) }} photo(s)</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Replace Video (optional)</label>
            <input type="file" name="video" class="form-input" accept="video/*">
            @if(!empty($land->videos) && is_array($land->videos))
              <small style="color:#6b7280;">Existing: {{ count($land->videos) }} video file(s)</small>
            @endif
          </div>

        </div>
      </div>

      <div class="details-modal-footer">
        <button type="button" class="listing-btn listing-btn-ghost details-modal-cancel">Cancel</button>
        <button type="submit" class="listing-btn listing-btn-primary">Save changes</button>
      </div>
    </form>
  </div>
</div>

        @empty
          <p style="font-size:13px; color:#9ca3af; margin-top:8px;">No land listings yet.</p>
        @endforelse
      </div>

      {{-- ======================= BUILDING LISTINGS ======================= --}}
      <div id="seller-building-panel" class="seller-tab-panel">
        @forelse($sellerBuildingListings as $b)
          @php
            $status = strtolower($b->status ?? 'normal');
            $statusLabel = strtoupper(str_replace('_', ' ', $status));

            $countryName  = $b->country?->name ?? $b->country ?? null;
            $stateName    = $b->state?->name ?? $b->state ?? null;
            $districtName = $b->districtRel?->name ?? $b->district?->name ?? $b->district ?? null;
          @endphp

          <div class="listing-card">
            <div class="listing-card-header">
              <div class="listing-code">
                {{ $b->property_code }}
                • {{ $districtName ?: '—' }}
                @if(!empty($stateName)) • {{ $stateName }} @endif
                @if(!empty($countryName)) • {{ $countryName }} @endif
              </div>

              <span class="listing-status
                @if($status === 'hot') listing-status-hot
                @elseif($status === 'urgent') listing-status-urgent
                @elseif($status === 'sold') listing-status-sold
                @elseif($status === 'closed') listing-status-closed
                @else listing-status-open @endif">
                {{ $statusLabel }}
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
                  <div class="listing-value">{{ $b->area ? $b->area . ', ' : '' }}{{ $b->landmark ?? '—' }}</div>
                </div>
                <div>
                  <div class="listing-label">Built-up</div>
                  <div class="listing-value">{{ $b->builtup_area ? number_format($b->builtup_area) . ' sqft' : '—' }}</div>
                </div>
                <div>
                  <div class="listing-label">Floors</div>
                  <div class="listing-value">{{ $b->floors ? $b->floors . ' floors' : '—' }}</div>
                </div>
                <div>
                  <div class="listing-label">Total Price</div>
                  <div class="listing-value">{{ $b->expected_price ? '₹' . number_format($b->expected_price) : '—' }}</div>
                </div>
              </div>
            </div>

            <div class="listing-card-footer">
              <button type="button"
                      class="listing-btn js-edit-status-btn"
                      data-url="{{ route('seller.building.status.update', $b->id) }}"
                      data-current-status="{{ $status }}">
                Edit status
              </button>

              <button type="button"
                      class="listing-btn js-edit-details-btn"
                      data-modal-id="edit-building-modal-{{ $b->id }}">
                Edit details
              </button>
            </div>
          </div>

          {{-- =================== BUILDING EDIT MODAL (WITH CASCADE + IDs) =================== --}}
         {{-- =================== BUILDING EDIT MODAL (FULL + CASCADE + FILES) =================== --}}
<div id="edit-building-modal-{{ $b->id }}" class="details-modal hidden">
  <div class="details-modal-backdrop"></div>

  <div class="details-modal-content">
    <div class="details-modal-header">
      <h3>Edit Building – {{ $b->property_code }}</h3>
      <button type="button" class="details-modal-close" aria-label="Close">&times;</button>
    </div>

    <form method="POST"
          action="{{ route('seller.building.update', $b->id) }}"
          enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <div class="details-modal-body">
        <div class="details-grid">

          {{-- ================= LOCATION ================= --}}
          <h4 class="form-section-title">Location Details</h4>

          <div class="form-group">
            <label class="form-label">Country</label>
            <select name="country_id"
                    class="form-input js-cascade-country"
                    data-scope="seller-building-edit-{{ $b->id }}">
              <option value="">Select Country</option>
              @foreach($countries as $c)
                <option value="{{ $c->id }}"
                  {{ (int)old('country_id', $b->country_id) === (int)$c->id ? 'selected' : '' }}>
                  {{ $c->name }}
                </option>
              @endforeach
            </select>

            {{-- optional backward compatible string --}}
            <input type="hidden" name="country" value="{{ old('country', $b->country) }}">
          </div>

          <div class="form-group">
            <label class="form-label">State</label>
            <select name="state_id"
                    class="form-input js-cascade-state"
                    data-scope="seller-building-edit-{{ $b->id }}"
                    data-selected="{{ old('state_id', $b->state_id) }}">
              <option value="">Select State</option>
            </select>

            <input type="hidden" name="state" value="{{ old('state', $b->state) }}">
          </div>

          <div class="form-group">
            <label class="form-label">District</label>
            <select name="district_id"
                    class="form-input js-cascade-district"
                    data-scope="seller-building-edit-{{ $b->id }}"
                    data-selected="{{ old('district_id', $b->district_id) }}">
              <option value="">Select District</option>
            </select>

            <input type="hidden" name="district" value="{{ old('district', $b->district) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Area / Town</label>
            <input type="text" name="area" class="form-input"
                   value="{{ old('area', $b->area) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Street Name</label>
            <input type="text" name="street_name" class="form-input"
                   value="{{ old('street_name', $b->street_name) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Landmark</label>
            <input type="text" name="landmark" class="form-input"
                   value="{{ old('landmark', $b->landmark) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Google Maps Link</label>
            <input type="url" name="map_link" class="form-input"
                   value="{{ old('map_link', $b->map_link) }}">
          </div>

          {{-- ================= BUILDING DETAILS ================= --}}
          <h4 class="form-section-title">Building Details</h4>

          <div class="form-group">
            <label class="form-label">Building Type</label>
            <select name="building_type" class="form-input">
              @php $bt = old('building_type', $b->building_type); @endphp
              @foreach(['Commercial','Office','Retail','Apartment','Villa','Mixed-Use','Hotel','Hospital','Warehouse'] as $type)
                <option value="{{ $type }}" {{ $bt === $type ? 'selected' : '' }}>{{ $type }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Total Plot Area (sqft)</label>
            <input type="number" step="0.01" name="total_plot_area" class="form-input"
                   value="{{ old('total_plot_area', $b->total_plot_area) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Built-up Area (sqft)</label>
            <input type="number" step="0.01" name="builtup_area" class="form-input"
                   value="{{ old('builtup_area', $b->builtup_area) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Floors</label>
            <input type="number" name="floors" class="form-input"
                   value="{{ old('floors', $b->floors) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Construction Year</label>
            <input type="number" name="construction_year" class="form-input"
                   value="{{ old('construction_year', $b->construction_year) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Lift Available</label>
            @php $lift = (string) old('lift_available', $b->lift_available ? 1 : 0); @endphp
            <select name="lift_available" class="form-input">
              <option value="1" {{ $lift === '1' ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ $lift === '0' ? 'selected' : '' }}>No</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Parking Slots</label>
            <input type="number" name="parking_slots" class="form-input"
                   value="{{ old('parking_slots', $b->parking_slots) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Road Frontage (ft)</label>
            <input type="number" name="road_frontage" class="form-input"
                   value="{{ old('road_frontage', $b->road_frontage) }}">
          </div>

          {{-- ================= PRICING ================= --}}
          <h4 class="form-section-title">Pricing</h4>

          <div class="form-group">
            <label class="form-label">Expected Total Price (₹)</label>
            <input type="number" step="0.01" name="expected_price" class="form-input"
                   value="{{ old('expected_price', $b->expected_price) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Negotiability</label>
            @php $neg = old('negotiability', $b->negotiability); @endphp
            <select name="negotiability" class="form-input">
              <option value="Negotiable" {{ $neg === 'Negotiable' ? 'selected' : '' }}>Negotiable</option>
              <option value="Slightly Negotiable" {{ $neg === 'Slightly Negotiable' ? 'selected' : '' }}>Slightly Negotiable</option>
              <option value="Fixed" {{ $neg === 'Fixed' ? 'selected' : '' }}>Fixed</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Sell Timeline</label>
            @php $tl = old('sell_timeline', $b->sell_timeline); @endphp
            <select name="sell_timeline" class="form-input">
              <option value="Urgent (Within 1 month)" {{ $tl === 'Urgent (Within 1 month)' ? 'selected' : '' }}>Urgent (Within 1 month)</option>
              <option value="Normal (1-3 months)" {{ $tl === 'Normal (1-3 months)' ? 'selected' : '' }}>Normal (1-3 months)</option>
              <option value="Flexible" {{ $tl === 'Flexible' ? 'selected' : '' }}>Flexible</option>
            </select>
          </div>

          {{-- ================= DOCUMENTS & MEDIA ================= --}}
          <h4 class="form-section-title">Documents & Media (Replace / Add)</h4>

          <div class="form-group">
            <label class="form-label">Building Permit (optional)</label>
            <input type="file" name="building_permit" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($b->documents['building_permit']))
              <small style="color:#6b7280;">Existing: {{ basename($b->documents['building_permit']) }}</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Completion Certificate (optional)</label>
            <input type="file" name="completion_certificate" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($b->documents['completion_certificate']))
              <small style="color:#6b7280;">Existing: {{ basename($b->documents['completion_certificate']) }}</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Ownership Documents (Add more)</label>
            <input type="file" name="ownership_documents[]" class="form-input" multiple accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($b->documents['ownership_documents']) && is_array($b->documents['ownership_documents']))
              <small style="color:#6b7280;">Existing: {{ count($b->documents['ownership_documents']) }} file(s)</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Photos (Add more)</label>
            <input type="file" name="photos[]" class="form-input" multiple accept="image/*">
            @if(!empty($b->photos) && is_array($b->photos))
              <small style="color:#6b7280;">Existing: {{ count($b->photos) }} photo(s)</small>
            @endif
          </div>

        </div>
      </div>

      <div class="details-modal-footer">
        <button type="button" class="listing-btn listing-btn-ghost details-modal-cancel">Cancel</button>
        <button type="submit" class="listing-btn listing-btn-primary">Save changes</button>
      </div>

    </form>
  </div>
</div>

        @empty
          <p style="font-size:13px; color:#9ca3af; margin-top:8px;">No building listings yet.</p>
        @endforelse
      </div>

      {{-- ======================= INVESTMENT LISTINGS ======================= --}}
      <div id="seller-investment-panel" class="seller-tab-panel">
        @forelse($sellerInvestmentListings as $inv)
          @php
            $status = strtolower($inv->status ?? 'open');
            $statusLabel = strtoupper(str_replace('_', ' ', $status));

            $countryName  = $inv->country?->name ?? $inv->country ?? null;
            $stateName    = $inv->state?->name ?? $inv->state ?? null;
            $districtName = $inv->districtRel?->name ?? $inv->district?->name ?? $inv->district ?? null;
          @endphp

          <div class="listing-card">
            <div class="listing-card-header">
              <div class="listing-code">
                {{ $inv->property_code }} • {{ $inv->project_name }}
                @if(!empty($districtName)) • {{ $districtName }} @endif
                @if(!empty($stateName)) • {{ $stateName }} @endif
                @if(!empty($countryName)) • {{ $countryName }} @endif
              </div>

              <span class="listing-status
                @if($status === 'hot') listing-status-hot
                @elseif($status === 'urgent') listing-status-urgent
                @elseif($status === 'sold') listing-status-sold
                @elseif($status === 'closed') listing-status-closed
                @else listing-status-open @endif">
                {{ $statusLabel }}
              </span>
            </div>

            <div class="listing-card-body">
              <div class="listing-grid">
                <div>
                  <div class="listing-label">Project Type</div>
                  <div class="listing-value">{{ $inv->project_type ?? '—' }}</div>
                </div>
                <div>
                  <div class="listing-label">Micro Location</div>
                  <div class="listing-value">{{ $inv->micro_location ?? '—' }}</div>
                </div>
                <div>
                  <div class="listing-label">Project Cost</div>
                  <div class="listing-value">{{ $inv->project_cost ? '₹' . number_format($inv->project_cost) : '—' }}</div>
                </div>
                <div>
                  <div class="listing-label">Investment Required</div>
                  <div class="listing-value">{{ $inv->investment_required ? '₹' . number_format($inv->investment_required) : '—' }}</div>
                </div>
              </div>
            </div>

            <div class="listing-card-footer">
              <button type="button"
                      class="listing-btn js-edit-status-btn"
                      data-url="{{ route('seller.investment.status.update', $inv->id) }}"
                      data-current-status="{{ $status }}">
                Edit status
              </button>

              <button type="button"
                      class="listing-btn js-edit-details-btn"
                      data-modal-id="edit-investment-modal-{{ $inv->id }}">
                Edit details
              </button>
            </div>
          </div>

          {{-- =================== INVESTMENT EDIT MODAL (WITH CASCADE + IDs) =================== --}}
          {{-- =================== INVESTMENT EDIT MODAL (FULL + CASCADE + FILES + IDs) =================== --}}
<div id="edit-investment-modal-{{ $inv->id }}" class="details-modal hidden">
  <div class="details-modal-backdrop"></div>

  <div class="details-modal-content">
    <div class="details-modal-header">
      <h3>Edit Investment – {{ $inv->property_code }}</h3>
      <button type="button" class="details-modal-close" aria-label="Close">&times;</button>
    </div>

    <form method="POST"
          action="{{ route('seller.investment.update', $inv->id) }}"
          enctype="multipart/form-data">
      @csrf
      @method('PATCH')

      <div class="details-modal-body">
        <div class="details-grid">

          {{-- ================= LOCATION ================= --}}
          <h4 class="form-section-title">Location</h4>

          <div class="form-group">
            <label class="form-label">Country</label>
            <select name="country_id"
                    class="form-input js-cascade-country"
                    data-scope="seller-investment-edit-{{ $inv->id }}">
              <option value="">Select Country</option>
              @foreach($countries as $c)
                <option value="{{ $c->id }}"
                  {{ (int)old('country_id', $inv->country_id) === (int)$c->id ? 'selected' : '' }}>
                  {{ $c->name }}
                </option>
              @endforeach
            </select>
            {{-- optional legacy string --}}
            <input type="hidden" name="country" value="{{ old('country', $inv->country) }}">
          </div>

          <div class="form-group">
            <label class="form-label">State</label>
            <select name="state_id"
                    class="form-input js-cascade-state"
                    data-scope="seller-investment-edit-{{ $inv->id }}"
                    data-selected="{{ old('state_id', $inv->state_id) }}">
              <option value="">Select State</option>
            </select>
            <input type="hidden" name="state" value="{{ old('state', $inv->state) }}">
          </div>

          <div class="form-group">
            <label class="form-label">District</label>
            <select name="district_id"
                    class="form-input js-cascade-district"
                    data-scope="seller-investment-edit-{{ $inv->id }}"
                    data-selected="{{ old('district_id', $inv->district_id) }}">
              <option value="">Select District</option>
            </select>
            <input type="hidden" name="district" value="{{ old('district', $inv->district) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Micro Location</label>
            <input type="text" name="micro_location" class="form-input"
                   value="{{ old('micro_location', $inv->micro_location) }}">
          </div>

          {{-- ================= PROJECT OVERVIEW ================= --}}
          <h4 class="form-section-title">Project Overview</h4>

          <div class="form-group">
            <label class="form-label">Project Name</label>
            <input type="text" name="project_name" class="form-input"
                   value="{{ old('project_name', $inv->project_name) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Project Type</label>
            <select name="project_type" class="form-input">
              @php $pt = old('project_type', $inv->project_type); @endphp
              @foreach(['Land development','Rental Property','Commercial Complex','Residential Project','Mixed Development'] as $type)
                <option value="{{ $type }}" {{ $pt === $type ? 'selected' : '' }}>
                  {{ $type }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- NOTE: only include this textarea IF your DB column exists --}}
          @if(\Illuminate\Support\Facades\Schema::hasColumn('seller_investment_listings','project_description'))
            <div class="form-group">
              <label class="form-label">Project Description</label>
              <textarea name="project_description" class="form-input" rows="4"
                placeholder="Describe the opportunity...">{{ old('project_description', $inv->project_description) }}</textarea>
            </div>
          @endif

          {{-- ================= INVESTMENT DETAILS ================= --}}
          <h4 class="form-section-title">Investment Details</h4>

          <div class="form-group">
            <label class="form-label">Total Project Cost (₹)</label>
            <input type="number" step="0.01" name="project_cost" class="form-input"
                   value="{{ old('project_cost', $inv->project_cost) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Investment Required (₹)</label>
            <input type="number" step="0.01" name="investment_required" class="form-input"
                   value="{{ old('investment_required', $inv->investment_required) }}">
          </div>

          <div class="form-group">
            <label class="form-label">Profit Sharing Model</label>
            <input type="text" name="profit_sharing_model" class="form-input"
                   value="{{ old('profit_sharing_model', $inv->profit_sharing_model) }}"
                   placeholder="e.g., 60:40 investor">
          </div>

          <div class="form-group">
            <label class="form-label">Payback Period</label>
            <input type="text" name="payback_period" class="form-input"
                   value="{{ old('payback_period', $inv->payback_period) }}"
                   placeholder="e.g., 5 years">
          </div>

          {{-- ================= PROJECT STATUS ================= --}}
          <h4 class="form-section-title">Project Status</h4>

          <div class="form-group">
            <label class="form-label">Current Status</label>
            <input type="text" name="project_status" class="form-input"
                   value="{{ old('project_status', $inv->project_status) }}"
                   placeholder="Approvals / Construction started / etc.">
          </div>

          <div class="form-group">
            <label class="form-label">Completion %</label>
            @php $cp = (int) old('completion_percent', $inv->completion_percent ?? 0); @endphp
            <input type="number" name="completion_percent" class="form-input" min="0" max="100"
                   value="{{ $cp }}">
            <small style="color:#6b7280;">Enter 0–100</small>
          </div>

          {{-- ================= DOCUMENTS ================= --}}
          <h4 class="form-section-title">Documents (Replace / Add)</h4>

          <div class="form-group">
            <label class="form-label">DPR (PDF)</label>
            <input type="file" name="dpr" class="form-input" accept=".pdf">
            @if(!empty($inv->documents['dpr']))
              <small style="color:#6b7280;">Existing: {{ basename($inv->documents['dpr']) }}</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Site Layout / Plan</label>
            <input type="file" name="layout_plan" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($inv->documents['layout_plan']))
              <small style="color:#6b7280;">Existing: {{ basename($inv->documents['layout_plan']) }}</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Approvals (Add more)</label>
            <input type="file" name="approvals[]" class="form-input" multiple accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($inv->documents['approvals']) && is_array($inv->documents['approvals']))
              <small style="color:#6b7280;">Existing: {{ count($inv->documents['approvals']) }} file(s)</small>
            @endif
          </div>

          <div class="form-group">
            <label class="form-label">Financial Projections</label>
            <input type="file" name="financials" class="form-input" accept=".pdf,.xlsx,.xls">
            @if(!empty($inv->documents['financials']))
              <small style="color:#6b7280;">Existing: {{ basename($inv->documents['financials']) }}</small>
            @endif
          </div>

        </div>
      </div>

      <div class="details-modal-footer">
        <button type="button" class="listing-btn listing-btn-ghost details-modal-cancel">Cancel</button>
        <button type="submit" class="listing-btn listing-btn-primary">Save changes</button>
      </div>

    </form>
  </div>
</div>

        @empty
          <p style="font-size:13px; color:#9ca3af; margin-top:8px;">No investment opportunities yet.</p>
        @endforelse
      </div>
    </div>

    {{-- ======================= SHARED STATUS EDIT MODAL ======================= --}}
    <div id="statusEditModal" class="status-modal hidden">
      <div class="status-modal-backdrop"></div>

      <div class="status-modal-content">
        <div class="status-modal-header">
          <h3>Edit Listing Status</h3>
          <button type="button" class="status-modal-close" aria-label="Close">&times;</button>
        </div>

        <form id="statusEditForm" method="POST">
          @csrf
          @method('PATCH')

          <div class="status-modal-body">
            <label for="statusSelect" class="status-label">Status</label>
            <select id="statusSelect" name="status" class="status-select">
              <option value="normal">Normal / Open</option>
              <option value="hot">Hot</option>
              <option value="urgent">Urgent</option>
              <option value="sold">Sold</option>
              <option value="closed">Closed</option>
            </select>

            <label for="statusNote" class="status-label" style="margin-top:12px;">
              Note / Internal Remark (optional)
            </label>
            <textarea id="statusNote"
                      name="status_note"
                      class="status-textarea"
                      placeholder="Eg: Deal closed on 26/11/2025 with buyer code BUY-XXXX"></textarea>
          </div>

          <div class="status-modal-footer">
            <button type="button" class="listing-btn listing-btn-ghost status-modal-cancel">Cancel</button>
            <button type="submit" class="listing-btn listing-btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

  {{-- JS --}}
  <script src="{{ asset('assets/script.js') }}"></script>

  {{-- ✅ ONE SINGLE CASCADE SCRIPT FOR ALL SELECTS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const statesByCountry  = @json($statesByCountry ?? []);
  const districtsByState = @json($districtsByState ?? []);

  function resetSelect(selectEl, placeholder) {
    if (!selectEl) return;
    selectEl.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = placeholder;
    selectEl.appendChild(opt);
  }

  function findGroup(scope) {
    const country  = document.querySelector('.js-cascade-country[data-scope="'+scope+'"]');
    const state    = document.querySelector('.js-cascade-state[data-scope="'+scope+'"]');
    const district = document.querySelector('.js-cascade-district[data-scope="'+scope+'"]');
    return { country, state, district };
  }

  function populateStates(scope, countryId, selectedStateId) {
    const { state, district } = findGroup(scope);
    if (!state || !district) return;

    resetSelect(state, 'Select State');
    resetSelect(district, 'Select District');

    if (!countryId || !statesByCountry[countryId]) return;

    statesByCountry[countryId].forEach(function (st) {
      const o = document.createElement('option');
      o.value = String(st.id);      // ✅ submit ID
      o.textContent = st.name;

      if (selectedStateId && String(st.id) === String(selectedStateId)) {
        o.selected = true;
      }
      state.appendChild(o);
    });

    // If we preselected a state, auto-populate districts using its ID
    if (selectedStateId) {
      populateDistricts(scope, selectedStateId, district.dataset.selected || null);
    }
  }

  function populateDistricts(scope, stateId, selectedDistrictId) {
    const { district } = findGroup(scope);
    if (!district) return;

    resetSelect(district, 'Select District');

    if (!stateId || !districtsByState[stateId]) return;

    districtsByState[stateId].forEach(function (d) {
      const o = document.createElement('option');
      o.value = String(d.id);       // ✅ submit ID
      o.textContent = d.name;

      if (selectedDistrictId && String(d.id) === String(selectedDistrictId)) {
        o.selected = true;
      }
      district.appendChild(o);
    });
  }

  // Wire all cascades
  const allCountries = document.querySelectorAll('.js-cascade-country[data-scope]');
  allCountries.forEach(function (countrySelect) {
    const scope = countrySelect.dataset.scope;
    const { state, district } = findGroup(scope);

    // Country -> State
    countrySelect.addEventListener('change', function () {
      const countryId = this.value; // ✅ value IS country_id now

      // Clear any old "selected" markers when user changes
      if (state) state.dataset.selected = '';
      if (district) district.dataset.selected = '';

      populateStates(scope, countryId, null);
    });

    // State -> District
    if (state) {
      state.addEventListener('change', function () {
        const stateId = this.value; // ✅ value IS state_id now

        if (district) district.dataset.selected = '';
        populateDistricts(scope, stateId, null);
      });
    }

    // ----- Auto prefill (Edit modal / edit page) -----
    // If country already selected, and we have data-selected on state/district, populate them.
    const countryId = countrySelect.value;
    const selectedStateId = state ? state.dataset.selected : null;

    if (countryId && selectedStateId) {
      populateStates(scope, countryId, selectedStateId);
    } else {
      // If country chosen but no selectedStateId, just ensure states are loaded
      if (countryId) populateStates(scope, countryId, null);
    }
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const statesByCountry  = @json($statesByCountry ?? []);
  const districtsByState = @json($districtsByState ?? []);

  function reset(selectEl, placeholder) {
    if (!selectEl) return;
    selectEl.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = placeholder;
    selectEl.appendChild(opt);
  }

  function populateStates(countryId, stateSelect) {
    reset(stateSelect, 'Select State');
    if (!countryId || !statesByCountry[countryId]) return;

    statesByCountry[countryId].forEach(st => {
      const o = document.createElement('option');
      o.value = st.name;           // ✅ keep old behavior (value = name)
      o.dataset.id = st.id;        // ✅ store id in data-id for next step
      o.textContent = st.name;
      stateSelect.appendChild(o);
    });
  }

  function populateDistricts(stateId, districtSelect) {
    reset(districtSelect, 'Select District');
    if (!stateId || !districtsByState[stateId]) return;

    districtsByState[stateId].forEach(d => {
      const o = document.createElement('option');
      o.value = d.name;            // ✅ keep old behavior (value = name)
      o.dataset.id = d.id;
      o.textContent = d.name;
      districtSelect.appendChild(o);
    });
  }

  // ---- LAND selects (your existing IDs) ----
  const countrySel  = document.getElementById('land-country-select');
  const stateSel    = document.getElementById('land-state-select');
  const districtSel = document.getElementById('land-district-select');

  if (countrySel && stateSel && districtSel) {

    countrySel.addEventListener('change', function () {
      const countryId = this.options[this.selectedIndex]?.dataset?.id || null;

      populateStates(countryId, stateSel);
      reset(districtSel, 'Select District');
    });

    stateSel.addEventListener('change', function () {
      const stateId = this.options[this.selectedIndex]?.dataset?.id || null;

      populateDistricts(stateId, districtSel);
    });

    // Optional: if country already selected (edit/old input), auto-load states
    const initialCountryId = countrySel.options[countrySel.selectedIndex]?.dataset?.id || null;
    if (initialCountryId) {
      populateStates(initialCountryId, stateSel);
    }
  }

 
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const statesByCountry  = @json($statesByCountry ?? []);
  const districtsByState = @json($districtsByState ?? []);

  function resetSelect(selectEl, placeholder) {
    if (!selectEl) return;
    selectEl.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = placeholder;
    selectEl.appendChild(opt);
  }

  // Read selected option's data-id (since value is NAME in your form)
  function getSelectedId(selectEl) {
    if (!selectEl) return null;
    const opt = selectEl.options[selectEl.selectedIndex];
    return opt ? (opt.dataset.id || null) : null;
  }

  // Create option where value = NAME (for your existing backend),
  // but data-id = ID (for cascading)
  function makeOption(name, id) {
    const o = document.createElement('option');
    o.value = name;         // keep your current behavior
    o.textContent = name;
    o.dataset.id = String(id);
    return o;
  }

  function populateStates(countrySelect, stateSelect, districtSelect) {
    resetSelect(stateSelect, 'Select State');
    resetSelect(districtSelect, 'Select District');

    const countryId = getSelectedId(countrySelect);
    if (!countryId || !statesByCountry[countryId]) return;

    statesByCountry[countryId].forEach(function (st) {
      stateSelect.appendChild(makeOption(st.name, st.id));
    });
  }

  function populateDistricts(stateSelect, districtSelect) {
    resetSelect(districtSelect, 'Select District');

    const stateId = getSelectedId(stateSelect);
    if (!stateId || !districtsByState[stateId]) return;

    districtsByState[stateId].forEach(function (d) {
      districtSelect.appendChild(makeOption(d.name, d.id));
    });
  }

  // -------- LAND --------
  const landCountry  = document.getElementById('land-country-select');
  const landState    = document.getElementById('land-state-select');
  const landDistrict = document.getElementById('land-district-select');

  if (landCountry && landState && landDistrict) {
    landCountry.addEventListener('change', function () {
      populateStates(landCountry, landState, landDistrict);
    });

    landState.addEventListener('change', function () {
      populateDistricts(landState, landDistrict);
    });
  }

  // -------- BUILDING --------
  const bCountry  = document.getElementById('building-country-select');
  const bState    = document.getElementById('building-state-select');
  const bDistrict = document.getElementById('building-district-select');

  if (bCountry && bState && bDistrict) {
    bCountry.addEventListener('change', function () {
      populateStates(bCountry, bState, bDistrict);
    });

    bState.addEventListener('change', function () {
      populateDistricts(bState, bDistrict);
    });
  }

  // -------- INVESTMENT --------
  const iCountry  = document.getElementById('investment-country-select');
  const iState    = document.getElementById('investment-state-select');
  const iDistrict = document.getElementById('investment-district-select');

  if (iCountry && iState && iDistrict) {
    iCountry.addEventListener('change', function () {
      populateStates(iCountry, iState, iDistrict);
    });

    iState.addEventListener('change', function () {
      populateDistricts(iState, iDistrict);
    });
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const statesByCountry  = @json($statesByCountry ?? []);
  const districtsByState = @json($districtsByState ?? []);

  const countrySel  = document.getElementById('investment-country-select');
  const stateSel    = document.getElementById('investment-state-select');
  const districtSel = document.getElementById('investment-district-select');

  function resetSelect(selectEl, placeholder) {
    if (!selectEl) return;
    selectEl.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = placeholder;
    selectEl.appendChild(opt);
  }

  // Your <option value=""> is NAME, the ID is in data-id.
  function getSelectedId(selectEl) {
    if (!selectEl) return null;
    const opt = selectEl.options[selectEl.selectedIndex];
    return opt ? (opt.dataset.id || null) : null;
  }

  // Keep value as NAME (same as your form), but store id in data-id for cascade.
  function appendOption(selectEl, name, id) {
    const o = document.createElement('option');
    o.value = name;
    o.textContent = name;
    o.dataset.id = String(id);
    selectEl.appendChild(o);
  }

  function populateStates() {
    resetSelect(stateSel, 'Select State');
    resetSelect(districtSel, 'Select District');

    const countryId = getSelectedId(countrySel);
    if (!countryId || !statesByCountry[countryId]) return;

    statesByCountry[countryId].forEach(function (st) {
      appendOption(stateSel, st.name, st.id);
    });
  }

  function populateDistricts() {
    resetSelect(districtSel, 'Select District');

    const stateId = getSelectedId(stateSel);
    if (!stateId || !districtsByState[stateId]) return;

    districtsByState[stateId].forEach(function (d) {
      appendOption(districtSel, d.name, d.id);
    });
  }

  // Events
  if (countrySel && stateSel && districtSel) {
    countrySel.addEventListener('change', populateStates);
    stateSel.addEventListener('change', populateDistricts);

    // Optional: if country already selected on page load
    if (countrySel.value) populateStates();
  }
});
</script>


</body>
</html>
