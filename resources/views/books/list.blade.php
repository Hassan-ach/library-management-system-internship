@if(isset($error))
    <h1>{{ $error }}</h1>
@endif

@if(isset($books) && $books->count() > 0)
    <ul>
        @foreach($books as $book)
            <li><strong>{{ $book->title }}</strong><br>{{ $book->description }}</li>
        @endforeach
    </ul>
    {{ $books->links() }}
@elseif(!isset($error))
    <p>No books found.</p>
@endif

