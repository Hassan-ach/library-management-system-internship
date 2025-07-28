<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
{{-- resources/views/layouts/app.blade.php --}}
@extends('adminlte::page')

@section('title', 'ENSUP Library - ' . (isset($title) ? $title : 'Dashboard'))

@section('content_header')
    
    @if(isset($breadcrumbs))
        <ol class="breadcrumb float-sm-right">
            @foreach($breadcrumbs as $label => $url)
                @if($loop->last)
                    <li class="breadcrumb-item active">{{ $label }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                @endif
            @endforeach
        </ol>
    @endif
@stop

@section('content')
    {{-- Your page content will be injected here --}}
    
@stop
@section('footer')
    <div class="float-right d-none d-sm-inline">
        ENSUP Library Management System v{{ config('adminlte.version') }}
    </div>
    <strong>Copyright © {{ date('Y') }} <a href="#">ENSUP</a>.</strong> All rights reserved.
@stop

@section('css')
    {{-- Add any page-specific CSS here if needed --}}
@stop

@section('js')
    {{-- Add any page-specific JS here if needed --}}
    <script>
        // Example for Toastr notifications
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
    </script>
@stop

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-sidebar {
            background: #343a40;
            min-height: 100vh;
            padding: 20px;
            color: white;
        }
        .admin-main {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <h4>Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users.all') }}">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.statistics.dashboard') }}">
                            <i class="fas fa-chart-line me-2"></i> Statistics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.settings.get') }}">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.profile') }}">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-9 col-lg-10 admin-main">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> --}}