@extends('admin.dashboard')

@section('css')

@endsection

@section('content')
    <div class="container">
        <h1>Bibliothecaires</h1>
        
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
                                    <a href="{{ route('admin.statistics.users.history', $user->id) }}" 
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
