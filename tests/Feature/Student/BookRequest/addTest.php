<?php

namespace Tests\Feature\Student\BookRequest;

use App\Enums\RequestStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class addTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_allowed_user_can_make_book_request(): void
    {
        // Create a user with the right role and active status
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'is_active' => true,
        ]);

        // Create a book with available copies
        $book = Book::factory()->create([
            'total_copies' => 3,
        ]);

        // Act as the user and send the POST request
        $resp = $this->actingAs($user)->post("/request/book/{$book->id}");

        // Assert redirection
        $resp->assertRedirect();

        // Assert success message
        $resp->assertSessionHas('success', 'Request submitted successfully');

        // Assert book request exists
        $this->assertDatabaseHas('book_requests', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        // Assert request info exists with PENDING status
        $this->assertDatabaseHas('request_infos', [
            'user_id' => $user->id,
            'status' => RequestStatus::PENDING,
        ]);
    }

    public function test_not_allowed_user_cannot_make_book_request(): void
    {
        // Create a user who is inactive
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'is_active' => false,
            'password' => bcrypt('password'),
        ]);

        // Create a book with available copies
        $book = Book::factory()->create([
            'total_copies' => 3,
        ]);

        // Act as the user and send the POST request
        $resp = $this->actingAs($user)->post("/request/book/{$book->id}");

        // Assert redirection
        $resp->assertRedirect();

        // Assert error message
        $resp->assertSessionHas('error', "You're not allowed to borrow this book");
    }

    public function test_allowed_user_cannot_make_book_request_when_no_copies_left(): void
    {
        // Create a user with the right role and active status
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'is_active' => true,
            'password' => bcrypt('password'),
        ]);

        // Create a book with 0 available copies
        $book = Book::factory()->create([
            'total_copies' => 0,
        ]);

        // Act as the user and send the POST request
        $resp = $this->actingAs($user)->post("/request/book/{$book->id}");

        // Assert redirection
        $resp->assertRedirect();

        // Assert error message
        $resp->assertSessionHas('error', "You're not allowed to borrow this book");
    }
}
