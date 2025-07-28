@php
use App\Enums\RequestStatus;
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
        <h1>Les etudiants</h1>

        {{-- Search and filter --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Chercher des etudiants</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.statistics.users.search') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Term</label>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Search by name, email, etc." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="activity" class="form-label">Last activity</label>
                        <select class="form-select" id="activity" name="activity" style="cursor: pointer;">
                            <option value="">Tous les statuts</option>
                            @foreach(App\Enums\RequestStatus::cases() as $activity)
                                <option value="{{ $activity->value }}" {{ request('activity') == $activity->value ? 'selected' : '' }}>
                                    @switch($activity)
                                        @case(App\Enums\RequestStatus::PENDING) En attente @break
                                        @case(App\Enums\RequestStatus::BORROWED) Emprunté @break
                                        @case(App\Enums\RequestStatus::APPROVED) Approuvé @break
                                        @case(App\Enums\RequestStatus::REJECTED) Rejeté @break
                                        @case(App\Enums\RequestStatus::OVERDUE) En retard @break
                                        @case(App\Enums\RequestStatus::RETURNED) Retourné @break
                                        @case(App\Enums\RequestStatus::CANCELED) Annulé @break
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" style="cursor: pointer;">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i> Search
                        </button>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <a href="{{ route('admin.users.all') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(request()->hasAny(['search', 'role', 'status']))
            <div class="alert alert-info mb-3">
                Showing results for:
                @if(request('search')) <strong>Search:</strong> {{ request('search') }} @endif
                @if(request('role')) <strong>Role:</strong> {{ ucfirst(request('role')) }} @endif
                @if(request('status')) <strong>Status:</strong> {{ ucfirst(request('status')) }} @endif
                <a href="{{ route('admin.statistics.users') }}" class="float-end">Show all</a>
            </div>
        @endif
        {{-- Search and filter --}}

        {{-- Scrollable table container --}}
        <div class="scrollable-table-container">
            <table class="table table-striped table-hover">
                <thead class="thead-dark sticky-header">
                    <tr>
                        <th>Id</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Last activity</th>
                        <th>Book title</th>
                        <th>Status</th>
                        <th>History</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                            @php
                                $latestInfo = null;
                                if ($user->bookRequests && $user->bookRequests->isNotEmpty()) {
                                    $latestRequest = $user->bookRequests->first();
                                    $latestInfo = $latestRequest->latestRequestInfo ?? null;
                                }
                            @endphp
                                {{-- @if ($user->role->value === 'student') --}}
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->value }}</td>
                                <td>
                                    @if($latestInfo)
                                        @php
                                            $status = $latestInfo->status;
                                            $bgColor = get_request_status_badge($status);
                                            $badgeText = get_request_status_text($status);
                                        @endphp

                                        <span class="badge bg-{{ $bgColor }}"> {{$badgeText}} </span>
                                        ({{ $latestInfo->created_at->diffForHumans() }})
                                    @else
                                        <span class="badge bg-secondary">pas d'activié</span>
                                    @endif
                                </td>
                                <td>{{$latestRequest->book?->title ?? 'N/A'}}</td>
                                <td>
                                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.statistics.users.history', $user->id) }}"
                                        class="btn btn-secondary btn-sm"
                                        title="View History">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.statistics.users.export') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel mr-2"></i> Export all
            </a>
        </div>

        {{-- Keep pagination for navigation --}}
        {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
