@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Detailed View – Buyer Investment Preference</h1>
    <p>Buyer: {{ $buyer->name }} (ID: {{ $buyer->id }})</p>
  </div>

  <div class="module-content">
    <div class="detail-top-bar">
      <a href="{{ route('admin.buyer.properties.index', ['buyer' => $buyer->id, 'tab' => 'investment']) }}"
         class="btn-table btn-table-view">
        ← Back to All Investment Preferences
      </a>

      <div class="detail-actions">
        {{-- EDIT BUTTON --}}
        <button type="button"
                class="btn-table btn-table-edit"
                onclick="openInvestmentEditModal()">
          Edit Preference
        </button>

        {{-- DELETE BUTTON --}}
        <form method="POST"
              action="{{ route('admin.buyers.preferences.investment.destroy', $investment) }}"
              onsubmit="return confirm('Are you sure you want to delete this investment preference?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-table btn-table-danger">
            Delete
          </button>
        </form>
      </div>
    </div>

    {{-- DETAILS CARD --}}
    <div class="detail-card">

      <div class="detail-row">
        <span class="detail-label">Request Code</span>
        <span class="detail-value">REQ-INV-{{ $investment->id }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value">{{ ucfirst($investment->status ?? 'active') }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Property Type</span>
        <span class="detail-value">{{ $investment->investment_property_type ?? '—' }}</span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Preferred Districts</span>
        <span class="detail-value">
          {{ is_array($investment->preferred_districts) ? implode(', ', $investment->preferred_districts) : ($investment->preferred_districts ?? '—') }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Preferred Locations</span>
        <span class="detail-value">
          {{ is_array($investment->preferred_locations) ? implode(', ', $investment->preferred_locations) : ($investment->preferred_locations ?? '—') }}
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Budget Range</span>
        <span class="detail-value">
          @if($investment->investment_budget_min)
            ₹{{ number_format($investment->investment_budget_min) }}
            @if($investment->investment_budget_max && $investment->investment_budget_max != $investment->investment_budget_min)
              – ₹{{ number_format($investment->investment_budget_max) }}
            @endif
          @else
            —
          @endif
        </span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Profit Expectation / Year</span>
        <span class="detail-value">
          {{ $investment->profit_expectation_year ? $investment->profit_expectation_year . '%' : '—' }}
        </span>
      </div>

    </div>
  </div>
</div>

{{-- ============================ --}}
{{-- EDIT INVESTMENT MODAL --}}
{{-- ============================ --}}
<div id="investment-edit-modal" class="modal-overlay" style="display:none;">
  <div class="modal-backdrop" onclick="closeInvestmentEditModal()"></div>

  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Investment Preference</h2>
      <button type="button" class="modal-close-btn" onclick="closeInvestmentEditModal()">×</button>
    </div>

    <div class="modal-body">
      <form method="POST" action="{{ route('admin.buyers.preferences.investment.update', $investment) }}">
        @csrf
        @method('PATCH')

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="form-grid-2">

          {{-- PROPERTY TYPE --}}
          <div class="form-group">
            <label for="investment_property_type">Property Type</label>
            <input type="text" id="investment_property_type" name="investment_property_type"
                   class="form-control"
                   value="{{ old('investment_property_type', $investment->investment_property_type) }}">
          </div>

          {{-- Preferred districts --}}
          <div class="form-group">
            <label for="preferred_districts">Preferred Districts (comma separated)</label>
            <input type="text"
                   id="preferred_districts"
                   name="preferred_districts"
                   class="form-control"
                   value="{{ old('preferred_districts', is_array($investment->preferred_districts) ? implode(', ', $investment->preferred_districts) : $investment->preferred_districts) }}">
          </div>

          {{-- Preferred locations --}}
          <div class="form-group">
            <label for="preferred_locations">Preferred Locations (comma separated)</label>
            <input type="text"
                   id="preferred_locations"
                   name="preferred_locations"
                   class="form-control"
                   value="{{ old('preferred_locations', is_array($investment->preferred_locations) ? implode(', ', $investment->preferred_locations) : $investment->preferred_locations) }}">
          </div>

          {{-- Budget min/max --}}
          <div class="form-group">
            <label>Budget Range (Min – Max)</label>
            <div style="display:flex; gap:8px;">
              <input type="number" name="investment_budget_min" class="form-control"
                     placeholder="Min"
                     value="{{ old('investment_budget_min', $investment->investment_budget_min) }}">

              <input type="number" name="investment_budget_max" class="form-control"
                     placeholder="Max"
                     value="{{ old('investment_budget_max', $investment->investment_budget_max) }}">
            </div>
          </div>

          {{-- Profit Expectation --}}
          <div class="form-group">
            <label for="profit_expectation_year">Profit Expectation (% per year)</label>
            <input type="number" step="0.01"
                   id="profit_expectation_year"
                   name="profit_expectation_year"
                   class="form-control"
                   value="{{ old('profit_expectation_year', $investment->profit_expectation_year) }}">
          </div>

          {{-- STATUS --}}
          <div class="form-group">
            <label>Status</label>
            @php $status = old('status', $investment->status ?? 'active'); @endphp
            <select name="status" class="form-control">
              <option value="active" {{ $status=='active' ? 'selected':'' }}>Active</option>
              <option value="urgent" {{ $status=='urgent' ? 'selected':'' }}>Urgent</option>
              <option value="completed" {{ $status=='completed' ? 'selected':'' }}>Completed</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn-table btn-table-secondary" onclick="closeInvestmentEditModal()">Cancel</button>
          <button type="submit" class="btn-table btn-table-primary">Save Changes</button>
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
    background: rgba(15,23,42,0.6);
    z-index: 999;
  }
  .modal-backdrop {
    position: absolute;
    inset:0;
  }
  .modal-content {
    position: relative;
    max-width: 900px;
    margin: 50px auto;
    background: #fff;
    border-radius: 12px;
    padding: 20px 24px;
  }
  .modal-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
  }
  .modal-close-btn {
    border:none;
    background:transparent;
    font-size:24px;
    cursor:pointer;
  }
  .form-grid-2 {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:16px;
  }
  .form-group { display:flex; flex-direction:column; gap:4px; }
</style>
@endpush

@push('scripts')
<script>
  function openInvestmentEditModal() {
    document.getElementById('investment-edit-modal').style.display = 'block';
  }
  function closeInvestmentEditModal() {
    document.getElementById('investment-edit-modal').style.display = 'none';
  }
</script>
@endpush
