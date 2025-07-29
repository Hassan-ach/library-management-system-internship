{{-- resources/views/layouts/app.blade.php --}}
@extends('adminlte::page')

@section('title', 'ENSUP Library - ' . (isset($title) ? $title : 'Dashboard'))

@section('content_header')
    {{-- Breadcrumbs will go here, AdminLTE handles this via config or @section('plugins.Datatables', true) --}}
    <h1>{{ isset($page_title) ? $page_title : 'Dashboard' }}</h1>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Add any page-specific JS here if needed --}}
    <script>
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
