{{-- resources/views/components/request-details-modal.blade.php --}}
<div class="modal fade" id="librarianRequestDetailsModal" tabindex="-1" role="dialog" aria-labelledby="librarianRequestDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="librarianRequestDetailsModalLabel">Détails de la demande</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des détails de la demande...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" form="processRequestForm" class="btn btn-success" id="modalProcessRequestBtn">Mettre à jour le statut</button>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    $(document).ready(function() {
        // Available statuses - this should match your RequestStatus enum
        var availableStatuses = @json(\App\Enums\RequestStatus::cases()).filter(status => status !== 'canceled' && status !== 'pending');;
        $('#librarianRequestDetailsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var requestId = button.data('request-id');
            var modal = $(this);
            modal.find('.modal-title').text('Détails de la demande #' + requestId);
            modal.find('.modal-body').html('<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des détails de la demande...</p>');
            modal.find('#modalProcessRequestBtn').prop('disabled', true);
            $.ajax({
                url: '/librarian/requests/' + requestId + '/details',
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.bookRequest) {
                        var req = response.bookRequest;
                        var book = req.book;
                        var student = req.user;
                        var latestInfo = response.reqInfo;
                        var requestHistory = response.requestHistory;
                        var authors = book.authors ? book.authors.map(a => a.name).join(', ') : 'N/A';
                        var categories = book.categories ? book.categories.map(c => c.name).join(', ') : 'N/A';
                        // Build status options dynamically
                        var statusOptions = '';
                        availableStatuses.forEach(function(status) {
                            var selected = latestInfo.status === status ? 'selected' : '';
                            statusOptions += `<option value="${status}" ${selected}>${status.charAt(0).toUpperCase() + status.slice(1)}</option>`;
                        });
                        // Build history timeline
                        var historyHtml = '';
                        if (requestHistory && requestHistory.length > 0) {
                            requestHistory.forEach(function(info) {
                                var statusClass = getStatusClass(info.status);
                                var date = new Date(info.created_at).toLocaleDateString('fr-FR', {
                                    year: 'numeric', month: 'long', day: 'numeric',
                                    hour: '2-digit', minute: '2-digit'
                                });
                                historyHtml += `
                                    <div class="mb-3 p-3 border-left border-${statusClass}">
                                        <div class="d-flex justify-content-between">
                                            <span class="badge badge-${statusClass}">${info.status.charAt(0).toUpperCase() + info.status.slice(1)}</span>
                                            <small class="text-muted">${date}</small>
                                        </div>
                                        ${info.user ? `<div><small>Par: ${info.user.first_name +' '+ info.user.last_name || 'N/A'}</small></div>` : ''}
                                        ${info.due_date ? `<div><small>Date d'échéance: ${new Date(info.due_date).toLocaleDateString('fr-FR')}</small></div>` : ''}
                                        ${info.librarian_notes ? `<div><small>Notes: ${info.librarian_notes}</small></div>` : ''}
                                    </div>
                                `;
                            });
                        }
                        var modalBodyContent = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Informations sur le livre</h5>
                                    <dl class="row">
                                        <dt class="col-sm-4">Titre:</dt><dd class="col-sm-8">${book.title || 'N/A'}</dd>
                                        <dt class="col-sm-4">Auteur(s):</dt><dd class="col-sm-8">${authors || 'N/A'}</dd>
                                        <dt class="col-sm-4">ISBN:</dt><dd class="col-sm-8">${book.isbn || 'N/A'}</dd>
                                        <dt class="col-sm-4">Catégorie(s):</dt><dd class="col-sm-8">${categories || 'N/A'}</dd>
                                        <dt class="col-sm-4">Copies dispo:</dt><dd class="col-sm-8"><span class="badge ${book.available_copies > 0 ? 'badge-success' : 'badge-danger'}">${book.available_copies}</span></dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <h5>Informations sur l'étudiant</h5>
                                    <dl class="row">
                                        <dt class="col-sm-4">Nom:</dt><dd class="col-sm-8">${student.first_name + ' ' + student.last_name || 'N/A'}</dd>
                                        <dt class="col-sm-4">Email:</dt><dd class="col-sm-8">${student.email || 'N/A'}</dd>
                                        <dt class="col-sm-4">ID Étudiant:</dt><dd class="col-sm-8">${student.student_id || 'N/A'}</dd>
                                    </dl>
                                </div>
                            </div>
                            <hr>
                            <h5>Détails de la demande</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Date de demande:</dt><dd class="col-sm-8">${new Date(req.created_at).toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</dd>
                                <dt class="col-sm-4">Date de retrait souhaitée:</dt><dd class="col-sm-8">${req.return_date ? new Date(req.return_date).toLocaleDateString('fr-FR') : 'Non spécifiée'}</dd>
                            </dl>
                            <hr>
                            <h5>Historique des statuts</h5>
                            <div class="history-timeline">
                                ${historyHtml}
                            </div>
                            <hr>
                            <h5>Mettre à jour le statut</h5>
                            <form id="processRequestForm" action="/librarian/requests/${requestId}" method="POST">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <div class="form-group">
                                    <label for="status">Nouveau statut:</label>
                                    <select name="status" id="status" class="form-control">
                                        ${statusOptions}
                                    </select>
                                </div>
                            </form>
                        `;
                        modal.find('.modal-body').html(modalBodyContent);
                        modal.find('#modalProcessRequestBtn').prop('disabled', false);

                         // Attacher le gestionnaire au formulaire nouvellement créé
                        modal.find('#processRequestForm').off('submit').on('submit', function(e) {
                             e.preventDefault(); // Empêcher la soumission par défaut immédiatement
                             var form = this; // Référence au formulaire

                             if (typeof Swal !== 'undefined') {
                                 Swal.fire({
                                     title: 'Confirmer la mise à jour?',
                                     text: "Le statut de la demande sera modifié.",
                                     icon: 'question',
                                     showCancelButton: true,
                                     confirmButtonColor: '#3085d6',
                                     cancelButtonColor: '#d33',
                                     confirmButtonText: 'Oui, mettre à jour!',
                                     cancelButtonText: 'Annuler'
                                 }).then((result) => {
                                     if (result.isConfirmed) {
                                         form.submit(); // Soumettre le formulaire JS
                                     }
                                 });
                             } else {
                                 if (confirm('Confirmer la mise à jour du statut?')) {
                                     form.submit(); // Soumettre le formulaire JS
                                 }
                             }
                         });
                    } else {
                        modal.find('.modal-body').html('<p class="text-center text-danger">Impossible de charger les détails de la demande.</p>');
                        modal.find('#modalProcessRequestBtn').prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error, xhr);
                    var errorMsg = 'Erreur lors du chargement des détails';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += ': ' + xhr.responseJSON.message;
                    }
                    modal.find('.modal-body').html('<p class="text-center text-danger">' + errorMsg + '</p>');
                    modal.find('#modalProcessRequestBtn').prop('disabled', true);
                }
            });
        });
        // Helper function for status classes
        function getStatusClass(status) {
            switch (status) {
                case 'pending': return 'warning';
                case 'approved': return 'info';
                case 'borrowed': return 'primary';
                case 'returned': return 'success';
                case 'rejected': case 'cancelled': case 'overdue': return 'danger';
                default: return 'secondary';
            }
        }
    });
</script>
@endpush
