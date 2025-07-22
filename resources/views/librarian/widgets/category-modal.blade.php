<!-- include the modal structure-->
<div id="categoryButtonStyle">
    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#categoriesModal">
        <i class="fas fa-sitemap"></i> Manage categories
    </button>
</div>

<div class="modal fade" id="categoriesModal" tabindex="-1" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Manage Categories</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Search Items -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Search Existing Categories</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" id="categorySearchInput" 
                                placeholder="Search for existing categories...">
                        </div>
                        <!-- Search Results -->
                        <div id="categoriesResults" style="max-height: 200px; overflow-y: auto;">
                            <!-- Dynamic search results will appear here -->
                        </div>
                    </div>
                </div>
                <!-- Create Items-->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Create new category</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                            </div>
                            <input type="text" class="form-control" id="newCategoryInput" 
                                    placeholder="Enter new category ...">
                            <input type="text" class="form-control" id="newCategoryDescription" 
                                    placeholder="Enter a description for the category ...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="createCategoryBtn">
                                    <i class="fas fa-plus"></i> Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Items-->
                                
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Selected categories</h3>
                        
                    </div>
                    <div class="card-body">
                        <div id="selectedCategoriesList" style="max-height: 200px; overflow-y: auto;">
                            <!-- Selected items will appear here dynamically -->
                            No item selected yet
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id ="cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCategoriesBtn" data-dismiss="modal">Save Items</button>
                </div>
            </div>
        </div>
    </div>
</div>
        
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
                    createNewCategory( Name, 'no description');
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
        $('#categoriesResults').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
        
        $.ajax({
            url: '/api/category/search', 
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
                
                displayCategoriesResults(response);
            },
            error: function(xhr, status, error) {
                $('#categoriesResults').html('<div class="text-danger">Search failed. Please try again.</div>');
            }
        });
    }
    
    function displayCategoriesResults(categories) {
        const container = $('#categoriesResults');
        
        if (categories.length === 0) {
            container.html('<div class="text-muted">No items found</div>');
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
        const container = $('#selectedCategoriesList');
        const form_container = $('#categories-display');

        if (oldCategories.length === 0 && newCategories.length === 0) {
            container.html('No item selected yet');
            form_container.html('No categories selected');

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