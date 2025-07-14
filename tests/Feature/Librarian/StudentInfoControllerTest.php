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

class StudentInfoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_librarian_can_view_student_profile_with_requests()
    {
        $librarian = User::factory()->create(['role' => UserRole::LIBRARIAN]);

        $student = User::factory()->create(['role' => UserRole::STUDENT]);

        $book = Book::factory()->create();

        $request = BookRequest::factory()->create([
            'user_id' => $student->id,
            'book_id' => $book->id,
        ]);

        RequestInfo::factory()->create([
            'request_id' => $request->id,
            'user_id' => $student->id,
            'status' => RequestStatus::PENDING,
        ]);

        $response = $this->actingAs($librarian)->get("/librarian/students/{$student->id}");

        $response->assertStatus(200);
        $response->assertViewIs('librarian.statistics.index');
        $response->assertViewHas('student');
    }
}
