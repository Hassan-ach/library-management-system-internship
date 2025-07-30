@extends('admin.dashboard')

@section('css')
<style>
    .scrollable-table-container {
        max-height: 45%; 
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .sticky-header th {
        position: sticky;
        top: 0;
        background: #343a40;
        color: white;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1>Nos Livres</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Rechercher des livres</h5>
        </div>
        <div class="card-body">
            <div class="container col-11">
                <form action="{{ route('admin.statistics.books.search') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <label for="search" class="form-label">critères de recherches</label>
                    <input type="text" class="form-control" id="search" name="search"
                        placeholder="Rechercher par titre, ISBN, etc..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i> Rechercher
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    @if(request()->hasAny(['search', 'availability']))
        <div class="alert alert-info mb-3">
            Résultats pour :
            @if(request('search')) <strong>Recherche :</strong> {{ request('search') }} @endif
            @if(request('availability')) 
                <strong>Disponibilité :</strong> 
                {{ request('availability') == 'available' ? 'Disponibles' : 'Non disponibles' }}
            @endif
            <a href="{{ route('admin.statistics.books') }}" class="float-end">Afficher tous</a>
        </div>
    @endif

    <div class="scrollable-table-container">
        <table class="table table-striped table-hover">
            <thead class="thead-dark sticky-header">
                <tr>
                    <th>Titre</th>
                    <th>ISBN</th>
                    <th>Nombre des pages</th>
                    <th>Copies total</th>
                    <th>history</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book?->title }}</td>
                        <td>{{ $book?->isbn }}</td>
                        <td>{{ $book?->number_of_pages }}</td>
                        <td>{{ $book?->total_copies }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.statistics.book.history', $book?->id) }}"
                                class="btn btn-secondary btn-sm"
                                title="View History">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="{{ route('admin.statistics.books.export') }}" class="btn btn-success mr-2">
            <i class="fas fa-file-excel mr-2"></i> Exporter
        </a>
    </div>
    {{ $books->withQueryString()->links('pagination::bootstrap-5') }}
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
