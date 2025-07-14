<?php

namespace Tests\Feature\Librarian;

use App\Enums\RequestStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_librarian_can_process_request_status()
    {
        $librarian = User::factory()->create(['role' => UserRole::LIBRARIAN]);

        $student = User::factory()->create(['role' => UserRole::STUDENT]);
        $book = Book::factory()->create();

        $bookRequest = BookRequest::factory()->create([
            'user_id' => $student->id,
            'book_id' => $book->id,
        ]);

        $data = [
            'status' => RequestStatus::APPROVED->value,
        ];

        $response = $this->actingAs($librarian)->post("/request/info/{$bookRequest->id}", $data);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'status updated successfully');

        $this->assertDatabaseHas('request_infos', [
            'request_id' => $bookRequest->id,
            'status' => RequestStatus::APPROVED->value,
        ]);
    }

    public function test_librarian_can_view_paginated_book_requests()
    {
        $librarian = User::factory()->create([
            'role' => UserRole::LIBRARIAN,
        ]);

        BookRequest::factory()
            ->count(15)
            ->hasLatestRequestInfo()
            ->create();

        $response = $this->actingAs($librarian)->get(route('requests.all'));

        $response->assertStatus(200);
        $response->assertViewIs('librarian.viewAllRequests');
        $response->assertViewHas('requests');

        $this->assertTrue($response->original->getData()['requests']->count() <= 10); // paginated
    }

    public function test_it_displays_a_single_book_request()
    {
        $librarian = User::factory()->create(['role' => UserRole::LIBRARIAN]);

        $book = Book::factory()->create();
        $student = User::factory()->create();

        $bookRequest = BookRequest::factory()
            ->for($student, 'user')
            ->for($book, 'book')
            ->create();

        RequestInfo::factory()
            ->for($bookRequest, 'bookRequest')
            ->for($student, 'user')
            ->create();

        $response = $this->actingAs($librarian)
            ->get(route('requests.single', $bookRequest->id));

        $response->assertStatus(200);
        $response->assertViewIs('librarian.viewSingleRequest');
        $response->assertViewHas('request', function ($r) use ($bookRequest) {
            return $r->id === $bookRequest->id;
        });
    }

    public function test_it_redirects_back_if_request_not_found()
    {
        $librarian = User::factory()->create(['role' => 'librarian']);

        $response = $this->actingAs($librarian)
            ->get(route('requests.single', 999));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Error while fetching the request information');
    }
}
