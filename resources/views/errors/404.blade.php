{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app') {{-- Optional layout --}}

@section('content')
    <div style="text-align: center; margin-top: 100px;">
        <h1>404 - Page Not Found</h1>
        <p>Sorry, the page you are looking for does not exist.</p>
        <a href="{{ url('/') }}">Go to Homepage</a>
    </div>
@endsection

