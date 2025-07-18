{{-- resources/views/components/book-details-modal.blade.php --}}
<div class="modal fade" id="bookDetailsModal" tabindex="-1" role="dialog" aria-labelledby="bookDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="bookDetailsModalLabel">Détails du livre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Content will be loaded via AJAX --}}
                <p class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des détails du livre...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="confirmRequestBtn">Confirmer la demande</button>
            </div>
        </div>
    </div>
</div>
