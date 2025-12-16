@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Detailed View – Buyer Land Preference</h1>
    <p>Buyer: {{ $buyer->name }} (ID: {{ $buyer->id }})</p>
  </div>

  <div class="module-content">
    <div class="detail-top-bar">
      <a href="{{ route('admin.buyer.properties.index', ['buyer' => $buyer->id, 'tab' => 'land']) }}"
         class="btn-table btn-table-view">
        ← Back to All Land Preferences
      </a>

      <div class="detail-actions">
        {{-- EDIT in MODAL --}}
        <button type="button"
                class="btn-table btn-table-edit"
                onclick="openLandEditModal()">
          Edit Preference
        </button>

        {{-- DELETE: calls destroy route --}}
        <form method="POST"
              action="{{ route('admin.buyers.preferences.land.destroy', $land) }}"
              onsubmit="return confirm('Are you sure you want to delete this land preference?');">
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
        <span class="detail-value">REQ-LAND-{{ $land->id }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value">{{ ucfirst($land->status ?? 'active') }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Preferred Districts</span>
        <span class="detail-value">
          {{ is_array($land->preferred_districts) ? implode(', ', $land->preferred_districts) : ($land->preferred_districts ?? '—') }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Preferred Locations</span>
        <span class="detail-value">
          {{ is_array($land->preferred_locations) ? implode(', ', $land->preferred_locations) : ($land->preferred_locations ?? '—') }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Land Size Unit</span>
        <span class="detail-value">{{ strtoupper($land->land_size_unit ?? 'cent') }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Budget Capacity / cent</span>
        <span class="detail-value">
          @if($land->budget_per_cent_min || $land->budget_per_cent_max)
            ₹{{ number_format($land->budget_per_cent_min ?? 0) }}
            @if($land->budget_per_cent_max && $land->budget_per_cent_max != $land->budget_per_cent_min)
              – ₹{{ number_format($land->budget_per_cent_max) }}
            @endif
          @else
            —
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Zoning Preference</span>
        <span class="detail-value">{{ $land->zoning_preference ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Timeline to Purchase</span>
        <span class="detail-value">{{ $land->timeline_to_purchase ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Mode of Purchase</span>
        <span class="detail-value">{{ $land->mode_of_purchase ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Typical Advance Capacity</span>
        <span class="detail-value">
          {{ $land->advance_capacity ? $land->advance_capacity . '%' : '—' }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Documentation Speed</span>
        <span class="detail-value">{{ $land->documentation_speed ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Property Condition</span>
        <span class="detail-value">{{ $land->property_condition ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Amenities Preference</span>
        <span class="detail-value">
          @php $amenities = $land->amenities_preference ?? []; @endphp
          @if(is_array($amenities) && count($amenities))
            {{ implode(', ', $amenities) }}
          @else
            —
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Infrastructure Preference</span>
        <span class="detail-value">
          {{ $land->infrastructure_preference ?: '—' }}
        </span>
      </div>
    </div>
  </div>
</div>

{{-- ========================= --}}
{{-- EDIT LAND PREFERENCE MODAL --}}
{{-- ========================= --}}
<div id="land-edit-modal" class="modal-overlay" style="display:none;">
  <div class="modal-backdrop" onclick="closeLandEditModal()"></div>

  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Land Preference</h2>
      <button type="button" class="modal-close-btn" onclick="closeLandEditModal()">×</button>
    </div>

    <div class="modal-body">
      <form method="POST" action="{{ route('admin.buyers.preferences.land.update', $land) }}">
        @csrf
        @method('PATCH')

        {{-- you can show validation errors here if you want --}}
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
          <div class="form-group">
            <label for="preferred_districts">Preferred Districts (comma separated)</label>
            <input type="text"
                   id="preferred_districts"
                   name="preferred_districts"
                   class="form-control"
                   value="{{ old('preferred_districts', is_array($land->preferred_districts) ? implode(', ', $land->preferred_districts) : $land->preferred_districts) }}">
          </div>

          <div class="form-group">
            <label for="preferred_locations">Preferred Locations (comma separated)</label>
            <input type="text"
                   id="preferred_locations"
                   name="preferred_locations"
                   class="form-control"
                   value="{{ old('preferred_locations', is_array($land->preferred_locations) ? implode(', ', $land->preferred_locations) : $land->preferred_locations) }}">
          </div>

          <div class="form-group">
            <label for="land_size_unit">Land Size Unit</label>
            <select id="land_size_unit"
                    name="land_size_unit"
                    class="form-control">
              @php $unit = old('land_size_unit', $land->land_size_unit ?? 'cent'); @endphp
              <option value="cent" {{ $unit === 'cent' ? 'selected' : '' }}>Cent</option>
              <option value="acre" {{ $unit === 'acre' ? 'selected' : '' }}>Acre</option>
            </select>
          </div>

          <div class="form-group">
            <label>Budget / Cent (Min – Max)</label>
            <div style="display:flex; gap:8px;">
              <input type="number"
                     name="budget_per_cent_min"
                     class="form-control"
                     placeholder="Min"
                     value="{{ old('budget_per_cent_min', $land->budget_per_cent_min) }}">
              <input type="number"
                     name="budget_per_cent_max"
                     class="form-control"
                     placeholder="Max"
                     value="{{ old('budget_per_cent_max', $land->budget_per_cent_max) }}">
            </div>
          </div>

          <div class="form-group">
            <label for="zoning_preference">Zoning Preference</label>
            <input type="text"
                   id="zoning_preference"
                   name="zoning_preference"
                   class="form-control"
                   value="{{ old('zoning_preference', $land->zoning_preference) }}">
          </div>

          <div class="form-group">
            <label for="timeline_to_purchase">Timeline to Purchase</label>
            <input type="text"
                   id="timeline_to_purchase"
                   name="timeline_to_purchase"
                   class="form-control"
                   value="{{ old('timeline_to_purchase', $land->timeline_to_purchase) }}">
          </div>

          <div class="form-group">
            <label for="mode_of_purchase">Mode of Purchase</label>
            <input type="text"
                   id="mode_of_purchase"
                   name="mode_of_purchase"
                   class="form-control"
                   value="{{ old('mode_of_purchase', $land->mode_of_purchase) }}">
          </div>

          <div class="form-group">
            <label for="advance_capacity">Typical Advance Capacity (%)</label>
            <input type="number"
                   id="advance_capacity"
                   name="advance_capacity"
                   class="form-control"
                   min="0"
                   max="100"
                   value="{{ old('advance_capacity', $land->advance_capacity) }}">
          </div>

          <div class="form-group">
            <label for="documentation_speed">Documentation Speed</label>
            <input type="text"
                   id="documentation_speed"
                   name="documentation_speed"
                   class="form-control"
                   value="{{ old('documentation_speed', $land->documentation_speed) }}">
          </div>

          <div class="form-group">
            <label for="property_condition">Preferred Property Condition</label>
            <input type="text"
                   id="property_condition"
                   name="property_condition"
                   class="form-control"
                   value="{{ old('property_condition', $land->property_condition) }}">
          </div>

          <div class="form-group form-group-full">
            <label for="amenities_preference">Amenities Preference (comma separated)</label>
            <textarea id="amenities_preference"
                      name="amenities_preference"
                      class="form-control"
                      rows="2">{{ old('amenities_preference', (is_array($land->amenities_preference ?? null) ? implode(', ', $land->amenities_preference) : $land->amenities_preference)) }}</textarea>
          </div>

          <div class="form-group form-group-full">
            <label for="infrastructure_preference">Infrastructure Preference</label>
            <textarea id="infrastructure_preference"
                      name="infrastructure_preference"
                      class="form-control"
                      rows="2">{{ old('infrastructure_preference', $land->infrastructure_preference) }}</textarea>
          </div>

          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
              @php $status = old('status', $land->status ?? 'active'); @endphp
              <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
              <option value="urgent" {{ $status === 'urgent' ? 'selected' : '' }}>Urgent</option>
              <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button"
                  class="btn-table btn-table-secondary"
                  onclick="closeLandEditModal()">
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
  /* ---------- PAGE BACKGROUND & WRAPPER ---------- */

  /* Make this screen white instead of ash */
  .dashboard-main {
    background: #ffffff !important;
  }

  .dashboard-screen {
    max-width: 1100px;
    margin: 24px auto 40px;
  }

  /* ---------- TOP BAR & ACTION BUTTONS ---------- */

  .detail-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
  }

  .detail-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .btn-table-danger {
    background: #fee2e2;
    color: #b91c1c;
    border-radius: 999px;
    padding: 6px 12px;
    border: 1px solid #fecaca;
    font-size: 12px;
    font-weight: 500;
  }

  .btn-table-danger:hover {
    background: #fecaca;
  }

  /* ---------- DETAIL CARD (WHITE) ---------- */

  .dashboard-screen .detail-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px 32px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    max-width: 960px;
    margin: 0 auto 32px;
  }

  /* Labels & values closer together */
  .dashboard-screen .detail-row {
    display: flex;
    justify-content: flex-start;   /* no space-between */
    align-items: flex-start;
    gap: 24px;                     /* distance between left & right */
    padding: 10px 0;
    border-bottom: 1px dashed #e5e7eb;
    font-size: 14px;
  }

  .dashboard-screen .detail-row:last-child {
    border-bottom: none;
  }

  .dashboard-screen .detail-label {
    min-width: 220px;              /* label column */
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #9ca3af;
  }

  .dashboard-screen .detail-value {
    flex: 0 1 60%;                 /* value column next to label */
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    text-align: left;              /* not right aligned */
    word-break: break-word;
  }

  @media (max-width: 768px) {
    .dashboard-screen .detail-card {
      padding: 18px 16px;
      border-radius: 12px;
    }

    .dashboard-screen .detail-row {
      flex-direction: column;
      gap: 4px;
    }

    .dashboard-screen .detail-label {
      min-width: 0;
    }

    .dashboard-screen .detail-value {
      flex: 1;
    }
  }

  /* ---------- CENTERED EDIT MODAL ---------- */

  .modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;                 /* shown via JS as flex */
    align-items: center;
    justify-content: center;
    padding: 16px;
  }

  .modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
  }

  .modal-content {
    position: relative;
    width: 100%;
    max-width: 900px;
    max-height: calc(100vh - 32px);
    background: #ffffff;
    border-radius: 16px;
    padding: 20px 24px;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.4);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e5e7eb;
  }

  .modal-close-btn {
    border: none;
    background: transparent;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    color: #6b7280;
  }

  .modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    padding-top: 4px;
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

  .form-control:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.25);
  }

  .modal-footer {
    margin-top: 16px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
  }

  .btn-table-secondary {
    background: #f3f4f6;
    color: #374151;
    border-radius: 999px;
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    font-size: 12px;
    font-weight: 500;
  }

  .btn-table-secondary:hover {
    background: #e5e7eb;
  }

  .btn-table-primary {
    background: #111827;
    color: #f9f9ff;
    border-radius: 999px;
    padding: 6px 16px;
    border: 1px solid #111827;
    font-size: 12px;
    font-weight: 500;
  }

  .btn-table-primary:hover {
    background: #020617;
  }
</style>
@endpush


@push('scripts')
<script>
  function openLandEditModal() {
    var modal = document.getElementById('land-edit-modal');
    if (modal) {
      modal.style.display = 'flex';   // center via flex (matches CSS above)
    }
  }

  function closeLandEditModal() {
    var modal = document.getElementById('land-edit-modal');
    if (modal) {
      modal.style.display = 'none';
    }
  }

  // Optional: close when clicking on dark backdrop
  document.addEventListener('click', function (e) {
    if (e.target.id === 'land-edit-modal') {
      closeLandEditModal();
    }
  });
</script>
@endpush

