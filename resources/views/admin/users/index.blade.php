@extends('admin.dashboard')

@section('css')
<style>
    .table-container {
        max-height: 370px;
        overflow-y: auto;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    .table-container table {
        margin-bottom: 0;
    }
    .sticky-header th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container mb-4">
    <h1>Users</h1>
        
    {{-- Search and filter --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Search Users</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.search') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Term</label>
                    <input type="text" class="form-control" id="search" name="search" 
                        placeholder="Search by name, email, etc." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" style="cursor: pointer;">
                        <option value="">All Roles</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="librarian" {{ request('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                    </select>
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
                    <a href="{{ route('admin.users.all') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Create user --}}
    <div class="col-md-6 d-flex align-items-end mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus me-2"></i> Create user
        </a>
    </div>

    {{-- Search results info --}}
    @if(request()->hasAny(['search', 'role', 'status']))
        <div class="alert alert-info mb-3">
            Showing results for:
            @if(request('search')) <strong>Search:</strong> {{ request('search') }} @endif
            @if(request('role')) <strong>Role:</strong> {{ ucfirst(request('role')) }} @endif
            @if(request('status')) <strong>Status:</strong> {{ ucfirst(request('status')) }} @endif
            <a href="{{ route('admin.users.all') }}" class="float-end">Show all</a>
        </div>
    @endif

    {{-- Users table with scroll --}}
    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead class="sticky-header">
                <tr>
                    <th>Id</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
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
                            <a href="{{ route('admin.users.update', $user->id) }}" 
                               class="btn btn-warning btn-sm" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div >
        {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection