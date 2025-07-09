<!DOCTYPE html>
<html>
<head>
    <title>Book Request Details</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .card { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .card h3 { margin-top: 0; }
        .btn { display: inline-block; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

    <h2>📚 Book Request Details</h2>

    <div class="card">
        <h3>Book Request Info</h3>
        <p><strong>Request ID:</strong> {{ $bookReq->id }}</p>
        <p><strong>Book ID:</strong> {{ $bookReq->book_id }}</p>
        <p><strong>User ID:</strong> {{ $bookReq->user_id }}</p>
    </div>

    <div class="card">
        <h3>Latest Status</h3>
        @if($reqInfo)
            <p><strong>Status:</strong> {{ $reqInfo->status }}</p>
            <p><strong>Updated at:</strong> {{ $reqInfo->created_at }}</p>
        @else
            <p>No request info found.</p>
        @endif
    </div>

    <a href="#" class="btn">⬅ Back</a>

</body>
</html>

