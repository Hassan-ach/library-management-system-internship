<?php

namespace Tests\Feature\Student\Books;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSearchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_student_can_search_books_by_title(): void
    {
        // Create an active student user
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'is_active' => true,
        ]);

        // Create books
        Book::factory()->create(['title' => 'Laravel Guide']);
        Book::factory()->create(['title' => 'PHP for Beginners']);
        Book::factory()->create(['title' => 'Vue.js Basics']);

        // Act as the user and search for 'Laravel'
        $response = $this->actingAs($user)->get(route('student.books.search', [
            'query' => 'Laravel',
        ]));
        $response->assertViewHas('books');
        $response->assertStatus(200);
        $response->assertSee('Laravel Guide');
        $response->assertDontSee('Vue.js Basics');
    }
}
