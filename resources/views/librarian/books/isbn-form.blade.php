@extends('layouts.app')

@section('title', 'Ajouter un livre par ISBN')

@section('content_header')
    <h1 class="m-0 text-dark">Ajouter un livre par ISBN</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2"> {{-- Centering the card --}}
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
            @if($errors->any())
                <x-adminlte-alert theme="danger" title="Erreurs de validation">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-adminlte-alert>
            @endif

            <x-adminlte-card title="Rechercher un livre par ISBN" theme="info" icon="fas fa-barcode" >
                <form action="{{ route('librarian.books.isbn.getInfo') }}" method="GET">

                    <div class="form-group">
                        <label for="isbn_input">ISBN du livre</label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control @error('isbn') is-invalid @enderror"
                                   id="isbn_input"
                                   name="isbn"
                                   placeholder="Saisir l'ISBN ici..."
                                   value="{{ old('isbn') }}"
                                   required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info"> {{-- Changed color to match card theme --}}
                                    <i class="fas fa-magic"></i> Rechercher
                                </button>
                            </div>
                            @error('isbn')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            Ce livre sera recherché via l'API Google Books.
                        </small>
                    </div>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="text-muted mb-3">Ou si vous préférez, ajoutez le livre manuellement :</p>
                    <a href="{{ route('librarian.books.create') }}" class="btn btn-secondary btn-lg btn-block">
                        <i class="fas fa-pen"></i> Ajouter le livre manuellement
                    </a>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
