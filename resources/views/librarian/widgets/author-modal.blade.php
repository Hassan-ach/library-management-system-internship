<div class="form-group">
    <div class="mt-3">
        <div id="authors-display" class="data-display empty">Aucun auteur n'est sélectionné</div>
    </div>
    <div id="authorButtonStyle">
        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#authorsModal">
            <i class="fas fa-user-tie"></i> Ajouter un auteur
        </button>
    </div>
    <div class="modal fade" id="authorsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter un auteur</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search Items -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chercher un auteur</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="authorSearchInput"
                                    placeholder="Saisir un mot-clé...">
                            </div>
                            <!-- Search Results -->
                            <div id="authorsResults" style="max-height: 200px; overflow-y: auto;">
                                <!-- Dynamic search results will appear here -->
                            </div>
                        </div>
                    </div>
                    <!-- Create Items-->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Créer un nouveau auteur</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                </div>
                                <input type="text" class="form-control" id="newAuthorInput"
                                    placeholder="Saisir le nom du nouveau auteur">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="createAuthorBtn">
                                        <i class="fas fa-plus"></i> Créer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Items-->

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Auteurs sélectionnés</h3>

                        </div>
                        <div class="card-body">
                            <div id="selectedAuthorList" style="max-height: 200px; overflow-y: auto;">
                                <!-- Selected items will appear here dynamically -->
                                Aucun auteur n'est sélectionné
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel">Annuler</button>
                        <button type="button" class="btn btn-primary" id="saveAuthorsBtn" data-dismiss="modal">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>