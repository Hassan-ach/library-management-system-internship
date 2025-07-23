{{-- @extends('layouts.admin')

@section('content') --}}
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Activity History for {{ $user->full_name }}</h4>
                <a href="{{ route('admin.statistics.users') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Performed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $this->getActionColor($activity->action) }}">
                                    {{ ucfirst($activity->action) }}
                                </span>
                            </td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                @if($activity->causer)
                                    {{ $activity->causer->name }}
                                @else
                                    System
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No activity found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}