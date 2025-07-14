<?php

use App\Enums\RequestStatus;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\RequestInfo;

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
