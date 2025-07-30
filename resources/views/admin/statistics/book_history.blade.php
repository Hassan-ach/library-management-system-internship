<!-- resources/views/admin/books/history.blade.php -->
@extends('admin.dashboard')

@section('content')
<div class="container">
    <br>
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4>
                    <i class="fas fa-history"></i> Historique d'emprunt: {{ $book->title }}
                </h4>
                <a href="{{ route('admin.statistics.books') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Date de sortie</th>
                            <th>Bibliothécaire (emprunt)</th>
                            <th>Retourné</th>
                            <th>Date de retour</th>
                            <th>Bibliothécaire (retour)</th>
                            <th>Durée d'emprint</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                        <tr class="{{ $borrowing['is_returned'] ? 'table-success' : 'table-warning' }}">
                            <td>{{ $borrowing['user_name'] }}</td>
                            <td>{{ $borrowing['borrow_date'] }}</td>
                            <td>{{ $borrowing['librarian_borrowed'] }}</td>
                            <td>
                                @if($borrowing['is_returned'])
                                    <span class="badge bg-success">Retourné</span>
                                @else
                                    <span class="badge bg-warning text-dark">Emprunté</span>
                                @endif
                            </td>
                            <td>{{ $borrowing['return_date'] ?? '-' }}</td>
                            <td>{{ $borrowing['librarian_returned'] ?? '-' }}</td>
                            <td>{{ $borrowing['duration'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucun historique d'emprunt trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $borrowings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection