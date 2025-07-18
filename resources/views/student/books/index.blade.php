{{-- resources/views/student/books/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tous les livres')

@section('content_header')
    <h1 class="m-0 text-dark">Tous les livres</h1>
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

            <x-adminlte-card title="Liste des livres disponibles" theme="primary" icon="fas fa-book" collapsible removable maximizable>
                <div class="card-tools">
                    <a href="{{ route('student.books.search') }}" class="btn btn-tool btn-outline-secondary">
                        <i class="fas fa-search"></i> Rechercher un livre
                    </a>
                </div>
                <div class="card-body">
                    @if($books->isEmpty())
                        <p class="text-center">Aucun livre n'est disponible pour le moment.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Auteur(s)</th>
                                        <th>Catégorie(s)</th>
                                        <th>Copies disponibles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($books as $book)
                                        <tr>
                                            <td>{{ $book->title }}</td>
                                            <td>
                                                @forelse($book->authors as $author)
                                                    {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                                @empty
                                                    N/A
                                                @endforelse
                                            </td>
                                            <td>
                                                @forelse($book->categories as $category)
                                                    {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                                @empty
                                                    N/A
                                                @endforelse
                                            </td>
                                            <td>
                                                @php $copies = is_callable([$book, 'available_copies']) ? $book->available_copies() : $book->available_copies; @endphp
                                                <span class="badge badge-{{ $copies > 0 ? 'success' : 'danger' }}">{{ $copies }}</span>
                                            </td>
                                            <td>
                                     <a href="{{route('student.books.show', $book->id)}}" class="btn btn-xs btn-info" title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($copies > 0)
                                                    <button type="button" class="btn btn-xs btn-success request-book-btn"
                                                            data-toggle="modal" data-target="#bookDetailsModal"
                                                            data-book-id="{{ $book->id }}"
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

                        @if(method_exists($books, 'links'))
                            <div class="d-flex justify-content-center mt-3">
                                {{ $books->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>
            </x-adminlte-card>
        </div>
    </div>

    @include('components.book-details-modal')
@stop

@section('css')
    {{-- Custom CSS here if needed --}}
@stop

@section('js')
    @parent
    <script>
        const requestRouteBase = "{{ url('student/reqests/book') }}";
        const defaultImage = "{{ asset('images/default-book.png') }}";

        $(document).ready(function () {
            $('#bookDetailsModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var bookId = button.data('book-id');
                var modal = $(this);

                modal.find('.modal-title').text('Détails du livre');
                modal.find('.modal-body').html('<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des détails du livre...</p>');
                modal.find('#confirmRequestBtn').prop('disabled', true);

                $.ajax({
                    url: '{{ url("student/books") }}/' + bookId + '/details',
                    method: 'GET',
                    success: function (response) {
                        if (response.book) {
                            var book = response.book;
                            var authors = book.authors.map(a => a.name).join(', ') || 'N/A';
                            var categories = book.categories.map(c => c.name).join(', ') || 'N/A';
                            var publishers = book.publishers.map(p => p.name).join(', ') || 'N/A';
                            var tags = book.tags.map(t => t.name).join(', ') || 'N/A';

                            var modalBodyContent = `
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <img src="${book.image_link ?? defaultImage}" alt="${book.title}" class="img-fluid mb-3" style="max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                    <div class="col-md-8">
                                        <h4>${book.title}</h4>
                                        <p><strong>Auteur(s):</strong> ${authors}</p>
                                        <p><strong>Catégorie(s):</strong> ${categories}</p>
                                        <p><strong>Éditeur(s):</strong> ${publishers}</p>
                                        <p><strong>Tags:</strong> ${tags}</p>
                                        <p><strong>Date de publication:</strong> ${book.publication_date ? new Date(book.publication_date).toLocaleDateString('fr-FR') : 'N/A'}</p>
                                        <p><strong>Nombre de pages:</strong> ${book.number_of_pages ?? 'N/A'}</p>
                                        <p><strong>Copies disponibles:</strong> <span class="badge ${book.available_copies > 0 ? 'badge-success' : 'badge-danger'}">${book.available_copies}</span></p>
                                    </div>
                                </div>
                                <hr>
                                <h5>Description:</h5>
                                <p>${book.description || 'Aucune description disponible.'}</p>
                                <hr>
                                <form id="requestBookForm" action="${requestRouteBase}/${book.id}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            `;
                            modal.find('.modal-body').html(modalBodyContent);
                            modal.find('#confirmRequestBtn').prop('disabled', book.available_copies <= 0);
                        } else {
                            modal.find('.modal-body').html('<p class="text-center text-danger">Impossible de charger les détails du livre.</p>');
                            modal.find('#confirmRequestBtn').prop('disabled', true);
                        }
                    },
                    error: function (xhr, status, error) {
                        modal.find('.modal-body').html('<p class="text-center text-danger">Erreur: ' + (xhr.responseJSON?.message ?? error) + '</p>');
                        modal.find('#confirmRequestBtn').prop('disabled', true);
                    }
                });
            });

            $('#confirmRequestBtn').on('click', function () {
                $('#requestBookForm').submit();
            });
        });
    </script>
@stop

