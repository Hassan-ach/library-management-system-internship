@extends('adminlte::page')
<!-- Input Group with Icon -->

@section('content')
<style>
    #publisherButtonStyle,#categoryButtonStyle,#authorButtonStyle,#tagButtonStyle{
        margin-top: 7px;
    }

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>    
<script>
    let oldTags = [];      // Ids of old items - would be send with request 
    let newTags = [];      // infos of new items - would be send with request
    
    let oldCategories = [];      // Ids of old categories - would be send with request 
    let newCategories = [];      // infos of new categories - would be send with request

    let oldAuthors= [];      // Ids of old authors - would be send with request 
    let newAuthors = [];      // infos of new authors - would be send with request

    let oldPublishers = [];      // Ids of old items - would be send with request 
    let newPublishers = [];      // infos of new items - would be send with request

    function updateSelectedItems() {
        const container = $('#tagsResults');
        
        if (tags.length === 0) {
            container.html('<div class="text-muted">No items found</div>');
            return;
        }

        let html = '';
        tags.forEach(tag => {
            html += `
                <div class="tag-search-item p-2 border-bottom" data-tag-id="${tag.id}" data-tag-name="${tag.label}" style="cursor: pointer;">
                    <i class="fas fa-tag mr-2"></i>${tag.label}
                </div>
            `; 
        });
        container.html(html);   
    }
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

    <div class="mt-3">
        <div id="tags-display" class="data-display empty">No tags selected</div>
    </div>
    @include('librarian.widgets.tag-modal')

    <!-- Categories Display Section -->
    <div class="mt-3">
        <div id="categories-display" class="data-display empty">No categories selected</div>
    </div>
    @include('librarian.widgets.author-modal')

    <!-- Authors Display Section -->
    <div class="mt-3">
        <div id="authors-display" class="data-display empty">No authors selected</div>
    </div>
    @include('librarian.widgets.publisher-modal')

    <!-- Publishers Display Section -->
    <div class="mt-3">
        <div id="publishers-display" class="data-display empty">No publishers selected</div>
    </div>
    @include('librarian.widgets.category-modal')
</div>

@stop