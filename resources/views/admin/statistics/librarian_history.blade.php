@php
use App\Enums\RequestStatus;
@endphp

@extends('admin.dashboard')

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
            <h5 class="mb-0">Librarian Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Personal Info Column -->
                <div class="col-md-6 border-end">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-user me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Full name</h6>
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
                            <h6 class="mb-0">Role</h6>
                            <p class="mb-0">{{ $user->role->name }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Info Column -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-list-ol me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Total Requests</h6>
                            <p class="mb-0">{{ $totalRequests }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-3 text-primary"></i>
                        <div>
                            <h6 class="mb-0">Last Activity</h6>
                            <p class="mb-0">
                                @if($requests->count() > 0)
                                    {{ $requests->first()['created_at']->format('M d, Y H:i') }}
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

<!-- In your table rows -->
<table class="table table-striped table-hover">
    <thead class="bg-primary text-black">
        <tr>
            <th class="text-black">ID</th>
            <th class="text-black">Response Date</th>
            <th class="text-black">Book Title</th>
            <th class="text-black">Status</th>
            <th class="text-black">Requested At</th>
            <th class="text-black">Requested By</th>
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
                    lol
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $requests->links() }}
</div>
</div>
@endsection