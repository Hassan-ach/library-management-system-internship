<form action="{{ route('student.books.search') }}" method="GET">
    <input type="text" name="query" placeholder="Search books..." value="{{ request('query') ?? request('q')   }}">
    <button type="submit">Search</button>
</form>

@if(isset($books))
    <h2>Search Results for "{{ $query }}"</h2>

    @if($books->isEmpty())
        <p>No books found.</p>
    @else
        <ul>
            @foreach($books as $book)
                <li>{{ $book->title }}</li>
            <p> {{$book->description}}</p>
            @endforeach
        </ul>
    @endif
@endif

