
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
</head>
<body>
    <h2>Welcome, {{ $user->first_name }} {{ $user->last_name }}</h2>

            @if ($requests)
    <h3>Your Book Requests</h3>
    <ul>
        @foreach($requests as $request)
            <li>
                {{ $request->book->title ?? 'Unknown Book' }}
                <ul>
                    @foreach($request->requestInfo as $info)
                        <li>Status: {{ $info->status }}, Date: {{ $info->created_at }}</li>
                    @endforeach
                </ul>
            </li>
        @endforeach

    </ul>
            @endif
</body>
</html>

