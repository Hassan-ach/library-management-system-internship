<div class="container">
    <ul>
    @foreach ($publishers as $pub)
        <li>{{ $pub->name }}</li>
    @endforeach
    </ul>
</div>
 
{{ $publishers->links() }}