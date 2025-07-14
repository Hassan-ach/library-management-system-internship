<?php

namespace Tests\Feature\Helpers;

use App\Enums\RequestStatus;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_borrowed_copies_counts_borrowed_and_approved_requests()
    {
        $book = Book::factory()->create();

        // Create requests with different statuses
        $borrowedRequest = BookRequest::factory()->create(['book_id' => $book->id]);
        $approvedRequest = BookRequest::factory()->create(['book_id' => $book->id]);
        $rejectedRequest = BookRequest::factory()->create(['book_id' => $book->id]);

        // Create request infos
        RequestInfo::factory()->create([
            'request_id' => $borrowedRequest->id,
            'status' => RequestStatus::BORROWED,
            'created_at' => now(),
        ]);

        RequestInfo::factory()->create([
            'request_id' => $approvedRequest->id,
            'status' => RequestStatus::APPROVED,
            'created_at' => now(),
        ]);

        RequestInfo::factory()->create([
            'request_id' => $rejectedRequest->id,
            'status' => RequestStatus::REJECTED,
            'created_at' => now(),
        ]);

        $result = get_borrowed_copies($book);

        $this->assertEquals(2, $result); // Only BORROWED and APPROVED should be counted
    }

    public function test_get_borrowed_copies_returns_zero_when_no_qualifying_requests()
    {
        $book = Book::factory()->create();
        $bookRequest = BookRequest::factory()->create(['book_id' => $book->id]);

        // Create request info with non-qualifying status
        RequestInfo::factory()->create([
            'request_id' => $bookRequest->id,
            'status' => RequestStatus::PENDING,
            'created_at' => now(),
        ]);
        RequestInfo::factory()->create([
            'request_id' => $bookRequest->id,
            'status' => RequestStatus::CANCELED,
            'created_at' => now(),
        ]);
        RequestInfo::factory()->create([
            'request_id' => $bookRequest->id,
            'status' => RequestStatus::REJECTED,
            'created_at' => now(),
        ]);

        $result = get_borrowed_copies($book);

        $this->assertEquals(0, $result);
    }

    public function test_get_latest_info_returns_most_recent_request_info()
    {
        $bookRequest = BookRequest::factory()->create();

        // Create older request info
        RequestInfo::factory()->create([
            'request_id' => $bookRequest->id,
            'status' => RequestStatus::PENDING,
            'created_at' => now()->subDays(2),
        ]);

        // Create newer request info (this should be returned)
        $latestRequestInfo = RequestInfo::factory()->create([
            'request_id' => $bookRequest->id,
            'status' => RequestStatus::APPROVED,
            'created_at' => now(),
        ]);

        $result = get_latest_info($bookRequest->id);

        $this->assertNotNull($result);
        $this->assertEquals($latestRequestInfo->id, $result->id);
        $this->assertEquals(RequestStatus::APPROVED, $result->status);
    }

    public function test_get_latest_info_returns_null_when_no_request_info_exists()
    {
        $nonExistentRequestId = 999;

        $result = get_latest_info($nonExistentRequestId);

        $this->assertNull($result);
    }
}
