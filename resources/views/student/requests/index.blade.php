@extends('layouts.app')

@section('title', 'Mes demandes de livres')

@section('content_header')
    <h1 class="m-0 text-dark">Mes demandes de livres</h1>
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

            <x-adminlte-card title="Historique de mes demandes" theme="info" icon="fas fa-clipboard-list" >
                <div class="card-body">
                    @if($bookRequests->isEmpty())
                        <p class="text-center">Vous n'avez pas encore effectué de demandes de livres.</p>
                        <div class="text-center">
                            <a href="{{ route('student.books.index') }}" class="btn btn-primary">
                                <i class="fas fa-book"></i> Parcourir les livres
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre du livre</th>
                                        <th>Date de demande</th>
                                        <th>Statut</th>
                                        <th>Date d'échéance</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookRequests as $request)
                                        <tr>
                                            <td>{{ $request->book->title ?? 'Livre inconnu' }}</td>
                                            <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <x-status-badge :status="$request->latestRequestInfo->status->value" />
                                            </td>
                                            <td>
                                                @if($request->latestRequestInfo && $request->latestRequestInfo->status === 'borrowed')
                                                    {{ $request->latestRequestInfo->due_date ? \Carbon\Carbon::parse($request->latestRequestInfo->due_date)->format('d/m/Y') : 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('student.requests.show', $request->id) }}" class="btn btn-xs btn-info" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($request->latestRequestInfo && $request->latestRequestInfo->status->value === 'pending')
                                                    <form action="{{ route('student.requests.cancel', $request->id) }}" method="GET" class="d-inline cancel-request-form">
                                                        <button type="submit" class="btn btn-xs btn-danger" title="Annuler la demande">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $bookRequests->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('css')
    @parent
    {{-- Include SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
    @parent
    {{-- Include SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // SweetAlert for Cancel Request confirmation
            $('.cancel-request-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                var form = this;

                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Vous ne pourrez pas annuler cette action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, annuler!',
                    cancelButtonText: 'Non, garder'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if confirmed
                    }
                });
            });

            // Optional: Show loading state while processing
            $('.cancel-request-form').on('submit', function() {
                var button = $(this).find('button[type="submit"]');
                button.prop('disabled', true);
                button.html('<i class="fas fa-spinner fa-spin"></i>');
            });
        });
    </script>
@stop
