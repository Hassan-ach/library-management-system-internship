@extends('layouts.app')

@section('title', 'Tous les livres')

@section('content_header')
    <h1 class="m-0 text-dark">Tous les livres</h1>
@stop

@section('content')
    <div class="container-fluid">
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

        <x-adminlte-card title="Liste des livres disponibles" theme="primary" icon="fas fa-book" >
            <div class="card-tools">
                <a href="{{ route('student.books.search') }}" class="btn btn-tool btn-outline-secondary">
                    <i class="fas fa-search"></i> Rechercher un livre
                </a>
            </div>
            <div class="card-body">
                @if($books->isEmpty())
                    <div class="alert alert-light text-center p-4"> {{-- Removed border shadow-sm --}}
                        <i class="fas fa-box-open fa-4x text-secondary mb-3"></i>
                        <h4>Aucun livre disponible pour le moment</h4>
                        <p class="lead text-muted">Veuillez revenir plus tard ou essayer de rechercher un livre spécifique.</p>
                        <a href="{{ route('student.books.search') }}" class="btn btn-info btn-lg">
                            <i class="fas fa-search"></i> Rechercher un livre
                        </a>
                    </div>
                @else
                    <div class="row">
                        @foreach ($books as $book)
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
                        {{ $books->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </x-adminlte-card>
    </div>

    {{-- Include the Book Details Modal Component --}}
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
    {{-- Removed the hover effect JavaScript --}}
@stop
