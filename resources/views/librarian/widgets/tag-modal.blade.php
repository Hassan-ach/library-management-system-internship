<div class="form-group">
    <div class="mt-3">
        <div id="tags-display" class="data-display empty">Aucune étiquette n'est sélectionnée</div>
    </div>
    <div id="tagButtonStyle">
        <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#tagsModal">
            <i class="fas fa-tag"></i> Ajouter une étiquette
        </button>
    </div>

    <div class="modal fade" id="tagsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter tags</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search Items -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Rechercher une étiquette existante</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="tagSearchInput"
                                    placeholder="Saisir un mot-clé...">
                            </div>
                            <!-- Search Results -->
                            <div id="tagsResults" style="max-height: 200px; overflow-y: auto;">
                                <!-- Dynamic search results will appear here -->
                            </div>
                        </div>
                    </div>
                    <!-- Create Items-->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Créer une nouvelle étiquette </h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" class="form-control" id="newTagInput"
                                    placeholder="Saisir le libellé de la nouvelle étiquette">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="createTagBtn">
                                        <i class="fas fa-plus"></i> Créer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Items-->

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Étiquettes sélectionnées</h3>

                        </div>
                        <div class="card-body">
                            <div id="selectedTagList" style="max-height: 200px; overflow-y: auto;">
                                Aucune étiquette n'est sélectionnée
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel">Annuler</button>
                        <button type="button" class="btn btn-primary" id="saveTagsBtn" data-dismiss="modal">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>