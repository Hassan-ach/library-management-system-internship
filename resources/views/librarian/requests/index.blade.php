@extends('layouts.app')

@section('title', 'Gestion des demandes d\'emprunt')

@section('content_header')
    <h1 class="m-0 text-dark">Demandes d'emprunt</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Liste des demandes" theme="primary" icon="fas fa-clipboard-list" >
                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label for="statusFilter" class="col-md-2 col-form-label">Filtrer par statut:</label>
                        <div class="col-md-4">
                            <select id="statusFilter" class="form-control select2">
                                <option value="">Toutes les demandes</option>
                                @foreach($statuses as $status)
                                    <option value="{{ ucfirst(get_request_status_text(App\Enums\RequestStatus::from($status->value))) }}">{{ ucfirst(get_request_status_text(App\Enums\RequestStatus::from($status->value))) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($requests->isEmpty())
                        <p class="text-center">Aucune demande d'emprunt à afficher pour le moment.</p>
                    @else
                        <div class="table-responsive">
                            <table id="requestsTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Demande</th>
                                        <th>Livre</th>
                                        <th>Étudiant</th>
                                        <th>Date de demande</th>
                                        <th>Statut</th>
                                        <th>Date d'échéance</th>
                                        <th >Actions</th>
                                    </tr>
                                </thead>
                        {{-- Dans resources/views/librarian/requests/index.blade.php --}}
<tbody>
    @foreach($requests as $request)
        <tr data-request-id="{{ $request->id }}"> {{-- Ajout de data-request-id --}}
            <td>{{ $request->id }}</td>
            <td>{{ $request->book?->title ?? 'Livre inconnu' }}</td>
            <td>{{ $request->user->first_name.' '.$request->user->last_name ?? 'Utilisateur inconnu' }}</td>
            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <x-status-badge :status="$request->latestRequestInfo->status->value" />
            </td>
            <td>
                    {{ $request->return_date() ? $request->return_date()->format('d/m/Y') : 'N/A' }}
            </td>
            <td> {{-- Colonne Actions --}}
                {{-- Button to trigger the details modal --}}
                <button type="button" class="btn btn-xs btn-info view-request-details-btn"
                        data-toggle="modal" data-target="#librarianRequestDetailsModal"
                        data-request-id="{{ $request->id }}"
                        title="Voir les détails">
                    <i class="fas fa-eye"></i>
                </button>
                {{-- Status Change Dropdown (direct action) --}}
                <form action="{{ route('librarian.requests.process', $request->id) }}" method="POST" class="d-inline status-update-form">
                    @csrf
                    <select name="status" class="form-control form-control-sm d-inline-block w-auto status-dropdown"
                            data-current-status="{{ $request->latestRequestInfo->status->value }}">
                        <option value="">Changer statut</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s->value }}" {{ $status === $s->value ? 'selected' : '' }}>
                                {{ ucfirst(get_request_status_text(App\Enums\RequestStatus::from($s->value))) }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Hidden button to trigger submission, or submit via JS on change --}}
                    <button type="submit" class="d-none"></button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $requests->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </x-adminlte-card>
        </div>
    </div>

    {{-- Include the Librarian Request Details Modal Component --}}
    @include('components.request-details-modal')
@stop

@section('js')
    @parent
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.8/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var requestsTable = $('#requestsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [6] } // Disable ordering on Actions column
                ]
            });

            // Filter requests by status
            $('#statusFilter').on('change', function() {
                var status = $(this).val();
                console.log(status)
                requestsTable.column(4).search(status).draw(); // Column 4 is 'Statut'
            });

            // Handle status change dropdown submission
            $('.status-dropdown').on('change', function() {
                var selectedStatus = $(this).val();
                var currentStatus = $(this).data('current-status');
                if (selectedStatus && selectedStatus !== currentStatus) {
                    var form = $(this).closest('form');
                    Swal.fire({
                        title: 'Confirmation',
                        text: `Changer le statue vers "${selectedStatus}" ?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, changer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form
                        } else {
                            // Revert dropdown to original value if cancelled
                            $(this).val(currentStatus);
                        }
                    });
                }
            });

            // Handle row click to go to request details page
            $('#requestsTable tbody').on('click', 'tr', function(event) {
                 // Check if click is in the 'Actions' column (index 6)
                 var clickedCellIndex = $(event.target).closest('td').index();

                 // Do nothing if click is in Actions column
                 if (clickedCellIndex === 6) {
                     return;
                 }

                 // Otherwise, navigate to the request details page
                 var requestId = $(this).data('request-id');
                 if (requestId) {
                     var showUrl = '/librarian/requests/' + requestId;
                     window.location.href = showUrl;
                 }
             });
        });
    </script>
@stop
