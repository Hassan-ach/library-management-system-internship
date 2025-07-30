@extends('admin.dashboard')

@section('title', 'Mon profil')

@section('content')
<br>
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

            <div class="row justify-content-center">
                <div  class="card col-md-8 justify-content-center" title="Informations de profil" theme="primary" icon="fas fa-user" >
                    <div class="card-body ">
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
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <h4>Détails du compte</h4>
                                <dl class="row">
                                    <form method="POST" action="{{ route('admin.users.update.submit', $user->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="first_name" class="form-label">Prénom</label>
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                    id="first_name" name="first_name" 
                                                    value="{{ old('first_name', $user->first_name) }}">
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="last_name" class="form-label">Nom</label>
                                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                    id="last_name" name="last_name" 
                                                    value="{{ old('last_name', $user->last_name) }}">
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                id="email" name="email" 
                                                value="{{ old('email', $user->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Current Password (only required when changing password) -->
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Mot de passe actuel (seulement si vous voulez le changez)</label>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                id="current_password" name="current_password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- New Password (optional) -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">noveau mot de passe</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                id="password" name="password">
                                            <div class="form-text">If changing, must be at least 8 characters with numbers and mixed case</div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Confirm New Password (only required if password is provided) -->
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmer mot de passe</label>
                                            <input type="password" class="form-control" 
                                                id="password_confirmation" name="password_confirmation">
                                        </div>

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
                                        
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-md-2">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Update User</button>
                                        </div>
                                    </form>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

