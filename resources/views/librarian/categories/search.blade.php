<div class="container">
    @foreach ($categories as $cat)
        {{ $cat->label }}
    @endforeach
</div>
 
{{ $categories->links() }}