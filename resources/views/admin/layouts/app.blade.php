{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AIPropMatch - AI-Powered Real Estate Intelligence</title>

  {{-- Global CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  {{-- Page-specific styles --}}
  @stack('styles')
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

      {{-- MAIN CONTENT AREA --}}
      <div class="dashboard-main">
        @yield('content')
      </div>

    </div>
  </div>

  {{-- Global JS (if any) --}}
  {{-- <script src="{{ asset('assets/app.js') }}"></script> --}}

  {{-- Page-specific scripts --}}
  @stack('scripts')
</body>
</html>
