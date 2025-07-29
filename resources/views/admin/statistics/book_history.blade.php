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
                            <th>1Utilisateur</th>
                            <th>2Date de sortie</th>
                            <th>3Bibliothécaire (emprunt)</th>
                            <th>4Retourné</th>
                            <th>5Date de retour</th>
                            <th>6Bibliothécaire (retour)</th>
                            <th>7Durée d'emprint</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                        <tr class="{{ $borrowing['is_returned'] ? 'table-success' : 'table-warning' }}">
                            <td>1{{ $borrowing['user_name'] }}</td>
                            <td>2{{ $borrowing['borrow_date'] }}</td>
                            <td>3{{ $borrowing['librarian_borrowed'] }}</td>
                            <td>4
                                @if($borrowing['is_returned'])
                                    <span class="badge bg-success">Retourné</span>
                                @else
                                    <span class="badge bg-warning text-dark">Emprunté</span>
                                @endif
                            </td>
                            <td>5{{ $borrowing['return_date'] ?? '-' }}</td>
                            <td>6{{ $borrowing['librarian_returned'] ?? '-' }}</td>
                            <td>7{{ $borrowing['duration'] }}</td>
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