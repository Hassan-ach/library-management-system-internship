<?php

use App\Enums\RequestStatus;
use App\Models\Book;
use App\Models\RequestInfo;

if (! function_exists('get_borrowed_copies')) {
    function get_borrowed_copies(Book $book): int
    {
        return RequestInfo::whereIn('request_id', function ($query) use ($book) {
            $query->select('id')
                ->from('book_requests')
                ->where('book_id', $book->id);
        })
            ->whereIn('status', [RequestStatus::BORROWED, RequestStatus::APPROVED])
            ->latest()
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
