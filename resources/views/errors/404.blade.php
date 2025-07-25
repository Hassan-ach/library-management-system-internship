@extends('layouts.app')

@section('title', 'Page non trouvée - 404')

@section('content_header')
    <h1 class="m-0 text-dark">Erreur 404</h1>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oups! Page non trouvée.</h3>

            <p>
                Nous n'avons pas pu trouver la page que vous recherchiez.
                Vous pouvez <a href="{{ route('student.dashboard') }}">retourner au tableau de bord</a> ou essayer d'utiliser la recherche.
            </p>

        </div>
    </div>
@stop
