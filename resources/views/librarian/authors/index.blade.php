<div class="container">
    <ul>
    @foreach ($authors as $author)
        <li>{{ $author->name }}</li>
    @endforeach
    </ul>
</div>
 
{{ $authors->links() }}