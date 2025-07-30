@extends('layouts.app') {{-- Corrected: Should extend your main application layout --}}

@section('title', 'Gestion des demandes d\'emprunt')

@section('content_header')
    <h1 class="m-0 text-dark">Demandes d'emprunt</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Display success/error messages --}}
            @if(session('success'))
                <x-adminlte-alert theme="success" title="Succès">
                    {{ session('success') }}
                </x-adminlte-alert>
            @endif
            @if(session('error'))
                <x-adminlte-alert theme="danger" title="Erreur">
                    {{ session('error') }}
                </x-adminlte-alert>
            @endif

            <x-adminlte-card title="Liste des demandes" theme="primary" icon="fas fa-clipboard-list" >
                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label for="statusFilter" class="col-md-2 col-form-label">Filtrer par statut:</label>
                        <div class="col-md-4">
                            <select id="statusFilter" class="form-control select2">
                                <option value="">Toutes les demandes</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
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
                                    </tr>
                                </thead>
                                <tbody style="cursor: pointer;">
                                    @foreach($requests as $request)
                                        <tr data-request-id="{{ $request->id }}">
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $requests->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('js')
    @parent

    <script>
        $(document).ready(function() {
            // Initialize Select2 for the filter dropdown
            $('.select2').select2({
                placeholder: "Sélectionner un statut",
                allowClear: true
            });

            // Initialize DataTables
            var requestsTable = $('#requestsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
                },
                // Removed columnDefs as there's no specific column to disable ordering on anymore
            });

            // Filter requests by status using DataTables API
            $('#statusFilter').on('change', function() {
                var status = $(this).val();
                // Column 4 is 'Statut' (0-indexed)
                requestsTable.column(4).search(status).draw();
            });

            // Handle row click to go to request details page
            $('#requestsTable tbody').on('click', 'tr', function(event) {
                 // No need to check clickedCellIndex anymore as there are no action buttons
                 var requestId = $(this).data('request-id');
                 if (requestId) {
                     window.location.href = '{{ url('admin/requests') }}/' + requestId;
                 }
             });
        });
    </script>
@stop

@section('css')
    @parent
@stop
