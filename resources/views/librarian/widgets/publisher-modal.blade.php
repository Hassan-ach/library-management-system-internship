<!-- include the modal structure-->
@include('librarian.widgets.raw-modal', [
    'item' => 'publisher',
    'Item' => 'Publisher',
    'icon'=> 'building',
    'Icon' => 'building',
    'style'=> 'btn-outline-success btn-sm'
])      

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
        $('#publishersResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
        
        $.ajax({
            url: '/api/publisher/search', 
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
                
                displayPublishersResults(response);
            },
            error: function(xhr, status, error) {
                $('#publishersResults').html('<div class="text-danger">Search failed. Please try again.</div>');
            }
        });
    }
    
    function displayPublishersResults( publishers) {
        const container = $('#publishersResults');
        
        if (publishers.length === 0) {
            container.html('<div class="text-muted">No items found</div>');
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
        const container = $('#selectedPublisherList');

        if (oldPublishers.length === 0 && newPublishers.length === 0) {
            container.html('No item selected yet');
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
    }

    function saveSelectedPublishers() {
        console.log('Saving old selected publishers:', oldPublishers);
        console.log('Saving new selected publishers:', newPublishers);
        // Close modal
        $('#publishersModal').modal('hide');
    }
</script>