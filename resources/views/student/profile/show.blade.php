@extends('layouts.app')

@section('title', 'Mon profil')

@section('content_header')
    <h1 class="m-0 text-dark">Mon profil</h1>
@stop

@section('content')
    <div class="row">
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

            {{-- Recent Book Requests Card --}}
            <x-adminlte-card title="Mes demandes récentes" theme="info" icon="fas fa-history" >
                <div class="card-body">
                    @if($requests->isEmpty())
                        <p class="text-center">Vous n'avez pas encore de demandes de livres.</p>
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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests->take(5) as $request) {{-- Show only 5 recent requests --}}
                                        <tr>
                                            <td>{{ $request->book?->title ?? 'Livre inconnu' }}</td>
                                            <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <x-status-badge :status="$request->latestRequestInfo->status->value" />
                                            </td>
                                            <td>
                                                <a href="{{ route('student.requests.show', $request->id) }}" class="btn btn-xs btn-info" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
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
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
@section('js')
    @parent
@stop

