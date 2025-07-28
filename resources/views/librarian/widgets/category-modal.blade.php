<div class="form-group">
    <div class="mt-3">
        <div id="categories-display" class="data-display empty">Aucune catégorie n'est sélectionnée</div>
    </div>
    <div id="categoryButtonStyle">
        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#categoriesModal">
            <i class="fas fa-sitemap"></i> Ajouter un categorie
        </button>
    </div>

    <div class="modal fade" id="categoriesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter Categories</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search Items -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Rechercher une catégorie existante</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="categorySearchInput"
                                    placeholder="Saisir un mot-clé...">
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
                            <h3 class="card-title">Créer une nouvelle catégorie</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                </div>
                                <input type="text" class="form-control" id="newCategoryInput"
                                    placeholder="Saisir le libellé de la nouvelle catégorie">
                                <input type="text" class="form-control" id="newCategoryDescription"
                                    placeholder="Fournir une description ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="createCategoryBtn">
                                        <i class="fas fa-plus"></i> Créer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Items-->

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Catégories sélectionnées</h3>

                        </div>
                        <div class="card-body">
                            <div id="selectedCategoriesList" style="max-height: 200px; overflow-y: auto;">
                                <!-- Selected items will appear here dynamically -->
                                Aucune catégorie n'est sélectionnée
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel">Annuler</button>
                        <button type="button" class="btn btn-primary" id="saveCategoriesBtn" data-dismiss="modal">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>