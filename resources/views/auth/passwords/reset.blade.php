{{-- resources/views/auth/passwords/reset.blade.php --}}
@extends('adminlte::auth.auth-page')

@section('auth_header', 'Réinitialiser le mot de passe')

@section('auth_body')
    <div class="login-box-msg">
        <img src="{{ asset('images/ensup-logo.png') }}" alt="ENSUP Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 80px; height: 80px;">
        <p class="login-box-msg mt-2">ENSUP Library</p>
    </div>

    <p class="login-box-msg">{{ __('adminlte::adminlte.password_reset_message') }}</p>

    <form action="{{ route('password.update') }}" method="post">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ $email ?? old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.retype_password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">{{ __('adminlte::adminlte.reset_password') }}</button>
            </div>
        </div>
    </form>

    <p class="mt-3 mb-1">
        <a href="{{ route('login') }}">
            {{ __('adminlte::adminlte.login_link') }}
        </a>
    </p>
@stop

@section('auth_footer')
    <p class="my-3 text-center">
        ENSUP Library Management System v{{ config('project.version', '1.0.0') }}<br>
        Copyright © {{ date('Y') }} <a href="#">ENSUP</a>. All rights reserved.
    </p>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ensup-custom.css') }}">
@stop

