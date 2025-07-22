<div class="container mt-5">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#{{ $item }}sModal">
        <i class="fas fa-{{ $Icon }}"></i> Manage Items
    </button>
</div>

<div class="modal fade" id="{{ $item }}sModal" tabindex="-1" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Manage {{ $item }}s</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Search Items -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Search Existing {{ $item }}s</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" id="{{ $item }}SearchInput" 
                                placeholder="Search for existing {{ $item }}s...">
                        </div>
                        <!-- Search Results -->
                        <div id="searchResults" style="max-height: 200px; overflow-y: auto;">
                            <!-- Dynamic search results will appear here -->
                        </div>
                    </div>
                </div>
                <!-- Create Items-->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Create new {{ $item }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-{{ $icon }}"></i></span>
                            </div>
                            <input type="text" class="form-control" id="newItemInput" 
                                    placeholder="Enter new {{ $item }} name...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="create{{ $Item }}Btn">
                                    <i class="fas fa-plus"></i> Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Items-->
                                
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Selected {{ $item }}s</h3>
                        
                    </div>
                    <div class="card-body">
                        <div id="selected{{ $Item }}List" style="max-height: 200px; overflow-y: auto;">
                            <!-- Selected items will appear here dynamically -->
                            No item selected yet
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id ="cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save{{ $Item }}sBtn" data-dismiss="modal">Save Items</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>