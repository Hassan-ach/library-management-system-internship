@extends('layouts.app')

@section('title', 'Tableau de bord Étudiant')

@section('content_header')
    <h1 class="m-0 text-dark">Tableau de bord Étudiant</h1>
    {{-- You can add breadcrumbs here if you have a breadcrumb component or logic --}}
    {{-- Example:
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
        <li class="breadcrumb-item active">Tableau de bord Étudiant</li>
    </ol>
    --}}
@stop

@section('content')
    <div class="row">
        {{-- Livres empruntés --}}
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Livres empruntés" text="{{ $borrowed->count() }}" icon="fas fa-book-open"
                theme="info" icon-theme="info" />
        </div>
        {{-- Demandes en attente --}}
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Demandes en attente" text="{{ $pending->count() }}" icon="fas fa-clock"
                theme="warning" icon-theme="warning" />
        </div>
        {{-- Livres rendus --}}
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Livres rendus" text="{{ $returned->count() }}" icon="fas fa-check-circle"
                theme="success" icon-theme="success" />
        </div>
        {{-- Retards --}}
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Retards" text="{{ $overdue->count() }}" icon="fas fa-exclamation-triangle"
                theme="danger" icon-theme="danger" />
        </div>
    </div>

    <div class="row">
        {{-- Mes emprunts récents Card --}}
        <div class="col-md-8">
            <x-adminlte-card title="Mes emprunts récents" theme="primary" icon="fas fa-history" collapsible  maximizable>
                @if($recent->isEmpty())
                    <p class="text-center">Vous n'avez pas d'emprunts récents.</p>
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
                                @foreach($recent as $request)
                                    <tr>
                                        <td>{{ $request->book->title ?? 'N/A' }}</td>
                                        <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $status = $request->latestRequestInfo->status->value ?? 'N/A';
                                                $badgeClass = '';
                                                switch ($status) {
                                                    case 'pending': $badgeClass = 'badge-warning'; break;
                                                    case 'approved': $badgeClass = 'badge-info'; break;
                                                    case 'borrowed': $badgeClass = 'badge-primary'; break;
                                                    case 'returned': $badgeClass = 'badge-success'; break;
                                                    case 'rejected': $badgeClass = 'badge-danger'; break;
                                                    case 'cancelled': $badgeClass = 'badge-secondary'; break;
                                                    case 'overdue': $badgeClass = 'badge-danger'; break;
                                                    default: $badgeClass = 'badge-secondary'; break;
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                        </td>
                                        <td>
                                            @if($request->latestRequestInfo && $request->latestRequestInfo->status === 'borrowed')
                                                {{ $request->latestRequestInfo->due_date ? \Carbon\Carbon::parse($request->latestRequestInfo->due_date)->format('d/m/Y') : 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('student.requests.show', $request->id) }}" class="btn btn-xs btn-info">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('student.requests.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes mes demandes</a>
                    </div>
                @endif
            </x-adminlte-card>
        </div>

        {{-- Raccourcis rapides Card --}}
        <div class="col-md-4">
            <x-adminlte-card title="Raccourcis rapides" theme="info" icon="fas fa-bolt" collapsible  maximizable>
                <div class="list-group">
                    <a href="{{ route('student.books.search') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-search mr-2 text-primary"></i> Rechercher des livres
                    </a>
                    <a href="{{ route('student.requests.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-list mr-2 text-info"></i> Voir mes demandes
                    </a>
                    <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-user mr-2 text-secondary"></i> Gérer mon profil
                    </a>
                    {{-- Add more quick actions as needed --}}
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any specific CSS for this page if needed --}}
@stop

@section('js')
    {{-- Add any specific JS for this page if needed --}}
    {{-- Example for DataTables if you want to make the recent borrowings table sortable/searchable --}}
    {{-- @push('js')
        <script>
            $(function() {
                $('#recentBorrowingsTable').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            });
        </script>
    @endpush --}}
@stop
