{{-- resources/views/librarian/requests/show.blade.php --}}
@extends('admin.dashboard')

@section('title', 'Détails de la Demande #' . $request->id)

@section('content_header')
    <h1 class="m-0 text-dark">Détails de la Demande #{{ $request->id }}</h1>
@stop

@section('content')
<div class="container-fluid"  style="height: 80vh; overflow-y: auto;">
    <div class="row">
        <div class="col-12">
            {{-- Afficher les messages de session --}}
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
            @if(session('info')) {{-- Ajout pour les messages d'info --}}
                <x-adminlte-alert theme="info" title="Information">
                    {{ session('info') }}
                </x-adminlte-alert>
            @endif

            <x-adminlte-card title="Informations sur la Demande" theme="primary" icon="fas fa-info-circle">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Informations sur le livre</h4>
                        <dl class="row">
                            <dt class="col-sm-4">Titre:</dt>
                            <dd class="col-sm-8">{{ $request->book?->title ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">Auteur(s):</dt>
                            <dd class="col-sm-8">
                                @if($request->book?->authors->isNotEmpty())
                                    {{ $request->book?->authors->pluck('name')->join(', ') }}
                                @else
                                    N/A
                                @endif
                            </dd>

                            <dt class="col-sm-4">ISBN:</dt>
                            <dd class="col-sm-8">{{ $request->book?->isbn ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">Catégorie(s):</dt>
                            <dd class="col-sm-8">
                                @if($request->book?->categories->isNotEmpty())
                                    {{ $request->book?->categories->pluck('name')->join(', ') }}
                                @else
                                    N/A
                                @endif
                            </dd>

                            <dt class="col-sm-4">Copies dispo:</dt>
                            <dd class="col-sm-8">
                                <span class="badge {{ $request->book?->available_copies() > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $request->book?->available_copies() }}
                                </span>
                            </dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <h4>Informations sur l'étudiant</h4>
                        <dl class="row">
                            <dt class="col-sm-4">Nom:</dt>
                            <dd class="col-sm-8">{{ $request->user->first_name . ' ' . $request->user->last_name ?? 'N/A' }}</dd> {{-- Mise à jour pour prénom/nom --}}

                            <dt class="col-sm-4">Email:</dt>
                            <dd class="col-sm-8">{{ $request->user->email ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">ID Étudiant:</dt>
                            <dd class="col-sm-8">{{ $request->user->student_id ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                </div>

                <hr>

                <h4>Détails de la demande</h4>
                <dl class="row">
                    <dt class="col-sm-3">Date de demande:</dt>
                    <dd class="col-sm-9">{{ $request->created_at->format('d/m/Y H:i') }}</dd>

                    {{-- Mise à jour pour utiliser return_date si le statut est borrowed --}}
                    @if($request->latestRequestInfo && $request->latestRequestInfo->status->value === 'borrowed')
                         <dt class="col-sm-3">Date d'échéance:</dt>
                         <dd class="col-sm-9">{{ $request->return_date() ? $request->return_date()->format('d/m/Y') : 'N/A' }}</dd>
                    @else
                         <dt class="col-sm-3">Date de retrait souhaitée:</dt>
                         <dd class="col-sm-9">{{ $request->preferred_pickup_date ? \Carbon\Carbon::parse($request->preferred_pickup_date)->format('d/m/Y') : 'Non spécifiée' }}</dd>
                    @endif

                </dl>
            </x-adminlte-card>

            <x-adminlte-card title="Historique des Statuts" theme="info" icon="fas fa-history">
                @if($request->requestInfo->isNotEmpty())
                    <div class="timeline">
                        {{-- Tri par date décroissante --}}
                        @foreach($request->requestInfo->sortByDesc('created_at') as $info)
                            @php
                                $statusClass = '';
                                switch ($info->status->value) {
                                    case 'pending': $statusClass = 'warning'; break;
                                    case 'approved': $statusClass = 'info'; break;
                                    case 'borrowed': $statusClass = 'primary'; break;
                                    case 'returned': $statusClass = 'success'; break;
                                    case 'rejected': $statusClass = 'danger'; break;
                                    case 'cancelled': $statusClass = 'secondary'; break;
                                    case 'overdue': $statusClass = 'danger'; break;
                                    default: $statusClass = 'secondary'; break;
                                }
                            @endphp
                            <div class="mb-3 p-3 border-left border-{{ $statusClass }}">
                                <div class="d-flex justify-content-between">
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ ucfirst(get_request_status_text($info->status)) }}
                                    </span>
                                    <small class="text-muted">
                                        {{ $info->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                {{-- Mise à jour pour prénom/nom --}}
                                @if($info->user)
                                    <div><small>Par: {{ $info->user->first_name . ' ' . $info->user->last_name }}</small></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Aucun historique disponible.</p>
                @endif
            </x-adminlte-card>

        </div>
    </div>
</div>
<br><br>
@stop

@section('js')
@parent
@endsection
