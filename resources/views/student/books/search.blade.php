{{-- resources/views/student/books/search.blade.php --}}
@extends('layouts.app')

@section('title', 'Rechercher des livres')

@section('content_header')
    <h1 class="m-0 text-dark">Rechercher des livres</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
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

            <x-adminlte-card title="Rechercher un livre" theme="info" icon="fas fa-search" collapsible maximizable>
                <form action="{{ route('student.books.search') }}" method="GET" class="form-inline mb-3">
                    <div class="input-group flex-grow-1">
                        <input type="search" name="query" class="form-control form-control-lg"
                               placeholder="Rechercher par titre"
                               value="{{ old('query', $query ?? '') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <hr>

                @if(isset($query) && $query)
                    <p class="lead">Résultats de la recherche pour: "<strong>{{ $query }}</strong>"</p>
                @else
                    <p class="lead">Veuillez entrer un terme de recherche ci-dessus.</p>
                @endif

                @if($books->isEmpty())
                    <p class="text-center">Aucun livre ne correspond à votre recherche.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Auteur(s)</th>
                                    <th>Catégorie(s)</th>
                                    <th>Copies disponibles</th>
                                    <th >Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $book)
                                    <tr>
                                        <td>{{ $book?->title }}</td>
                                        <td>
                                            @foreach($book?->authors as $author)
                                                {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                            @if($book?->authors->isEmpty()) N/A @endif
                                        </td>
                                        <td>
                                            @foreach($book?->categories as $category)
                                                {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                            @if($book?->categories->isEmpty()) N/A @endif
                                        </td>
                                        <td>
                                            @if($book?->available_copies() > 0)
                                                <span class="badge badge-success">{{ $book?->available_copies() }}</span>
                                            @else
                                                <span class="badge badge-danger">0</span>
                                            @endif
                                        </td>
                                        <td>
                                     <a href="{{route('student.books.show', $book?->id)}}" class="btn btn-xs btn-info" title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @if($book?->available_copies() > 0)
                                                <button type="button" class="btn btn-xs btn-success request-book-btn"
                                                        data-toggle="modal" data-target="#bookDetailsModal"
                                                        data-book-id="{{ $book?->id }}"
                                                        title="Demander ce livre">
                                                    <i class="fas fa-plus"></i> Demander
                                                </button>
                                            @else
                                                <button class="btn btn-xs btn-secondary" disabled title="Non disponible">
                                                    <i class="fas fa-ban"></i> Non dispo.
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $books->appends(['query' => $query])->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </x-adminlte-card>
        </div>
    </div>

    @include('components.book-details-modal')
@stop

