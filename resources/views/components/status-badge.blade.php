{{-- resources/views/components/status-badge.blade.php --}}
@props(['status'])

@php
    $badgeClass = match($status) {
        'pending' => 'badge-warning',
        'approved' => 'badge-info',
        'borrowed' => 'badge-primary',
        'returned' => 'badge-success',
        'rejected', 'overdue' => 'badge-danger',
        'cancelled' => 'badge-secondary',
        default => 'badge-secondary',
    };
@endphp

<span class="badge {{ $badgeClass }}">{{ ucfirst(get_request_status_text(App\Enums\RequestStatus::from($status))) }}</span>
