<div class="container">
    <ul>
    @foreach ($tags as $tag)
        <li>{{ $tag->label }}</li>
    @endforeach
    </ul>
</div>
 
{{ $tags->links() }}