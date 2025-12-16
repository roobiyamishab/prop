@extends('admin.layouts.app')

@section('content')
<div class="dashboard-screen">
  <div class="dashboard-header">
    <h1>Edit Investment Listing – {{ $investment->property_code }}</h1>
    <p>Update project details, investment terms, documents, and status.</p>
  </div>

  <div class="module-content">

    <a href="{{ route('admin.seller.properties.index', ['seller' => $investment->user_id, 'tab' => 'investment']) }}"
       class="btn-table btn-table-view"
       style="margin-bottom: 16px; display:inline-flex;">
      ← Back to All Investment Listings
    </a>

    <div class="detail-card edit-invest-card">
      <form class="module-form edit-invest-form"
            method="POST"
            action="{{ route('admin.seller.investment.update', $investment) }}"
            enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <h3 class="section-title">Project Details</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Project Name</label>
            <input type="text" name="project_name"
                   value="{{ old('project_name', $investment->project_name) }}"
                   placeholder="Project name">
          </div>
          <div class="form-group">
            <label>Project Type</label>
            <input type="text" name="project_type"
                   value="{{ old('project_type', $investment->project_type) }}"
                   placeholder="Land development / Villas / Apartments ...">
          </div>
        </div>

        <h3 class="section-title">Location Details</h3>

        <div class="form-row">
          <div class="form-group">
            <label>District</label>
            <input type="text" name="district"
                   value="{{ old('district', $investment->district) }}"
                   placeholder="District">
          </div>
          <div class="form-group">
            <label>Micro Location</label>
            <input type="text" name="micro_location"
                   value="{{ old('micro_location', $investment->micro_location) }}"
                   placeholder="Area / locality / micro-location">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Landmark</label>
            <input type="text" name="landmark"
                   value="{{ old('landmark', $investment->landmark) }}"
                   placeholder="Nearby landmark">
          </div>
          <div class="form-group">
            <label>Google Map Link</label>
            <input type="url" name="map_link"
                   value="{{ old('map_link', $investment->map_link) }}"
                   placeholder="https://maps.google.com/...">
          </div>
        </div>

        <h3 class="section-title">Investment & Financials</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Total Project Cost (₹)</label>
            <input type="number" step="0.01" name="project_cost"
                   value="{{ old('project_cost', $investment->project_cost) }}"
                   placeholder="Total project cost">
          </div>
          <div class="form-group">
            <label>Investment Required (₹)</label>
            <input type="number" step="0.01" name="investment_required"
                   value="{{ old('investment_required', $investment->investment_required) }}"
                   placeholder="Capital required">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Profit Sharing Model</label>
            <input type="text" name="profit_sharing_model"
                   value="{{ old('profit_sharing_model', $investment->profit_sharing_model) }}"
                   placeholder="Eg: 60:40, fixed ROI 18% p.a">
          </div>
          <div class="form-group">
            <label>Payback Period</label>
            <input type="text" name="payback_period"
                   value="{{ old('payback_period', $investment->payback_period) }}"
                   placeholder="Eg: 24–36 months">
          </div>
        </div>

        <h3 class="section-title">Project Status</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Project Status</label>
            <input type="text" name="project_status"
                   value="{{ old('project_status', $investment->project_status) }}"
                   placeholder="Approvals in process / Under construction / Completed etc.">
          </div>
          <div class="form-group">
            <label>Completion %</label>
            <input type="number" name="completion_percent"
                   value="{{ old('completion_percent', $investment->completion_percent) }}"
                   min="0" max="100" placeholder="0–100">
          </div>
        </div>

        <h3 class="section-title">Listing Status</h3>

        <div class="form-row">
          <div class="form-group">
            <label>Status</label>
            <select name="status">
              @foreach(['normal','hot','urgent','sold','booked','off_market'] as $st)
                <option value="{{ $st }}" {{ $investment->status === $st ? 'selected' : '' }}>
                  {{ ucfirst($st) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <h3 class="section-title">Documents</h3>

        @if($investment->documents && count($investment->documents))
          <div class="form-row" style="flex-wrap: wrap; gap: 8px;">
            @foreach($investment->documents as $doc)
              <a href="{{ asset('storage/' . $doc) }}" target="_blank"
                 class="btn-table btn-table-view">
                View Document
              </a>
            @endforeach
          </div>
        @else
          <p style="font-size: 13px; color:#6b7280;">No documents uploaded yet.</p>
        @endif

        <div class="form-group" style="margin-top: 12px;">
          <label>Add More Documents</label>
          <input type="file" name="documents[]" multiple>
        </div>

        <div style="margin-top: 24px; display:flex; gap:12px;">
          <button type="submit" class="btn-primary">
            Save Changes
          </button>

          {{-- Optional: delete button --}}
         
        </div>

      </form>
    </div>

  </div>
</div>
@endsection

@push('styles')
<style>
    .edit-invest-card {
        padding: 24px 32px;
        margin-top: 16px;
    }

    .edit-invest-form .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .edit-invest-form .form-group {
        flex: 1 1 0;
    }

    .edit-invest-form .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .edit-invest-form .form-group input,
    .edit-invest-form .form-group select,
    .edit-invest-form .form-group textarea {
        width: 100%;
    }

    .edit-invest-form .section-title {
        font-size: 18px;
        font-weight: 600;
        margin: 24px 0 12px;
    }

    .edit-invest-form .section-title:first-of-type {
        margin-top: 0;
    }

    .btn-danger {
        background: #dc2626;
        border: none;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }
</style>
@endpush
