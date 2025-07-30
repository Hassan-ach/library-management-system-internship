@extends('admin.dashboard')

@section('css')

@endsection

@section('content')
    <div class="container">
        <h1>Bibliothécaires</h1>
        {{-- Search and filter --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Chercher des bibliothécaires</h5>
            </div>
            <div class="card-body">
                <div class="container col-11">
                    <form action="{{ route('admin.statistics.librarians.search') }}" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <label for="search" class="form-label">critères de recherches</label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Chercher par id, nom, email, etc..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status" style="cursor: pointer;">
                                <option value="">Tout les status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Chercher
                            </button>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <a href="{{ route('admin.statistics.librarian') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i> Effacer
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(request()->hasAny(['search', 'role', 'status']))
            <div class="alert alert-info mb-3">
                Afficher les resultats pour
                @if(request('search')) <strong> {{ request('search') }} </strong> @endif
                @if(request('role')) <strong> {{ ucfirst(request('role')) }} </strong>@endif
                @if(request('status')) <strong> {{ ucfirst(request('status')) }} </strong>@endif
                <a href="{{ route('admin.statistics.librarian') }}" class="float-end">Afficher tout</a>
            </div>
        @endif

        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>E-mail</th>
                    <th>Rôle</th>
                    <th style="text-align: center;">Statut</th>
                    <th style="text-align: center;">Historique</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @if($user->role->value === 'librarian')
                        <tr>
                            <td> {{$user->id}} </td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->role->value === 'student')
                                    <p class="mb-0">étudiant</p>
                                @elseif ($user->role->value === 'admin')
                                    <p class="mb-0">admin</p>
                                @elseif ($user->role->value === 'librarian')
                                    <p class="mb-0">bibliothécaire</p>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.statistics.librarian_history', $user->id) }}"
                                    class="btn btn-secondary btn-sm"
                                    title="View History">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('admin.statistics.librarian.export') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel mr-2"></i> Exporter
            </a>
        </div>

        {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
