<div class="container">
    @foreach ($publishers as $pub)
        {{ $pub->name }}
    @endforeach
</div>
 
{{ $publishers->links() }}