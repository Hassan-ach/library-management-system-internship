<!-- include the modal structure-->
@include('librarian.widgets.raw-modal', [
    'item' => 'author',
    'Item' => 'Author',
    'icon'=> 'user-tie',
    'Icon' => 'user-tie',
    'style' => 'btn-outline-info btn-sm'
])      

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
        $('#authorsResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
        
        $.ajax({
            url: '/api/author/search', 
            type: 'GET',
            dataType: 'json',
            data: { 
                q: query
            },
            /*headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },*/
            success: function(response) {
                
                displayAuthorsResults(response);
            },
            error: function(xhr, status, error) {
                $('#authorsResults').html('<div class="text-danger">Search failed. Please try again.</div>');
            }
        });
    }
    
    function displayAuthorsResults(authors) {
        const container = $('#authorsResults');
        
        if (authors.length === 0) {
            container.html('<div class="text-muted">No items found</div>');
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
        const container = $('#selectedAuthorList');

        if (oldAuthors.length === 0 && newAuthors.length === 0) {
            container.html('No item selected yet');
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
    }

    function saveSelectedAuthors() {
        console.log('Saving old selected authors:', oldAuthors);
        console.log('Saving new selected authors:', newAuthors);
        // Close modal
        $('#authorsModal').modal('hide');
    }
</script>