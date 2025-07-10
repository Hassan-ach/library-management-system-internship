<form action="{{ route('student.books.search') }}" method="GET">
    <input type="text" name="query" placeholder="Search books..." value="{{ request('query') ?? request('q') }}">
    <button type="submit">Search</button>
</form>

@if(isset($error))
    <h2>Error: "{{ $error }}"</h2>
@elseif(isset($books))
    <h2>Search Results for "{{ $query }}"</h2>

    @if($books->count() === 0)
        <p>No books found.</p>
    @else
        <ul>
            @foreach($books as $book)
                <li>
                    <strong>{{ $book->title }}</strong><br>
                    <p>{{ $book->description }}</p>
                </li>
            @endforeach
        </ul>

        {{-- Optional: pagination links if $books is a paginator --}}
        @if(method_exists($books, 'links'))
            {{ $books->links() }}
        @endif
    @endif
@endif

