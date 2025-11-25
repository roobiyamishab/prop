<x-guest-layout>
    <div id="app">
        <div id="signup-page" class="page auth-page">
            <div class="auth-container">
                <div class="auth-card">
                    <h2 class="auth-title">Create your account</h2>
                    <p class="auth-subtitle">Join AIPropMatch today</p>

                    <form method="POST" action="{{ route('register') }}" class="auth-form">
                        @csrf

                        {{-- Full Name --}}
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                required
                            >
                            @error('name')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone Number --}}
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="+91 1234567890"
                                required
                            >
                            @error('phone')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                required
                            >
                            @error('email')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="••••••••"
                                required
                                autocomplete="new-password"
                            >
                            @error('password_confirmation')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary btn-full">
                            Create Account
                        </button>
                    </form>

                    <p class="auth-footer">
                        Already have an account?
                        <a href="{{ route('login') }}">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
