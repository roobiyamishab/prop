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
    <div id="dashboard-page" class="dashboard-page">
      
      {{-- SIDEBAR --}}
      <div class="dashboard-sidebar">
        <div class="sidebar-header">
          <h2>AIPropMatch</h2>
        </div>

        <nav class="sidebar-nav">
          {{-- Dashboard Home --}}
          <a href="{{ route('admin.dashboard', ['tab' => 'home']) }}"
             class="sidebar-item {{ request()->routeIs('admin.dashboard') && request('tab', 'home') === 'home' ? 'active' : '' }}">
            <span class="sidebar-icon">🏠</span>
            <span>Dashboard Home</span>
          </a>

          {{-- Buyer Module – go to dashboard buyer tab --}}
          <a href="{{ route('admin.dashboard', ['tab' => 'buyer']) }}"
             class="sidebar-item {{ request()->routeIs('admin.dashboard') && request('tab') === 'buyer' ? 'active' : '' }}">
            <span class="sidebar-icon">🔍</span>
            <span>Buyer Module</span>
          </a>

          {{-- Seller Module – adjust route when you have it --}}
         
           {{-- Seller Module --}}
{{-- Seller Module --}}
{{-- Seller Module --}}
<a href="{{ route('admin.dashboard', ['tab' => 'seller']) }}"
   class="sidebar-item {{ request()->routeIs('admin.dashboard') && request('tab') === 'seller' ? 'active' : '' }}">
  <span class="sidebar-icon">📋</span>
  <span>Seller Module</span>
</a>

  {{-- Investment Module – adjust route when you have it --}}
          <!-- @if (Route::has('admin.investments.index'))
            <a href="{{ route('admin.investments.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.investments.*') ? 'active' : '' }}">
              <span class="sidebar-icon">💰</span>
              <span>Investment Module</span>
            </a>
          @else
            <a href="javascript:void(0)" class="sidebar-item">
              <span class="sidebar-icon">💰</span>
              <span>Investment Module</span>
            </a>
          @endif -->

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

  {{-- Page-specific scripts --}}
  @stack('scripts')
</body>
</html>
