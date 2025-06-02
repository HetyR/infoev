<x-layouts.backend>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f9f9f9;">
        <div class="col-md-6 col-lg-5 px-3">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-bottom-0 text-center pt-4">
                    <h4 class="fw-bold text-dark">{{ __('Welcome Back') }}</h4>                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('auth.login.store') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-muted fw-semibold">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control rounded-3 shadow-sm border" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-muted fw-semibold">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control rounded-3 shadow-sm border" name="password" required placeholder="••••••••">
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-outline-success rounded-pill fw-semibold">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <!-- Forgot Password -->
                        @if (Route::has('password.request'))
                            <div class="text-center mb-3">
                                <a class="text-decoration-none text-secondary small" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif

                        <!-- Divider -->
                        <div class="d-flex align-items-center my-3">
                            <hr class="flex-grow-1">
                            <span class="px-2 text-muted small">or sign in with</span>
                            <hr class="flex-grow-1">
                        </div>

                        <!-- Google Sign-in -->
                        <div class="d-grid">
                            <a href="{{ route('auth.google.redirect') }}" class="btn btn-outline-danger rounded-pill fw-semibold">
                                <i class="fab fa-google me-2"></i> {{ __('Google') }}
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-white text-center small text-muted py-3">
                    &copy; {{ now()->year }} Your App — All rights reserved
                </div>
            </div>
        </div>
    </div>
</x-layouts.backend>
