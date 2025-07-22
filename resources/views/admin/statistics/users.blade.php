<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Users</h1>
        
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
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
                    <tr>
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
                                <a href="{{ route('admin.statistics.users.history', $user->id) }}" 
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

        <div class="mt-3">
            <a href="{{ route('admin.statistics.users.export') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel mr-2"></i> Export as Excel
            </a>
        </div>
    </div>
</body>