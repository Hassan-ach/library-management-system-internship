@extends('layouts.app')

@section('title', 'Détails du livre: ' . $book?->title)

@section('content_header')
    <h1 class="m-0 text-dark">Détails du livre</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Display success/error messages --}}
            @if(session('success'))
                <x-adminlte-alert theme="success" title="Succès">
                    {{ session('success') }}
                </x-adminlte-alert>
            @endif
            @if(session('error'))
                <x-adminlte-alert theme="danger" title="Erreur">
                    {{ session('error') }}
                </x-adminlte-alert>
            @endif

            <x-adminlte-card title="Informations sur le livre" theme="primary" icon="fas fa-book-reader" >
                <div class="card-body">
                    <div class="row">
                        {{-- Book Cover Image --}}
                        <div class="col-md-4 text-center mb-3">
                            <img src="{{ $book?->image_url ? $book?->image_url : asset('images/default-book.png') }}"
                                 alt="{{ $book?->title }}" class="img-fluid rounded shadow-sm" style="max-height: 300px; border: 1px solid #ddd; padding: 5px;">
                            <h4 class="mt-3">{{ $book?->title }}</h4>
                            <p class="text-muted">
                                @foreach($book?->authors as $author)
                                    {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                                @if($book?->authors->isEmpty()) Auteur inconnu @endif
                            </p>
                        <form id="sendRequestBookForm" action="{{ route('student.requests.create', $book?->id) }}" method="POST">
    @csrf
    @if($book?->available_copies() > 0)
        <button type="submit" class="btn btn-success">Confirmer la demande</button>
    @else
        <button class="btn btn-secondary mt-2" disabled title="Non disponible">
            <i class="fas fa-ban"></i> Non disponible
        </button>
    @endif
</form>
                        </div>

                        {{-- Book Details --}}
                        <div class="col-md-8">
                            <dl class="row">

                                <dt class="col-sm-4">Catégorie(s):</dt>
                                <dd class="col-sm-8">
                                    @foreach($book?->categories as $category)
                                        <span class="badge badge-info">{{ $category->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                    @endforeach
                                    @if($book?->categories->isEmpty()) N/A @endif
                                </dd>

                                <dt class="col-sm-4">Éditeur(s):</dt>
                                <dd class="col-sm-8">
                                    @foreach($book?->publishers as $publisher)
                                        {{ $publisher->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($book?->publishers->isEmpty()) N/A @endif
                                </dd>

                                <dt class="col-sm-4">Année de publication:</dt>
                                <dd class="col-sm-8">{{ $book?->publication_year ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Date de publication:</dt>
                                <dd class="col-sm-8">{{ $book?->publication_date ? $book?->publication_date->format('d/m/Y') : 'N/A' }}</dd>

                                <dt class="col-sm-4">Nombre de pages:</dt>
                                <dd class="col-sm-8">{{ $book?->pages ?? $book?->number_of_pages ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Tags:</dt>
                                <dd class="col-sm-8">
                                    @foreach($book?->tags as $tag)
                                        <span class="badge badge-secondary">{{ $tag->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                    @endforeach
                                    @if($book?->tags->isEmpty()) Aucun tag @endif
                                </dd>

                                <dt class="col-sm-4">Copies totales:</dt>
                                <dd class="col-sm-8">{{ $book?->total_copies }}</dd>

                                <dt class="col-sm-4">Copies disponibles:</dt>
                                <dd class="col-sm-8">
                                    @if($book?->available_copies() > 0)
                                        <span class="badge badge-success">{{ $book?->available_copies() }}</span>
                                    @else
                                        <span class="badge badge-danger">0</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Note moyenne:</dt>
                                <dd class="col-sm-8">
                                    @if($book?->average_rating)
                                        ({{$book?->average_rating}})
                                    @else
                                        Pas encore noté
                                    @endif
                                </dd>
                            </dl>

                            <h5 class="mt-4">Description:</h5>
                            <p>{{ $book?->description ?? 'Aucune description détaillée disponible pour ce livre.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('student.books.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop

