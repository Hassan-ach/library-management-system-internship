{{-- resources/views/components/book-details-modal.blade.php --}}

{{-- Modal HTML Structure --}}
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

{{-- JavaScript for the modal --}}
@push('js')
<script>
    $(document).ready(function() {
        // Listen for the modal to be shown
        $('#bookDetailsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var bookId = button.data('book-id'); // Extract book ID from data-book-id attribute

            var modal = $(this);
            modal.find('.modal-title').text('Détails du livre');
            modal.find('.modal-body').html('<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des détails du livre...</p>');
            modal.find('#confirmRequestBtn').prop('disabled', true); // Disable button while loading

            // AJAX call to fetch book details
            $.ajax({
                url: '{{ url("student/books") }}/' + bookId + '/details', // Dynamic URL for book details
                method: 'GET',
                success: function(response) {
                    if (response.book) {
                        var book = response.book;
                        var authors = book.authors.map(a => a.name).join(', ') || 'N/A';
                        var categories = book.categories.map(c => c.label).join(', ') || 'N/A';
                        var publishers = book.publishers.map(p => p.name).join(', ') || 'N/A';
                        var tags = book.tags.map(t => t.name).join(', ') || 'N/A';

                        var modalBodyContent = `
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="${book.image_url || '{{ asset('images/default-book.png') }}'}" alt="${book.title}" class="img-fluid mb-3" style="max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                                </div>
                                <div class="col-md-8">
                                    <h4>${book.title}</h4>
                                    <p><strong>Auteur(s):</strong> ${authors}</p>
                                    <p><strong>Catégorie(s):</strong> ${categories}</p>
                                    <p><strong>Éditeur(s):</strong> ${publishers}</p>
                                    <p><strong>Tags:</strong> ${tags}</p>
<p><strong>Date de publication:</strong> ${book.publication_date ? new Date(book.publication_date).getFullYear() : 'N/A'}</p>
                                    <p><strong>Nombre de pages:</strong> ${book.number_of_pages || 'N/A'}</p>
                                    <p><strong>Copies disponibles:</strong> <span class="badge ${book.available_copies > 0 ? 'badge-success' : 'badge-danger'}">${book.available_copies}</span></p>
                                </div>
                            </div>
                            <hr>
                            <h5>Description:</h5>
                            <p>${book.description || 'Aucune description disponible.'}</p>
                            <hr>
                            <form id="requestBookForm" action="{{ route('student.requests.create', '') }}/${book.id}" method="POST">
                                @csrf
                            </form>
                        `;
                        modal.find('.modal-body').html(modalBodyContent);
                        modal.find('#confirmRequestBtn').prop('disabled', book.available_copies <= 0); // Enable/disable based on availability
                    } else {
                        modal.find('.modal-body').html('<p class="text-center text-danger">Impossible de charger les détails du livre.</p>');
                        modal.find('#confirmRequestBtn').prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    modal.find('.modal-body').html('<p class="text-center text-danger">Erreur lors du chargement des détails: ' + (xhr.responseJSON ? xhr.responseJSON.message : error) + '</p>');
                    modal.find('#confirmRequestBtn').prop('disabled', true);
                    console.error("AJAX Error: ", status, error, xhr.responseJSON);
                }
            });
        });

        // Handle the "Confirm Request" button click inside the modal
        $('#confirmRequestBtn').on('click', function() {
            $('#requestBookForm').submit();
        });
    });
</script>
@endpush
