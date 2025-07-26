<?php

use App\Enums\RequestStatus;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use Illuminate\Support\Facades\Auth;

if (! function_exists('get_borrowed_copies')) {
    function get_borrowed_copies(Book $book): int
    {
        // Load all book requests with their latest request info eager loaded
        $bookRequests = BookRequest::with('latestRequestInfo')
            ->where('book_id', $book->id)
            ->get();

        // Count how many have latestRequestInfo with status BORROWED or APPROVED
        return $bookRequests->filter(function ($request) {
            return $request->latestRequestInfo
                && in_array($request->latestRequestInfo->status, [RequestStatus::BORROWED, RequestStatus::APPROVED]);
        })->count();
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
    function get_last_activity($stud_id) {
        $last_req = BookRequest::with('latestRequestInfo')->where('user_id', $stud_id)->latest()->first();
        $last_activity = $last_req->latestRequestInfo->status;
        $book_title = Book::findOrFail($last_req->book_id)->title;

        return [
            'last_activity' => $last_activity, 
            'book_title' => $book_title
        ];
    }
}

// app/Helpers/RequestHelpers.php
function get_request_status_badge($request) {
    $latestInfo = $request->RequestInfo->sortByDesc('created_at')->first();
    $status = strtolower($latestInfo->status ?? 'unknown');
    
    $bgColor = match($status) {
        'borrowed', 'returned' => 'success',
        'pending' => 'warning',
        'approved' => 'info',
        'rejected' => 'danger',
        'overdue' => 'dark',
        'canceled' => 'secondary',
        default => 'primary'
    };
    
    return '<span class="badge badge-'.$bgColor.'">'.ucfirst($status).'</span>';
}


// Count how many books aren't available
if (! function_exists('get_non_available_books')) {
    function get_non_available_books(): int
    {
        // To find non available books, we check just the BookRequest table
        $book_requests = BookRequest::all();
        $count = 0;
        foreach( $book_requests as $request){
            $book = Book::find( $request->book_id);
            $count += get_borrowed_copies( $book);
        }
        
        return $count;
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
            COUNT(CASE WHEN status = "overdue" THEN 1 END) as overdue_requests
        ')->where('created_at', '>=', now()->subDays(30))
        ->where('user_id', $librarian_id)
        ->first();
        /*
        if (!$statistics) {
            $statistics = (object) [
                'returned_books' => 0,
                'approved_requests' => 0,
                'rejected_requests' => 0,
                'overdue_requests' => 0,
                'total_requests' => 0
            ];
        }
        */
        return $statistics;
    }
} 