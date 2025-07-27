@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content_header')
<div class="row">
    {{-- Books statistics --}}
    <div class="col-lg-4 col-6">
        <x-adminlte-info-box title="Livres empruntés" text="{{ 10 }}" icon="fas fa-book-open"
        theme="info" icon-theme="info" />
    </div>
    {{-- Approved/rejected requests --}}
    <div class="col-lg-4 col-6">
        <x-adminlte-info-box title="Demandes en attente" text="{{ 20 }}" icon="fas fa-clock"
        theme="warning" icon-theme="warning" />
    </div>
    {{-- Livres rendus --}}
    <div class="col-lg-4 col-6">
        <x-adminlte-info-box title="Livres rendus" text="{{ 30 }}" icon="fas fa-check-circle"
            theme="success" icon-theme="success" />
    </div>
    
</div>
@stop