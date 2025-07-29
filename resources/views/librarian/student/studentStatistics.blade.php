@extends('layouts.app')
@section('title', 'Profile de l\'etudiant')

@section('content_header')
<h1> </h1>
@stop
@section('content')
<div class="container" style="width:90%;margin: 0 auto;">
        <div class="d-flex  justify-content-between align-items-center " style="margin-bottom:15px">
            <div>
                <a href="{{ URL::previous()}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la demande
                </a>
            </div>
        </div>
        @if(Session::has('error'))
            <script>
                toastr.error("{{ Session::get('error') }}");
            </script>
        @endif

        <x-adminlte-card title="Informations de profil" theme="primary" icon="fas fa-user">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/user2-160x160.jpg') }}"
                            class="img-circle elevation-2 mb-3" alt="User Image"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h3>{{ $user->first_name }}</h3>
                        <p class="text-muted">{{ $user->role ?? 'Étudiant' }}</p>
                    </div>

                    <div class="col-md-8">
                        <h4>Détails du compte</h4>
                        <dl class="row">
                            <dt class="col-sm-4">Nom complet:</dt>
                            <dd class="col-sm-8">{{ $user->first_name . ' ' . $user->last_name }}</dd>

                            <dt class="col-sm-4">Email:</dt>
                            <dd class="col-sm-8">{{ $user->email }}</dd>

                            <dt class="col-sm-4">Nombre de demandes:</dt>
                            <dd class="col-sm-8">{{ $nbr_request }}</dd>

                            <dt class="col-sm-4">Statut du compte:</dt>
                            <dd class="col-sm-8">
                                @php
                                    $status = $user->is_active ?? 'active';
                                    $badgeClass = '';
                                    switch ($status) {
                                        case true:
                                            $badgeClass = 'badge-success';
                                            $status = 'active';
                                            break;
                                        case false:
                                            $badgeClass = 'badge-secondary';
                                            $status = 'active';
                                            break;
                                        default:
                                            $badgeClass = 'badge-secondary';
                                            break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </x-adminlte-card>
        <x-adminlte-card title="Historique de l'étudiant" theme="info" icon="fas fa-history">
            <div class="card-body">
                @if($requests->isEmpty())
                    <p class="text-center">L'étudiant n'a fait aucun demande</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Id demande</th>
                                    <th>Titre du livre</th>
                                    <th>Statut</th>
                                    <th>Date de statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests->sortBy([['created_at', 'desc']]) as $request)
                                    <tr>
                                        <td><span class="badge bg-dark">{{ $request->id ?? '#'}}</span></td>
                                        <td>{{ $request->book->title ?? 'livre inconnu' }}</td>
                                        <td>
                                            <x-status-badge :status="$request->latestRequestInfo->status->value" />
                                        </td>
                                        <td>{{ $request->latestRequestInfo->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                @endif
                </div>
        </x-adminlte-card>
    </div>
@endsection