<div class="container">
    <ul>
    @foreach ($categories as $cat)
        <li>{{ $cat->label }} : {{ $cat->description }}</li>
    @endforeach
    </ul>
</div>
 
{{ $categories->links() }}