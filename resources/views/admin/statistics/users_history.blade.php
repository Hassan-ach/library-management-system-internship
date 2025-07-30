@php
use App\Enums\RequestStatus;
use App\Enums\UserRole;
@endphp

@extends('admin.dashboard')

@section('css')
<style>
    .scrollable-table-container {
        max-height: 45%;  /* Adjust height as needed */
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .sticky-header th {
        position: sticky;
        top: 0;
        background: #343a40; /* Matches thead-dark */
        color: white;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="container">
    @if(Session::has('error'))
        <script>
            toastr.error("{{ Session::get('error') }}");
        </script>
    @endif

    <!-- User Information Section -->
    <!-- User Info Section -->
    <br>
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informations sur l'étudiants</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Personal Info Column -->
                <div class="col-md-6 border-end">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-user me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Nom</h6>
                            <p class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-envelope me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Email</h6>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-tag me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Rôle</h6>
                                @if ($user->role->value === 'student')
                                    <p class="mb-0">étudiant</p>
                                @elseif ($user->role->value === 'admin')
                                    <p class="mb-0">admin</p>
                                @elseif ($user->role->value === 'librarian')
                                    <p class="mb-0">bibliothécaire</p>
                                @endif
                        </div>
                    </div>
                </div>
                
                <!-- Activity Info Column -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-list-ol me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Nombre total de demandes</h6>
                            <p class="mb-0">{{ $totalRequests }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Dernière activité</h6>
                            <p class="mb-0">
                                @if($requests->count() > 0)
                                    {{ 'le ' . str_replace( ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                                           ['janv', 'févr', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc'],
                                $requests->first()['created_at']->format('d M Y H:i')) }}
                                    <small class="text-muted d-block">({{ $requests->first()['created_diff'] }})</small>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="scrollable-table-container">
    <table class="table table-striped table-hover">
        <thead class="thead-light sticky-header">
            <tr>
                <th class="text-black">ID</th>
                <th class="text-black">Date demande</th>
                <th class="text-black">Titre</th>
                <th class="text-black">Statut</th>
                <th class="text-black">Traité à</th>
                <th class="text-black">Traité par</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
                @php
                    $status = $request['status'] ?? null;
                    $bgColor = get_request_status_badge($status);
                    $badgeText = get_request_status_text($status);
                @endphp
                <tr>
                    <td class="align-middle">{{ $request['id'] }}</td>
                    <td class="align-middle">
                        <div class="d-flex flex-column">
                            <span>{{ $request['created_at']->format('Y-m-d H:i') }}</span>
                            <small class="text-muted">{{ $request['created_diff'] }}</small>
                        </div>
                    </td>
                    <td class="align-middle">
                        <span class="fw-medium">{{ $request['book_title'] }}</span>
                    </td>
                    <td class="align-middle">
                        <span class="badge bg-{{ $bgColor }}"> {{$badgeText}} </span>
                    </td>
                    <td class="align-middle">
                        @if($request['processed_at'])
                        <div class="d-flex flex-column">
                            <span>{{ $request['processed_at']->format('Y-m-d H:i') }}</span>
                            <small class="text-muted">{{ $request['processed_diff'] }}</small>
                        </div>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        {{$request['processed_by']}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div >
    {{ $requests->withQueryString()->links('pagination::bootstrap-5') }}
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection