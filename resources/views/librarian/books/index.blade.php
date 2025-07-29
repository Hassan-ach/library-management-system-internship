@extends('layouts.app')

@section('title', 'Tous les livres')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Tous les livres</h1>

        <form action="{{ route('librarian.books.search') }}" method="GET" class="ml-auto" style="width: 30%; max-width:350px;">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Chercher un livre..." value="{{ request('query') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
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
        @if( $books)
            <div class="row">
                @forelse ($books as $book)
                    <!-- if there is books to display  -->
                    <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 d-flex align-items-stretch">

                        <div class="card shadow-sm mb-4 w-100">
                            <a href="{{ route('librarian.books.show', $book->id) }}">
                            @if ($book->image_url)
                                <img src="{{ $book->image_url }}" class="card-img-top" alt="{{ $book->title }} Cover" style="height: 250px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex justify-content-center align-items-center bg-light" style="height: 250px;">
                                    <i class="fas fa-book fa-6x text-black-50"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-grow-1 justify-content-center">
                                <h5 class="card-title font-weight-bold" style="color:black">
                                    {{ \Illuminate\Support\Str::limit($book->title, 50) }}
                                </h5>
                            </div>
                            </a>
                            <div class="card-footer bg-light border-top-0 mt-auto">
                                <div class="row no-gutters">
                                    <div class='col-6 pr-2'>
                                        <a href="{{ route('librarian.books.edit', $book) }}" class="btn btn-sm btn-outline-primary w-100" title="Edit">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    </div>
                                    <div class='col-6 pl-2'>
                                        <button class="btn btn-sm btn-outline-danger w-100"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-book-id="{{ $book->id }}"
                                                data-book-title="{{ $book->title }}">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- if there is no books to display-->
                    <div class="col-12">
                        <div class="alert alert-light text-center">
                            <h4>Aucun livre disponible pour le moment</h4>
                            <p>La bibliothèque est vide. Commencez dès maintenant à ajouter vos premiers livres !</p>
                            <a href="{{ route('librarian.books.create') }}" class="btn btn-success">Ajouter un livre</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $books->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="col-12 mt-5">
            <div class="alert alert-light p-4 border shadow-sm">
            <div class="d-flex align-items-center">

                <div class="pr-4">
                    <i class="fas fa-search fa-4x text-info mb-3"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="lead text-muted">
                        {{ $message }}
                    </p>
                </div>
            </div>
            <a href="{{ route('librarian.books.index') }}" class="btn  btn-success" >
                retourner
            </a>
            </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression du livre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce livre :
                    <strong id="bookTitleToDelete"></strong> ?
                    <p class="text-danger mt-2">Une fois supprimé, le livre ne pourra plus être restauré.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>

                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>

    $('#deleteModal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);

        var bookId = button.data('book-id');
        var bookTitle = button.data('book-title');

        var action = '{{ url('librarian/books') }}/' + bookId;

        var modal = $(this);

        modal.find('#bookTitleToDelete').text(bookTitle);
        modal.find('#deleteForm').attr('action', action);
    });
</script>
@stop
