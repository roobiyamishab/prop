@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Detailed View – Buyer Building Preference</h1>
    <p>Buyer: {{ $buyer->name }} (ID: {{ $buyer->id }})</p>
  </div>

  <div class="module-content">
    <div class="detail-top-bar">
      <a href="{{ route('admin.buyer.properties.index', ['buyer' => $buyer->id, 'tab' => 'building']) }}"
         class="btn-table btn-table-view">
        ← Back to All Building Preferences
      </a>

      <div class="detail-actions">
        {{-- EDIT in MODAL --}}
        <button type="button"
                class="btn-table btn-table-edit"
                onclick="openBuildingEditModal()">
          Edit Preference
        </button>

        {{-- DELETE --}}
        <form method="POST"
              action="{{ route('admin.buyers.preferences.building.destroy', $building) }}"
              onsubmit="return confirm('Are you sure you want to delete this building preference?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-table btn-table-danger">
            Delete
          </button>
        </form>
      </div>
    </div>

    <div class="detail-card">
      <div class="detail-row">
        <span class="detail-label">Request Code</span>
        <span class="detail-value">REQ-BLD-{{ $building->id }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value">{{ ucfirst($building->status ?? 'active') }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Building Type</span>
        <span class="detail-value">{{ $building->building_type ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Preferred Districts</span>
        <span class="detail-value">
          {{ is_array($building->preferred_districts) ? implode(', ', $building->preferred_districts) : ($building->preferred_districts ?? '—') }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Micro-locations</span>
        <span class="detail-value">
          {{ is_array($building->micro_locations) ? implode(', ', $building->micro_locations) : ($building->micro_locations ?? '—') }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Area Range (sq.ft)</span>
        <span class="detail-value">
          @if($building->area_min || $building->area_max)
            {{ number_format($building->area_min ?? 0) }} –
            {{ number_format($building->area_max ?? $building->area_min ?? 0) }}
          @else
            —
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Frontage Requirement (ft)</span>
        <span class="detail-value">{{ $building->frontage_min ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Age Preference</span>
        <span class="detail-value">{{ $building->age_preference ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Total Budget</span>
        <span class="detail-value">
          @if($building->total_budget_min)
            ₹{{ number_format($building->total_budget_min) }}
            @if($building->total_budget_max && $building->total_budget_max != $building->total_budget_min)
              – ₹{{ number_format($building->total_budget_max) }}
            @endif
          @else
            —  
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Distance Requirements</span>
        <span class="detail-value">
          {{ $building->distance_requirement ?: '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Rent Expectation</span>
        <span class="detail-value">
          {{ $building->rent_expectation ? '₹' . number_format($building->rent_expectation) . ' / month' : '—' }}
        </span>
      </div>
    </div>
  </div>
</div>

{{-- ============================ --}}
{{-- EDIT BUILDING PREFERENCE MODAL --}}
{{-- ============================ --}}
<div id="building-edit-modal" class="modal-overlay" style="display:none;">
  <div class="modal-backdrop" onclick="closeBuildingEditModal()"></div>

  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Building Preference</h2>
      <button type="button" class="modal-close-btn" onclick="closeBuildingEditModal()">×</button>
    </div>

    <div class="modal-body">
      <form method="POST" action="{{ route('admin.buyers.preferences.building.update', $building) }}">
        @csrf
        @method('PATCH')

        @if ($errors->any())
          <div class="alert alert-danger" style="margin-bottom:1rem;">
            <ul style="margin:0; padding-left:1.2rem;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="form-grid-2">
          {{-- Building Type (SELECT) --}}
          <div class="form-group">
            <label for="building_type">Building Type</label>
            @php
              $bt = old('building_type', $building->building_type);
            @endphp
            <select id="building_type" name="building_type" class="form-control">
              <option value="">Select building type</option>

              <option value="Commercial Building" {{ $bt === 'Commercial Building' ? 'selected' : '' }}>
                Commercial Building
              </option>
              <option value="Office Building" {{ $bt === 'Office Building' ? 'selected' : '' }}>
                Office Building
              </option>
              <option value="Retail Complex" {{ $bt === 'Retail Complex' ? 'selected' : '' }}>
                Retail Complex
              </option>
              <option value="Apartment Building" {{ $bt === 'Apartment Building' ? 'selected' : '' }}>
                Apartment Building
              </option>
              <option value="Villa Project / Gated Community" {{ $bt === 'Villa Project / Gated Community' ? 'selected' : '' }}>
                Villa Project / Gated Community
              </option>
              <option value="Mixed-Use Building" {{ $bt === 'Mixed-Use Building' ? 'selected' : '' }}>
                Mixed-Use Building
              </option>
              <option value="Hotel / Resort" {{ $bt === 'Hotel / Resort' ? 'selected' : '' }}>
                Hotel / Resort
              </option>
              <option value="Hospital / Clinic Building" {{ $bt === 'Hospital / Clinic Building' ? 'selected' : '' }}>
                Hospital / Clinic Building
              </option>
              <option value="Warehouse / Industrial Building" {{ $bt === 'Warehouse / Industrial Building' ? 'selected' : '' }}>
                Warehouse / Industrial Building
              </option>
            </select>
          </div>

          {{-- Preferred Districts --}}
          <div class="form-group">
            <label for="preferred_districts">Preferred Districts (comma separated)</label>
            <input type="text"
                   id="preferred_districts"
                   name="preferred_districts"
                   class="form-control"
                   value="{{ old('preferred_districts', is_array($building->preferred_districts) ? implode(', ', $building->preferred_districts) : $building->preferred_districts) }}">
          </div>

          {{-- Micro locations --}}
          <div class="form-group">
            <label for="micro_locations">Micro-locations (comma separated)</label>
            <input type="text"
                   id="micro_locations"
                   name="micro_locations"
                   class="form-control"
                   value="{{ old('micro_locations', is_array($building->micro_locations) ? implode(', ', $building->micro_locations) : $building->micro_locations) }}">
          </div>

          {{-- Area range --}}
          <div class="form-group">
            <label>Area Range (sq.ft)</label>
            <div style="display:flex; gap:8px;">
              <input type="number"
                     name="area_min"
                     class="form-control"
                     placeholder="Min"
                     value="{{ old('area_min', $building->area_min) }}">
              <input type="number"
                     name="area_max"
                     class="form-control"
                     placeholder="Max"
                     value="{{ old('area_max', $building->area_max) }}">
            </div>
          </div>

          {{-- Frontage --}}
          <div class="form-group">
            <label for="frontage_min">Frontage Requirement (ft)</label>
            <input type="number"
                   id="frontage_min"
                   name="frontage_min"
                   class="form-control"
                   value="{{ old('frontage_min', $building->frontage_min) }}">
          </div>

          {{-- Age preference --}}
          <div class="form-group">
            <label for="age_preference">Age Preference</label>
            <input type="text"
                   id="age_preference"
                   name="age_preference"
                   class="form-control"
                   value="{{ old('age_preference', $building->age_preference) }}">
          </div>

          {{-- Total budget --}}
          <div class="form-group">
            <label>Total Budget</label>
            <div style="display:flex; gap:8px;">
              <input type="number"
                     name="total_budget_min"
                     class="form-control"
                     placeholder="Min"
                     value="{{ old('total_budget_min', $building->total_budget_min) }}">
              <input type="number"
                     name="total_budget_max"
                     class="form-control"
                     placeholder="Max"
                     value="{{ old('total_budget_max', $building->total_budget_max) }}">
            </div>
          </div>

          {{-- Distance requirement --}}
          <div class="form-group form-group-full">
            <label for="distance_requirement">Distance Requirements</label>
            <textarea id="distance_requirement"
                      name="distance_requirement"
                      class="form-control"
                      rows="2">{{ old('distance_requirement', $building->distance_requirement) }}</textarea>
          </div>

          {{-- Rent expectation --}}
          <div class="form-group">
            <label for="rent_expectation">Rent Expectation (per month)</label>
            <input type="number"
                   id="rent_expectation"
                   name="rent_expectation"
                   class="form-control"
                   value="{{ old('rent_expectation', $building->rent_expectation) }}">
          </div>

          {{-- Status --}}
          <div class="form-group">
            <label for="status">Status</label>
            @php $status = old('status', $building->status ?? 'active'); @endphp
            <select id="status" name="status" class="form-control">
              <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
              <option value="urgent" {{ $status === 'urgent' ? 'selected' : '' }}>Urgent</option>
              <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button"
                  class="btn-table btn-table-secondary"
                  onclick="closeBuildingEditModal()">
            Cancel
          </button>
          <button type="submit" class="btn-table btn-table-primary">
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
  .modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
  }
  .modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
  }
  .modal-content {
    position: relative;
    max-width: 900px;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 12px;
    padding: 20px 24px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.35);
  }
  .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
  }
  .modal-close-btn {
    border: none;
    background: transparent;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
  }
  .modal-body {
    max-height: 70vh;
    overflow-y: auto;
  }
  .form-grid-2 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
  }
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .form-group-full {
    grid-column: 1 / -1;
  }
  .form-control {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 8px 10px;
    font-size: 14px;
  }
  .modal-footer {
    margin-top: 16px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }
</style>
@endpush

@push('scripts')
<script>
  function openBuildingEditModal() {
    var modal = document.getElementById('building-edit-modal');
    if (modal) {
      modal.style.display = 'block';
    }
  }

  function closeBuildingEditModal() {
    var modal = document.getElementById('building-edit-modal');
    if (modal) {
      modal.style.display = 'none';
    }
  }
</script>
@endpush
