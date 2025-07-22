<head>
    {{-- Your existing head content --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Users</h1>
        {{-- <ul style="list-style: none;display:flex;flex-direction: row; gap: 10px;">
            <li><a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create user</a></li>
        </ul> --}}
        
    {{-- <<<<<<<<<<<<<< search and filter --}}
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
                <div class="col-md-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">All Roles</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="librarian" {{ request('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i> Search
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.users.all') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>
    {{-- >>>>>>>>>>>>>> search and filter --}}
    
    {{-- <<<<<<<<<<<<<< Create user --}}
    
    <ul style="list-style: none;display:flex;flex-direction: row; gap: 10px;">
        <li><a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create user</a></li>
    </ul>

    {{-- >>>>>>>>>>>>>> Create user --}}

    {{-- <<<<<<<<<<<<<< show user --}}
    @if(request()->hasAny(['search', 'role', 'status']))
        <div class="alert alert-info mb-3">
            Showing results for:
            @if(request('search')) <strong>Search:</strong> {{ request('search') }} @endif
            @if(request('role')) <strong>Role:</strong> {{ ucfirst(request('role')) }} @endif
            @if(request('status')) <strong>Status:</strong> {{ ucfirst(request('status')) }} @endif
            <a href="{{ route('admin.users.all') }}" class="float-end">Show all</a>
        </div>
    @endif
    {{-- >>>>>>>>>>>>>> show user --}}
    



    {{-- <<<<<<<<<<<<<< list users --}}
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                            <div class="d-flex gap-2">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.users.update', $user->id) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit">
                                    <i style="padding: 0px 0px -10px 0px" class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Delete Button with Confirmation -->
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            {{ $users->withQueryString()->links() }}
    </div>
    {{-- >>>>>>>>>>>>>> list users --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>