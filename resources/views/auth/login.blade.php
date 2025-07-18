{{-- resources/views/auth/login.blade.php --}}
@extends('adminlte::auth.auth-page')


@section('auth_body')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">

        {{-- Illustration (only on md and above) --}}
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center bg-light">
            <img src="{{ asset('images/login-illustration.svg') }}"
                 alt="Library Illustration"
                 class="img-fluid p-4"
                 style="max-height: 500px;"
                 loading="lazy">
        </div>

        {{-- Login Form --}}
        <div class="col-md-6 col-12 d-flex align-items-center justify-content-center">
            <div class="card p-4 shadow-sm w-100" style="max-width: 400px;">
                <div class="text-center">
                    <img src="{{ asset('images/ensup-logo.png') }}"
                         alt="ENSUP Logo"
                         class="img-circle mb-2"
                         style="width: 80px; height: 80px;">
                    <h4 class="mb-3">Library Management System</h4>
                </div>

                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="input-group mb-3">
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Email" autocomplete="email" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password" autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Remember and Submit --}}
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                Sign In
                            </button>
                        </div>
                    </div>
                </form>

                @if (Route::has('password.request'))
                    <p class="mb-1 text-center">
                        <a href="{{ route('password.request') }}">I forgot my password</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@stop


@section('auth_footer')
    <p class="my-3 text-center small text-muted">
        ENSUP Library Management System v{{ config('project.version', '1.0.0') }}<br>
        &copy; {{ date('Y') }} <a href="https://www.ensup.ma" target="_blank">ENSUP</a>. Tous droits réservés.
    </p>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ensup-custom.css') }}">
@stop

