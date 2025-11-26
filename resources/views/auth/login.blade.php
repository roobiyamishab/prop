<x-guest-layout>
    <div id="app">
        <div id="login-page" class="page auth-page" style="display:block;">
            <div class="auth-container">
                <div class="auth-card">
                    {{-- Title + subtitle --}}
                    <h2 class="auth-title">Welcome back</h2>
                    <p class="auth-subtitle">Log in to your AIPropMatch account</p>

                    {{-- Session Status (same as old view) --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="auth-form">
                        @csrf

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="you@example.com"
                            >
                            @error('email')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            >
                            @error('password')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remember Me + Forgot Password --}}
                        <div class="form-group" style="display:flex; justify-content:space-between; align-items:center;">
                            <label for="remember_me" style="display:flex; align-items:center; gap:8px; font-size:14px; color:var(--text-secondary);">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    style="width:16px; height:16px;"
                                >
                                <span>Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size:14px;">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        {{-- Submit button --}}
                        <button type="submit" class="btn-primary btn-full">
                            Log in
                        </button>
                    </form>

                    <p class="auth-footer">
                        Don’t have an account?
                        <a href="{{ route('register') }}">Create one</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
