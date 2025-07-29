@extends('admin.dashboard')

@section('title', 'Détails de la demande: ' . ($bookReq->book?->title ?? 'Demande #'.$bookReq->id))

@section('content_header')
    <h1 class="m-0 text-dark">Détails de la demande</h1>
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

            <x-adminlte-card title="Détails de la demande {{ $bookReq->book?->title }}" theme="primary" icon="fas fa-info-circle" >
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informations sur le livre</h4>
                            <dl class="row">
                                <dt class="col-sm-4">Titre:</dt>
                                <dd class="col-sm-8">{{ $bookReq->book?->title ?? 'Livre inconnu' }}</dd>

                                <dt class="col-sm-4">Auteur(s):</dt>
                                <dd class="col-sm-8">
                                    @if($bookReq->book && $bookReq->book?->authors->isNotEmpty())
                                        @foreach($bookReq->book?->authors as $author)
                                            {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </dd>
                                <dt class="col-sm-4">Catégorie(s):</dt>
                                <dd class="col-sm-8">
                                    @if($bookReq->book && $bookReq->book?->categories->isNotEmpty())
                                        @foreach($bookReq->book?->categories as $category)
                                            <span class="badge badge-info">{{ $category->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </dl>
                        </div>

                        <div class="col-md-6">
                            <h4>Statut de la demande</h4>
                            <dl class="row">
                                <dt class="col-sm-4">Date de demande:</dt>
                                <dd class="col-sm-8">{{ $bookReq->created_at->format('d/m/Y') }}</dd>

                                <dt class="col-sm-4">Date de retrait souhaitée:</dt>
                                <dd class="col-sm-8">{{ $bookReq->return_date() ? $bookReq->return_date()->format('d/m/Y') : 'Non spécifiée' }}</dd>

                                <dt class="col-sm-4">Statut actuel:</dt>
                                <dd class="col-sm-8">
                                     <x-status-badge :status="$reqInfo->status->value" />
                                </dd>

                                @if($reqInfo->status === 'borrowed' || $reqInfo->status === 'overdue')
                                    <dt class="col-sm-4">Date d'échéance:</dt>
                                    <dd class="col-sm-8">{{ $reqInfo->created_at ? \Carbon\Carbon::parse($reqInfo->created_at)->format('d/m/Y') : 'N/A' }}</dd>
                                @endif

                                @if($reqInfo->status === 'returned')
                                    <dt class="col-sm-4">Date de retour:</dt>
                                    <dd class="col-sm-8">{{ $reqInfo->created_at ? \Carbon\Carbon::parse($reqInfo->created_at)->format('d/m/Y H:i') : 'N/A' }}</dd>
                                @endif

                                @if($reqInfo->created_at)
                                    <dt class="col-sm-4">Traitée le:</dt>
                                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($reqInfo->created_at)->format('d/m/Y H:i') }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="card-footer text-right">
                    @if($reqInfo->status->value === 'pending')
                                                         <form action="{{ route('student.requests.cancel', $bookReq->id) }}" method="GET" class="d-inline cancel-request-form">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Annuler la demande
                            </button>
                                                    </form>
               @endif
                    <a href="{{ route('student.requests.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Retour à mes demandes
                    </a>
                </div>
            </x-adminlte-card>
        </div>
    </div>
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
