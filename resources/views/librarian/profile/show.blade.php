@extends('layouts.app')

@section('title', 'Mon profil')

@section('content_header')
<div style="width:90%;margin: 0 auto;">
    <h1 class="ml-1 text-dark">Mon profil</h1>
</div>
@stop

@section('content')
    <div class="row" style="width:90%;margin: 0 auto;">
        <div class="col-md-12">
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

            <x-adminlte-card title="Informations de profil" theme="primary" icon="fas fa-user" >
                <div class="card-body">
                    <div class="row">
                        {{-- Left Column: Avatar and Basic Info --}}
                        <div class="col-md-4 text-center">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/user2-160x160.jpg') }}"
                                 class="img-circle elevation-2 mb-3" alt="User Image" style="width: 150px; height: 150px; object-fit: cover;">
                            <h3>{{ $user->first_name }}</h3>
                            <p class="text-muted">{{ $user->role ?? 'Étudiant' }}</p>
                        {{--  <a href="{{ route('student.books.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Modifier le profil
                            </a>--}}
                        </div>

                        {{-- Right Column: Detailed Info --}}
                        <div class="col-md-8">
                            <h4>Détails du compte</h4>
                            <dl class="row">
                                <dt class="col-sm-4">Nom complet:</dt>
                                <dd class="col-sm-8">{{ $user->first_name . ' ' . $user->last_name }}</dd>

                                <dt class="col-sm-4">Email:</dt>
                                <dd class="col-sm-8">{{ $user->email }}</dd>

                                <dt class="col-sm-4">Statut du compte:</dt>
                                <dd class="col-sm-8">
                                    @php
                                        $status = $user->is_active ?? 'active';
                                        $badgeClass = '';
                                        switch ($status) {
                                            case true: $badgeClass = 'badge-success'; $status = 'active'; break;
                                            case false: $badgeClass = 'badge-secondary'; $status = 'active'; break;
                                            default: $badgeClass = 'badge-secondary'; break;
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </x-adminlte-card>

            <x-adminlte-card title="Mes actions récentes" theme="info" icon="fas fa-history" >
                <div class="card-body">
                    @if($requests_info->isEmpty())
                        <p class="text-center">Vous n'avez pas aucun action à afficher</p>
                        <div class="text-center">
                            <a href="{{ route('librarian.requests.index') }}" class="btn btn-primary">
                                <i class="fas fa-book"></i> Parcourir les demandes
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Id demande</th>
                                        <th>Etudiant</th>
                                        <th>Titre du livre</th>
                                        <th>Date de demande</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests_info->take(8) as $request) {{-- Show only 8 recent requests --}}
                                        <tr>
                                            <td><span class="badge bg-dark">{{ $request->bookRequest->id ?? '#'}}</span></td>
                                            <td>{{ $request->bookRequest->user?->first_name .' '.$request->bookRequest->user?->last_name ?? 'Etudiant inconnu' }}</td>
                                            <td>{{ $request->bookRequest->book?->title ?? 'Livre inconnu' }}</td>
                                            <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <x-status-badge :status="$request->status->value" />
                                            </td>
                                            <td>
                                                <a href="{{ route('librarian.requests.show', $request->bookRequest->id) }}" class="btn btn-xs btn-info" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('librarian.requests.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes mes demandes</a>
                        </div>
                    @endif
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop

