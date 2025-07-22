@extends('adminlte::page')
<!-- Input Group with Icon -->

@section('content')
<script>
    let oldItems = [];      // Ids of old items - would be send with request 
    let newItems = [];      // infos of new items - would be send with request
    
    let oldCategories = [];      // Ids of old categories - would be send with request 
    let newCategories = [];      // infos of new categories - would be send with request

    let oldAuthors= [];      // Ids of old authors - would be send with request 
    let newAuthors = [];      // infos of new authors - would be send with request

    let oldPublishers = [];      // Ids of old items - would be send with request 
    let newPublishers = [];      // infos of new items - would be send with request
</script>

<div class="form-group">
    <label>Book Title</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-book"></i></span>
        </div>
        <input type="text" class="form-control" placeholder="Enter book title">
    </div>
        
    <label>ISBN</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
        </div>
        <input type="text" class="form-control" placeholder="978-0-123456-78-9">
    </div>
    <small class="form-text text-muted">Format: 978-0-123456-78-9</small>
    

    <label>Publication Date</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
        </div>
        <input type="date" class="form-control" placeholder="Select publication date">
    </div>

    <label>Number of Pages</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
        </div>
        <input type="number" class="form-control" min="1" placeholder="Enter page count">
    </div>

    <label>Total Copies</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-copy"></i></span>
        </div>
        <input type="number" class="form-control" min="1" value="1" placeholder="Enter total copies">
    </div>

    <label>Description</label>
    <div>
        <textarea class="form-control" rows="4" placeholder="Enter book description..."></textarea>
    </div>
    
    @include('librarian.widgets.tag-modal')
    <!-- @include('librarian.widgets.author-modal') -->
</div>

@stop