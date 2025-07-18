<head>
    {{-- <link rel="stylesheet" href="{{ asset('css/admin/users/index.css') }}"> --}}
    {{-- @vite(['resources/css/admin/users/index.css']) --}}
</head>
<body>
    <div class="container">
        <h1>Users</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->value }}</td>
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            <a href="{{ route('admin.statistics.users.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel mr-2"></i> Export as Excel
            </a>
        </div>
    </div>
</body>