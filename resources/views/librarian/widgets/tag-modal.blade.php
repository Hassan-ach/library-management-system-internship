<!-- include the modal structure-->
@include('librarian.widgets.raw-modal', [
    'item' => 'item',
    'Item' => 'Item',
    'icon'=> 'tag',
    'Icon' => 'tag'
])      

<script>
    $(document).ready(function() {
        // Search functionality
        $('#itemSearchInput').on('input', function(){
            const query = $(this).val().toLowerCase();
            if ( query.length >= 2){
                searchItems(query);
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
        $('#createItemBtn').click(function() {
            const tagName = $('#newItemInput').val().trim();
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
        $('#saveItemsBtn').click(function() {
            saveSelectedTags();
        });
    });

    function searchItems(query) {
        $('#searchResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
        
        $.ajax({
            url: '/api/tag/search', 
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
                
                displaySearchResults(response);
            },
            error: function(xhr, status, error) {
                $('#searchResults').html('<div class="text-danger">Search failed. Please try again.</div>');
            }
        });
    }
    
    function displaySearchResults(tags) {
        const container = $('#searchResults');
        
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

    function addTagToSelection(tagId, tagName) {
        // Check if tag is already selected
        if (oldItems.find(tag => tag['label'] === tagName) || newItems.find(tag => tag === tagName)) {
            //nothing
            return;
        }
        // Items with id == '*' means that are new 
        if ( tagId === '*'){
            newItems.push( tagName);
        }else{
            oldItems.push({ id: tagId, label: tagName });
        }
        updateSelectedItemsDisplay();
    }

    function createNewTag(tagName) {
        addTagToSelection( '*', tagName);
    }

    function removeTagFromSelection(tagName) {
        oldItems = oldItems.filter(tag => tag['label'] != tagName);
        newItems = newItems.filter(tag => tag != tagName);
        updateSelectedItemsDisplay();
    }

    function updateSelectedItemsDisplay() {
        const container = $('#selectedItemList');

        if (oldItems.length === 0 && newItems.length === 0) {
            container.html('No item selected yet');
            return;
        }

        let html = '';
        oldItems.forEach(tag => {
            html += `
                <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-tag-name="${tag['label']}">
                    ${tag['label']}
                    <button type="button" class="btn btn-sm ml-1 remove-tag-btn" data-tag-name="${tag['label']}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        });
        newItems.forEach(tag => {
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
    }

    function saveSelectedTags() {
        console.log('Saving old selected tags:', oldItems);
        console.log('Saving new selected tags:', newItems);
        // Close modal
        $('#itemsModal').modal('hide');
    }
</script>