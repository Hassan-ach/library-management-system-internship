<div class="container">
    @foreach ($authors as $author)
        {{ $author->name }}
    @endforeach
</div>
 
{{ $authors->links() }}