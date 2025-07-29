@extends('layouts.app')

@section('title', 'Rechercher des livres')

@section('content_header')
    <h1 class="m-0 text-dark">Rechercher des livres</h1>
@stop

@section('content')
    <div class="container-fluid"> {{-- Added container-fluid for consistent padding --}}
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
                                   placeholder="Rechercher par titre, auteur, catégorie ou ISBN..." {{-- Updated placeholder --}}
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
                        <div class="alert alert-light text-center p-4"> {{-- Removed border shadow-sm --}}
                            <i class="fas fa-search-minus fa-4x text-secondary mb-3"></i> {{-- Changed icon --}}
                            <h4>Aucun livre ne correspond à votre recherche.</h4>
                            <p class="lead text-muted">Veuillez essayer un autre terme de recherche.</p>
                            <a href="{{ route('student.books.index') }}" class="btn btn-info btn-lg">
                                <i class="fas fa-book"></i> Voir tous les livres
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($books as $book)
                                <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 d-flex align-items-stretch">
                                    <div class="card mb-4 w-100 h-100 book-card"> {{-- Removed shadow-sm --}}
                                        <a href="{{ route('student.books.show', $book->id) }}" class="text-decoration-none text-dark">
                                            @if ($book->image_url)
                                                <img src="{{ $book->image_url }}" class="card-img-top book-cover-img" alt="{{ $book->title }} Cover">
                                            @else
                                                <div class="card-img-top d-flex justify-content-center align-items-center bg-gradient-light" style="height: 250px;">
                                                    <i class="fas fa-book fa-6x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="card-body d-flex flex-column justify-content-center text-center" style="min-height: 100px;">
                                                <h5 class="card-title font-weight-bold mb-0">
                                                    {{ \Illuminate\Support\Str::limit($book->title, 50) }}
                                                </h5>
                                                @if($book->authors->isNotEmpty())
                                                    <p class="card-text text-muted text-sm mt-1">
                                                        {{ \Illuminate\Support\Str::limit($book->authors->pluck('name')->join(', '), 40) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </a>
                                        <div class="card-footer bg-light border-top-0 mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if($book->available_copies() > 0)
                                                        <span class="badge badge-success">{{ $book->available_copies() }} disponibles</span>
                                                    @else
                                                        <span class="badge badge-danger">0 disponible</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-success request-book-btn"
                                                            data-toggle="modal" data-target="#bookDetailsModal"
                                                            data-book-id="{{ $book->id }}"
                                                            title="Demander ce livre"
                                                            @if($book->available_copies() <= 0) disabled @endif>
                                                        <i class="fas fa-plus"></i> Demander
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $books->appends(['query' => $query])->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </x-adminlte-card>
            </div>
        </div>
    </div>

    @include('components.book-details-modal')
@stop

@section('css')
    @parent
    <style>
        .book-cover-img {
            height: 250px;
            object-fit: contain; /* Changed from cover to contain */
            width: 100%;
            background-color: #f8f9fa; /* Add a background color for images with transparency or letterboxing */
        }
        .book-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 100px;
            padding-bottom: 0.5rem;
        }
        .book-card .card-title {
            margin-bottom: 0;
        }
        .book-card .card-text {
            margin-top: 0.25rem;
        }
        .book-card .card-footer {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        /* Custom gradient for placeholder */
        .bg-gradient-light {
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
        }
    </style>
@endsection

@section('js')
    @parent
@stop
