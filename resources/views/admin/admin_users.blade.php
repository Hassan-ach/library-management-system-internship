<div>
    @if(isset($error))
        <h1>{{ $error }}</h1>
    @endif

    @if(isset($users) && $users->count() > 0)

        <table>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Is active</th>
                <th>Role</th>
            </tr>

            @foreach($users as $user)
                <tr>$user->first_name</tr>
                <tr>$user->last_name</tr>
                <tr>$user->email</tr>
                <tr>$user->password</tr>
                <tr>$user->is_active</tr>
                <tr>$user->role</tr>
            @endforeach

        </table>

        
        {{ $books->links() }}
    @elseif(!isset($error))
        <p>No books found.</p>
    @endif


</div>
