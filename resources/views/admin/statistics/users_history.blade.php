@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    @if(Session::has('error'))
        <script>
            toastr.error("{{ Session::get('error') }}");
        </script>
    @endif

    <!-- User Information Section -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="m-0 font-weight-bold">Student Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $user->firstname }} {{ $user->lastname }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ $user->role->name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total Requests:</strong> {{ $requests->total() }}</p>
                    <p><strong>Last Activity:</strong> 
                        @if($requests->count() > 0)
                            {{ $requests->first()->created_at->format('M d, Y H:i') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests History Section -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4 class="m-0 font-weight-bold text-primary">Borrowing History</h4>
            <a href="{{ route('admin.statistics.users') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Students
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Request Date</th>
                            <th>Book Title</th>
                            <th>Status</th>
                            <th>Processed Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $index => $request)
                        <tr>
                            <td>{{ $index + $requests->firstItem() }}</td>
                            <td>
                                {{ $request['created_at']->format('Y-m-d H:i') }}<br>
                                <small class="text-muted">{{ $request['created_diff'] }}</small>
                            </td>
                            <td>{{ $request->book->title ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $status = $request->RequestInfo->first()->status->value ?? 'UNKNOWN';
                                @endphp

                                <span class="badge bg-
                                    @if($status === 'PENDING') warning
                                    @elseif($status === 'BORROWED') success
                                    @elseif($status === 'APPROVED') info
                                    @elseif($status === 'REJECTED') danger
                                    @elseif($status === 'OVERDUE') dark
                                    @elseif($status === 'RETURNED') success
                                    @elseif($status === 'CANCELED') secondary
                                    @else primary @endif
                                ">
                                    @if($status === 'PENDING') en attente
                                    @elseif($status === 'BORROWED') emprunté
                                    @elseif($status === 'APPROVED') approuvé
                                    @elseif($status === 'REJECTED') rejeté
                                    @elseif($status === 'OVERDUE') depassé
                                    @elseif($status === 'RETURNED') retourné
                                    @elseif($status === 'CANCELED') annulé
                                    @else inconnu @endif
                                </span>
                            </td>
                            <td>
                                <td>
                                    @if($request['processed_at'])
                                        {{ $request['processed_at']->format('Y-m-d H:i') }}<br>
                                        <small class="text-muted">{{ $request['processed_diff'] }}</small>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No borrowing history found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $requests->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection