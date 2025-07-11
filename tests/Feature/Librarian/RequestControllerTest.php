<?php

namespace Tests\Feature\Librarian;

use App\Enums\RequestStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\BookRequest;
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
}
