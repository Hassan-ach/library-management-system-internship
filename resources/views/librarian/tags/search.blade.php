<div class="container">
    @foreach ($tags as $tag)
        {{ $tag->label }}
    @endforeach
</div>
 
{{ $tags->links() }}