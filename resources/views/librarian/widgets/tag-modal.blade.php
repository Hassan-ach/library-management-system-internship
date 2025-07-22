<!-- include the modal structure-->
@include('librarian.widgets.raw-modal', [
    'item' => 'tag',
    'Item' => 'Tag',
    'icon'=> 'tag',
    'Icon' => 'tag',
    'style'=> 'btn-outline-warning btn-sm'
])      

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
        $('#tagsResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
        
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
                
                displayTagsResults(response);
            },
            error: function(xhr, status, error) {
                $('#tagsResults').html('<div class="text-danger">Search failed. Please try again.</div>');
            }
        });
    }
    
    function displayTagsResults(tags) {
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

    function addTagToSelection(tagId, tagName) {
        // Check if tag is already selected
        if (oldTags.find(tag => tag['label'] === tagName) || newTags.find(tag => tag === tagName)) {
            //nothing
            return;
        }
        // Tags with id == '*' means that are new 
        if ( tagId === '*'){
            newTags.push( tagName);
        }else{
            oldTags.push({ id: tagId, label: tagName });
        }
        updateSelectedTagsDisplay();
    }

    function createNewTag(tagName) {
        addTagToSelection( '*', tagName);
    }

    function removeTagFromSelection(tagName) {
        oldTags = oldTags.filter(tag => tag['label'] != tagName);
        newTags = newTags.filter(tag => tag != tagName);
        updateSelectedTagsDisplay();
    }

    function updateSelectedTagsDisplay() {
        const container = $('#selectedTagList');
        const form_container = $('#tags-display');

        if (oldTags.length === 0 && newTags.length === 0) {
            container.html('No item selected yet');
            form_container.html('No tags selected');
            return;
        }

        let html = '';
        oldTags.forEach(tag => {
            html += `
                <div class="badge badge-primary badge-pill mr-2 mb-2 selected-tag" data-tag-name="${tag['label']}">
                    ${tag['label']}
                    <button type="button" class="btn btn-sm ml-1 remove-tag-btn" data-tag-name="${tag['label']}" style="background: none; border: none; color: white; padding: 0; margin-left: 5px;">
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