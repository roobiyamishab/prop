{{-- Preloader --}}
<div id="preloader">
  <div id="text">
    <p>D</p><p>O</p><p>L</p><p>P</p><p>H</p><p>I</p><p class="active">N</p>
  </div>
</div>

{{-- Scroll progress --}}
<div class="progress-wrap">
  <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
  </svg>
</div>

<nav class="navbar navbar-expand-lg navbar-main">
  <div class="container">

    {{-- Logo --}}
    <a class="navbar-brand" href="{{ route('front.home') }}">
      <img src="{{ asset('frontend/assets/images/logo/aipro.jpg') }}"
           alt="logo"
           class="logo-img"
           style="width: 225px;">
    </a>

    {{-- Right buttons (Desktop) --}}
    <div class="right-nav">
      @auth
        <a href="{{ route('dashboard') }}" class="btn btn--border">Dashboard</a>

        {{-- logout (POST) --}}
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" class="btn btn--base">Logout</button>
        </form>
      @else
        @if(Route::has('login'))
          <a href="{{ route('login') }}" class="btn btn--border">Login</a>
        @endif

        @if(Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn--base">Sign Up</a>
        @endif
      @endauth

      {{-- Mobile toggler --}}
      <button class="navbar-toggler" type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasNavbar"
              aria-controls="offcanvasNavbar"
              aria-label="Toggle navigation">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
             fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
        </svg>
      </button>
    </div>

    {{-- Offcanvas menu --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
          <img src="{{ asset('frontend/assets/images/logo/aipro.jpg') }}" alt="logo" class="logo-img">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>

      {{-- Mobile auth buttons --}}
      <div class="d-flex d-lg-none gap-4 pt-3 justify-content-center">
        @auth
          <a href="{{ route('dashboard') }}" class="btn btn--border">Dashboard</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn--base">Logout</button>
          </form>
        @else
          @if(Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn--border">Login</a>
          @endif
          @if(Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn--base">Sign Up</a>
          @endif
        @endauth
      </div>

      <div class="offcanvas-body align-items-center">
        <ul class="navbar-nav justify-content-center flex-grow-1">

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('front.home') ? 'active' : '' }}"
               href="{{ route('front.home') }}">
              <span>Home</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('front.about') ? 'active' : '' }}"
               href="{{ route('front.about') }}">
              <span>About Us</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('') || request()->routeIs('') ? 'active' : '' }}"
               href="">
              <span>Properties</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}"
               href="{{ route('front.contact') }}">
              <span>Contacts</span>
            </a>
          </li>

        </ul>
      </div>
    </div>

  </div>
</nav>
