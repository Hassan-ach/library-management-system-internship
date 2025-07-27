@extends('layouts.app')
@section('title', $page_title ?? 'Ajouter un livre')
@section('content_header')
    <h1 class="m-0 text-dark">{{$page_header ?? 'Ajouter un livre'}}</h1>
@stop
<!-- Input Group with Icon -->
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    
    <!-- tag-modal script-->
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#tagSearchInput').on('input', function(){
                const query = $(this).val().toLowerCase();
                if ( query.length >= 2){
                    searchTags(query);
                }
            });

            // Add tag to selection when clicked
            $(document).on('click', '.tag-search-item', function(e) {
                e.preventDefault();
                const tagId = $(this).data('tag-id');
                const tagName = $(this).data('tag-name');
                addTagToSelection(tagId, tagName);
            });

            // Create new tag
            $('#createTagBtn').click(function() {
                const tagName = $('#newTagInput').val().trim();
                if (tagName) {
                    createNewTag(tagName);
                }
            });

            // Remove tag from selection
            $(document).on('click', '.remove-tag-btn', function() {
                const tagName = $(this).data('tag-name');
                removeTagFromSelection(tagName);
            });

            // Save tags
            $('#saveTagsBtn').click(function() {
                saveSelectedTags();
            });
        });

        function searchTags(query) {
            $('#tagsResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des résultats...</div>');
            
            $.ajax({
                url: '/librarian/api/tag/search', 
                type: 'GET',
                dataType: 'json',
                data: { 
                    q: query
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    
                    displayTagsResults(response);
                },
                error: function(xhr, status, error) {
                    $('#tagsResults').html('<div class="text-danger">Échec de la recherche. Réessayez ultérieurement. </div>');
                }
            });
        }
        
        function displayTagsResults(tags) {
            const container = $('#tagsResults');
            
            if (tags.length === 0) {
                container.html('<div class="text-muted">Aucun résultat trouvé</div>');
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

        function addTagToSelection(tagId, tagName) {
            // Check if tag is already selected
            if (oldTags.find(tag => tag['name'] === tagName) || newTags.find(tag => tag === tagName)) {
                //nothing
                return;
            }
            // Tags with id == '*' means that are new 
            if ( tagId === '*'){
                newTags.push( tagName);
            }else{
                oldTags.push({ id: tagId, name: tagName });
            }
            updateSelectedTagsDisplay();
        }

        function createNewTag(tagName) {
            addTagToSelection( '*', tagName);
        }

        function removeTagFromSelection(tagName) {
            oldTags = oldTags.filter(tag => tag['name'] != tagName);
            newTags = newTags.filter(tag => tag != tagName);
            updateSelectedTagsDisplay();
        }

        function updateSelectedTagsDisplay() {
            fillHiddenInputs();

            const container = $('#selectedTagList');
            const form_container = $('#tags-display');

            if (oldTags.length === 0 && newTags.length === 0) {
                container.html('Aucune étiquette n\'est sélectionnée');
                form_container.html('Aucune étiquette n\'est sélectionnée');
                return;
            }

            let html = '';
            oldTags.forEach(tag => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-tag-name="${tag['name']}">
                        ${tag['name']}
                        <button type="button" class="btn btn-sm ml-1 remove-tag-btn" data-tag-name="${tag['name']}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            newTags.forEach(tag => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-tag-name="${tag}">
                        ${tag}
                        <button type="button" class="btn btn-sm ml-1 remove-tag-btn" data-tag-name="${tag}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            
            container.html(html);
            form_container.html(html);
        }

        function saveSelectedTags() {
            console.log('Saving old selected tags:', oldTags);
            console.log('Saving new selected tags:', newTags);
            // Close modal
            $('#tagsModal').modal('hide');
        }
    </script>
    
    <!-- publisher-modal script-->
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#publisherSearchInput').on('input', function(){
                const query = $(this).val().toLowerCase();
                if ( query.length >= 2){
                    searchPublishers(query);
                }
            });

            // Add publisher to selection when clicked
            $(document).on('click', '.publisher-search-item', function(e) {
                e.preventDefault();
                const Id = $(this).data('publisher-id');
                const Name = $(this).data('publisher-name');
                addPublisherToSelection( Id, Name);
            });

            // Create new publisher
            $('#createPublisherBtn').click(function() {
                const Name = $('#newPublisherInput').val().trim();
                if ( Name) {
                    createNewPublisher( Name);
                }
            });

            // Remove tag from selection
            $(document).on('click', '.remove-publisher-btn', function() {
                const Name = $(this).data('publisher-name');
                removePublisherFromSelection( Name);
            });

            // Save publishers
            $('#savePublishersBtn').click(function() {
                saveSelectedPublishers();
            });
        });

        function searchPublishers(query) {
            $('#publishersResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des résultats...</div>');
            
            $.ajax({
                url: '/librarian/api/publisher/search', 
                type: 'GET',
                dataType: 'json',
                data: { 
                    q: query
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    
                    displayPublishersResults(response);
                },
                error: function(xhr, status, error) {
                    $('#publishersResults').html('<div class="text-danger">Échec de la recherche. Réessayez ultérieurement.</div>');
                }
            });
        }
        
        function displayPublishersResults( publishers) {
            const container = $('#publishersResults');
            
            if (publishers.length === 0) {
                container.html('<div class="text-muted">Aucun résultat trouvé</div>');
                return;
            }

            let html = '';
            publishers.forEach(publisher => {
                html += `
                    <div class="publisher-search-item p-2 border-bottom" data-publisher-id="${publisher.id}" data-publisher-name="${publisher.name}" style="cursor: pointer;">
                        <i class="fas fa-tag mr-2"></i>${publisher.name}
                    </div>
                `; 
            });
            container.html(html);   
        }
        
        function addPublisherToSelection( Id, Name) {
            // Check if publisher is already selected
            if (oldPublishers.find(publisher => publisher['name'] === Name) || newPublishers.find(publisher => publisher === Name)) {
                //nothing
                return;
            }
            // Publishers with id == '*' means that are new 
            if ( Id === '*'){
                newPublishers.push( Name);
            }else{
                oldPublishers.push({ id: Id, name: Name });
            }
            updateSelectedPublishersDisplay();
        }

        function createNewPublisher( Name) {
            addPublisherToSelection( '*', Name);
        }

        function removePublisherFromSelection( Name) {
            oldPublishers = oldPublishers.filter(publisher => publisher['name'] != Name);
            newPublishers = newPublishers.filter(publisher => publisher != Name);
            updateSelectedPublishersDisplay();
        }

        function updateSelectedPublishersDisplay() {
            fillHiddenInputs();
            
            const container = $('#selectedPublisherList');
            const form_container = $('#publishers-display');

            if (oldPublishers.length === 0 && newPublishers.length === 0) {
                container.html('Aucun éditeur n\'est sélectionné');
                form_container.html('Aucun éditeur n\'est sélectionné');
                return;
            }

            let html = '';
            oldPublishers.forEach(publisher => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-publisher-name="${publisher['name']}">
                        ${publisher['name']}
                        <button type="button" class="btn btn-sm ml-1 remove-publisher-btn" data-publisher-name="${publisher['name']}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            newPublishers.forEach(publisher => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-publisher-name="${publisher}">
                        ${publisher}
                        <button type="button" class="btn btn-sm ml-1 remove-publisher-btn" data-publisher-name="${publisher}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            
            container.html(html);
            form_container.html(html);
        }

        function saveSelectedPublishers() {
            console.log('Saving old selected publishers:', oldPublishers);
            console.log('Saving new selected publishers:', newPublishers);
            // Close modal
            $('#publishersModal').modal('hide');
        }
    </script>

    <!-- category-modal script-->
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#categorySearchInput').on('input', function(){
                const query = $(this).val().toLowerCase();
                if ( query.length >= 2){
                    searchCategories(query);
                }
            });

            // Add category to selection when clicked
            $(document).on('click', '.cat-search-item', function(e) {
                e.preventDefault();
                const Id = $(this).data('cat-id');
                const Name = $(this).data('cat-name');
                addCategoryToSelection( Id, Name);
            });

            // Create new category
            $('#createCategoryBtn').click(function() {
                const Name = $('#newCategoryInput').val().trim();
                if ( Name) {
                    const description = $('#newCategoryDescription').val().trim();
                    if ( !description) {
                        createNewCategory( Name, 'aucune description');
                    }
                    createNewCategory( Name, description);
                }
            });

            // Remove category from selection
            $(document).on('click', '.remove-cat-btn', function() {
                const Name = $(this).data('cat-name');
                removeCategoryFromSelection( Name);
            });

            // Save categories
            $('#saveCategoriesBtn').click(function() {
                saveSelectedCategories();
            });
        });

        function searchCategories(query) {
            $('#categoriesResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des résultats....</div>');
            
            $.ajax({
                url: '/librarian/api/category/search', 
                type: 'GET',
                dataType: 'json',
                data: { 
                    q: query
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    
                    displayCategoriesResults(response);
                },
                error: function(xhr, status, error) {
                    $('#categoriesResults').html('<div class="text-danger">Échec de la recherche. Réessayez ultérieurement.</div>');
                }
            });
        }
        
        function displayCategoriesResults(categories) {
            const container = $('#categoriesResults');
            
            if (categories.length === 0) {
                container.html('<div class="text-muted">Aucun résultat trouvé</div>');
                return;
            }

            let html = '';
            categories.forEach(category => {
                html += `
                    <div class="cat-search-item p-2 border-bottom" data-cat-id="${category.id}" data-cat-name="${category.label}" style="cursor: pointer;">
                        <i class="fas fa-sitemap mr-2"></i>${category.label}
                    </div>
                `; 
            });
            container.html(html);   
        }
        
        function addCategoryToSelection( Id, Name, Description = "") {
            // Check if category is already selected
            if (oldCategories.find(category => category['name'] === Name) || newCategories.find(category => category[0] === Name)) {
                //nothing
                return;
            }
            // Categories with id == '*' means that are new 
            if ( Id === '*'){
                newCategories.push( [Name, Description]);
            }else{
                oldCategories.push({ id: Id, name: Name });
            }
            updateSelectedCategoriesDisplay();
        }

        function createNewCategory( Name, Description) {
            addCategoryToSelection( '*', Name, Description);
        }

        function removeCategoryFromSelection( Name) {
            oldCategories = oldCategories.filter(category => category['name'] != Name);
            newCategories = newCategories.filter(category => category[0] != Name);
            updateSelectedCategoriesDisplay();
        }

        function updateSelectedCategoriesDisplay() {
            fillHiddenInputs();
            
            const container = $('#selectedCategoriesList');
            const form_container = $('#categories-display');

            if (oldCategories.length === 0 && newCategories.length === 0) {
                container.html('Aucune catégorie n\'est sélectionnée');
                form_container.html('Aucune catégorie n\'est sélectionnée');

                return;
            }

            let html = '';
            oldCategories.forEach(category => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-cat-name="${category['name']}">
                        ${category['name']}
                        <button type="button" class="btn btn-sm ml-1 remove-cat-btn" data-cat-name="${category['name']}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            newCategories.forEach(category => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-cat-name="${category[0]}">
                        ${category[0]}
                        <button type="button" class="btn btn-sm ml-1 remove-cat-btn" data-cat-name="${category[0]}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            
            container.html(html);
            form_container.html(html);
        }

        function saveSelectedCategories() {
            console.log('Saving old selected categories:', oldCategories);
            console.log('Saving new selected categories:', newCategories);
            // Close modal
            $('#categoriesModal').modal('hide');
        }
    </script>

    <!-- author-modal script-->
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#authorSearchInput').on('input', function(){
                const query = $(this).val().toLowerCase();
                if ( query.length >= 2){
                    searchAuthors(query);
                }
            });

            // Add author to selection when clicked
            $(document).on('click', '.author-search-item', function(e) {
                e.preventDefault();
                const Id = $(this).data('author-id');
                const Name = $(this).data('author-name');
                addAuthorToSelection( Id, Name);
            });

            // Create new author
            $('#createAuthorBtn').click(function() {
                const Name = $('#newAuthorInput').val().trim();
                if ( Name) {
                    createNewAuthor( Name);
                }
            });

            // Remove author from selection
            $(document).on('click', '.remove-author-btn', function() {
                const Name = $(this).data('author-name');
                removeAuthorFromSelection( Name);
            });

            // Save authors
            $('#saveAuthorsBtn').click(function() {
                saveSelectedAuthors();
            });
        });

        function searchAuthors(query) {
            $('#authorsResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des résultats...</div>');
            
            $.ajax({
                url: '/librarian/api/author/search', 
                type: 'GET',
                dataType: 'json',
                data: { 
                    q: query
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    
                    displayAuthorsResults(response);
                },
                error: function(xhr, status, error) {
                    $('#authorsResults').html('<div class="text-danger">Échec de la recherche. Réessayez ultérieurement. </div>');
                }
            });
        }
        
        function displayAuthorsResults(authors) {
            const container = $('#authorsResults');
            
            if (authors.length === 0) {
                container.html('<div class="text-muted">Aucun résultat trouvé</div>');
                return;
            }

            let html = '';
            authors.forEach(author => {
                html += `
                    <div class="author-search-item p-2 border-bottom" data-author-id="${author.id}" data-author-name="${author.name}" style="cursor: pointer;">
                        <i class="fas fa-tag mr-2"></i>${author.name}
                    </div>
                `; 
            });
            container.html(html);   
        }
        
        function addAuthorToSelection( Id, Name) {
            // Check if author is already selected
            if (oldAuthors.find(author => author['name'] === Name) || newAuthors.find(author => author === Name)) {
                //nothing
                return;
            }
            // Authors with id == '*' means that are new 
            if ( Id === '*'){
                newAuthors.push( Name);
            }else{
                oldAuthors.push({ id: Id, name: Name });
            }
            updateSelectedAuthorsDisplay();
        }

        function createNewAuthor( Name) {
            addAuthorToSelection( '*', Name);
        }

        function removeAuthorFromSelection( Name) {
            oldAuthors = oldAuthors.filter(author => author['name'] != Name);
            newAuthors = newAuthors.filter(author => author != Name);
            updateSelectedAuthorsDisplay();
        }
        
        function updateSelectedAuthorsDisplay() {
            fillHiddenInputs();
            
            const container = $('#selectedAuthorList');
            const form_container = $('#authors-display');

            if (oldAuthors.length === 0 && newAuthors.length === 0) {
                container.html('Aucun auteur n\'est sélectionné');
                form_container.html('Aucun auteur n\'est sélectionné');
                return;
            }

            let html = '';
            oldAuthors.forEach(author => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-author-name="${author['name']}">
                        ${author['name']}
                        <button type="button" class="btn btn-sm ml-1 remove-author-btn" data-author-name="${author['name']}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            newAuthors.forEach(author => {
                html += `
                    <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-author-name="${author}">
                        ${author}
                        <button type="button" class="btn btn-sm ml-1 remove-author-btn" data-author-name="${author}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            
            container.html(html);
            form_container.html(html);
        }

        function saveSelectedAuthors() {
            console.log('Saving old selected authors:', oldAuthors);
            console.log('Saving new selected authors:', newAuthors);
            // Close modal
            $('#authorsModal').modal('hide');
        }
    </script>

    <script>
        let oldTags = @json($tags ?? []);      // Ids of old items - would be send with request 
        let newTags = [];      // infos of new items - would be send with request
        
        let oldCategories = @json($categories ?? []);        // Ids of old categories - would be send with request 
        let newCategories = [];      // infos of new categories - would be send with request

        let oldAuthors= @json($authors ?? []);        // Ids of old authors - would be send with request 
        let newAuthors = [];      // infos of new authors - would be send with request

        let oldPublishers = @json($publishers ?? []);        // Ids of old items - would be send with request 
        let newPublishers = [];      // infos of new items - would be send with request

        function updateSelectedItems() {
            updateSelectedAuthorsDisplay();
            updateSelectedPublishersDisplay();
            updateSelectedCategoriesDisplay();
            updateSelectedTagsDisplay();
        }

        function fillHiddenInputs(){ 
            document.getElementById('tags').value = JSON.stringify({
                "new": newTags,
                "old": toArray(oldTags)
            });

            // Set categories input
            document.getElementById('categories').value = JSON.stringify({
                "new": newCategories,
                "old": toArray(oldCategories)
            });

            // Set publishers input
            document.getElementById('publishers').value = JSON.stringify({
                "new": newPublishers,
                "old": toArray(oldPublishers)
            });

            // Set authors input
            document.getElementById('authors').value = JSON.stringify({
                "new": newAuthors,
                "old": toArray(oldAuthors)
            });
            
        }
        
        function toArray(arr){
            return(arr.map(item => item.id));
        }
        
        updateSelectedItems();
    </script>
@stop

@section('content')
<style>
    #publisherButtonStyle,#categoryButtonStyle,#authorButtonStyle,#tagButtonStyle{
        margin-top: 7px;
    }
</style>
<div class="container-fluid">
    <form id="bookForm" method="POST" action="{{ $action ?? route('librarian.books.store') }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <label>Titre du livre</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                    </div>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $title ?? '') }}" placeholder="Saisir le titre du livre">
                </div>
                    
                <label>ISBN</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    </div>
                    <input type="text" class="form-control" name="isbn" value="{{ old('isbn', $isbn ?? '') }}" placeholder="978-0-123456-78-9">
                </div>
                <small class="form-text text-muted">Format: 978-0-123456-78-9</small>
                

                <label>Date du publication</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" class="form-control" name="publication_date" value="{{ old('publication_date', $publication_date ?? '') }}" placeholder="">
                </div>

                <label>Nombre des pages</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                    </div>
                    <input type="number" class="form-control" name="number_of_pages" min="1" value="{{ old('number_of_pages', $number_of_pages ?? '') }}" placeholder="ex: 30">
                </div>

                <label>Nombre de copies</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-copy"></i></span>
                    </div>
                    <input type="number" class="form-control" name="total_copies" min="1" value="{{ old('total_copies', $total_copies ?? '') }}" placeholder="ex: 2">
                </div>

                <label>Description</label>
                <div>
                    <textarea class="form-control" name="description" rows="4" placeholder="Saisir une description détaillée du livre">{{ old('description', $description ?? '') }}</textarea>
                </div>
                <!-- Authors Display Section -->
                 @include('librarian.widgets.author-modal')
                
                <!-- Publishers Display Section -->
                 @include('librarian.widgets.publisher-modal')
                    
                <!-- Categories Display Section -->
                 @include('librarian.widgets.category-modal')
                
                <!-- Tags Display Section -->
                @include('librarian.widgets.tag-modal')
               
                <input type="hidden" name="tags" id="tags">
                <input type="hidden" name="categories" id="categories">
                <input type="hidden" name="publishers" id="publishers">
                <input type="hidden" name="authors" id="authors">
                
                <input name="_method" type="hidden" value="{{ $method ?? 'POST'}}">
                
                <div class="text-right">
                    <button type="submit" class="btn btn-success " style='width:10%'>
                        <i class="fas fa-save pr-2"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@stop