@extends('admin.dashboard')

@section('css')

@endsection

@section('content')
    <div class="container">
        <h1>Bibliothecaires</h1>
        {{-- Search and filter --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Chercher des etudiants</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.statistics.librarians.search') }}" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <label for="search" class="form-label">Search Term</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            placeholder="Search by name, email, etc." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" style="cursor: pointer;">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i> Search
                        </button>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <a href="{{ route('admin.statistics.librarian') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        @if(request()->hasAny(['search', 'role', 'status']))
            <div class="alert alert-info mb-3">
                Showing results for:
                @if(request('search')) <strong>Search:</strong> {{ request('search') }} @endif
                @if(request('role')) <strong>Role:</strong> {{ ucfirst(request('role')) }} @endif
                @if(request('status')) <strong>Status:</strong> {{ ucfirst(request('status')) }} @endif
                <a href="{{ route('admin.statistics.librarian') }}" class="float-end">Show all</a>
            </div>
        @endif

        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>History</th>
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
                            <td>{{ $user->role->value }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
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
    </div>
@endsection
