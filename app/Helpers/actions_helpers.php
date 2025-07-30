<?php

use App\Enums\RequestStatus;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use Illuminate\Support\Facades\Auth;

if (! function_exists('get_borrowed_copies')) {
    function get_borrowed_copies($bookId): int
    {
        return BookRequest::join('request_infos', function ($join) {
            $join->on('book_requests.id', '=', 'request_infos.request_id')
                ->whereRaw('request_infos.id = (
                 SELECT MAX(ri.id)
                 FROM request_infos ri
                 WHERE ri.request_id = book_requests.id
             )');
        })
            ->where('book_requests.book_id', $bookId)
            ->whereIn('request_infos.status', [
                RequestStatus::BORROWED,
                RequestStatus::APPROVED,
                RequestStatus::OVERDUE,
            ])
            ->count();
    }
}

if (! function_exists('get_latest_info')) {
    function get_latest_info($reqId): ?RequestInfo
    {
        $latestInfo = RequestInfo::where('request_id', $reqId)
            ->latest()
            ->first();

        return $latestInfo;
    }
}

if (! function_exists('get_last_activity')) {
    function get_last_activity($stud_id)
    {
        $last_req = BookRequest::with('latestRequestInfo')->where('user_id', $stud_id)->latest()->first();
        $last_activity = $last_req->latestRequestInfo->status;
        $book_title = Book::findOrFail($last_req->book_id)->title;

        return [
            'last_activity' => $last_activity,
            'book_title' => $book_title,
        ];
    }
}

if (! function_exists('get_request_status_badge')) {
    function get_request_status_badge($status)
    {
        $bgColor = match ($status) {
            RequestStatus::BORROWED => 'success',
            RequestStatus::RETURNED => 'success',
            RequestStatus::PENDING => 'warning',
            RequestStatus::APPROVED => 'info',
            RequestStatus::REJECTED => 'danger',
            RequestStatus::OVERDUE => 'dark',
            RequestStatus::CANCELED => 'secondary',
            default => 'primary'
        };

        return $bgColor;
    }
}

if (! function_exists('get_request_status_text')) {
    function get_request_status_text($status)
    {
        $badgeText = match ($status) {
            RequestStatus::BORROWED => 'Emprunté',
            RequestStatus::RETURNED => 'Rendu',
            RequestStatus::PENDING => 'En attente',
            RequestStatus::APPROVED => 'Approuvé',
            RequestStatus::REJECTED => 'Rejeté',
            RequestStatus::OVERDUE => 'depassé',
            RequestStatus::CANCELED => 'Annulé',
            default => 'Inconnu'
        };

        return $badgeText;
    }
}

// Count how many books aren't available
if (! function_exists('get_non_available_books')) {
    function get_non_available_books(): int
    {
        return BookRequest::join('request_infos', function ($join) {
            $join->on('book_requests.id', '=', 'request_infos.request_id')
                ->whereRaw('request_infos.id = (
                 SELECT MAX(ri.id)
                 FROM request_infos ri
                 WHERE ri.request_id = book_requests.id
             )');
        })
            ->whereIn('request_infos.status', [
                RequestStatus::BORROWED,
                RequestStatus::APPROVED,
                RequestStatus::OVERDUE,
            ])
            ->count();
    }
}

// Count how many books we have on our database
if (! function_exists('count_total_books')) {
    function count_total_books(): int
    {
        $count = Book::sum('total_copies');

        return $count;
    }
}

// Count the number of accepted/rejected requests and returned books in last 30 days
if (! function_exists('get_requests_statics')) {
    function get_requests_statics()
    {
        $librarian_id = Auth::user()->id;

        $statistics = RequestInfo::selectRaw('
            COUNT(CASE WHEN status = "returned" THEN 1 END) as returned_books,
            COUNT(CASE WHEN status = "approved" THEN 1 END) as approved_requests,
            COUNT(CASE WHEN status = "rejected" THEN 1 END) as rejected_requests,
            COUNT(CASE WHEN status = "overdue" THEN 1 END) as overdue_requests,
            COUNT(CASE WHEN status = "borrowed" THEN 1 END) as borrowed_books
        ')->where('created_at', '>=', now()->subDays(30))
            ->first();

        /*
        if (!$statistics) {
            $statistics = (object) [
            'approved_requests' => 0,
            'rejected_requests' => 0,
            'borrowed_books' =>0,
            'returned_books' => 0,
            'overdue_requests' => 0
            ];
        }
        */
        return $statistics;
    }
}
