<?php

namespace Tests\Feature\Student\Books;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksListTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_list_shows_books_with_pagination()
    {
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
        ]);
        Book::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('student.books.list'));

        $response->assertViewHas('books');

        $books = $response->viewData('books');
        $this->assertCount(10, $books);

    }

    public function test_books_list_shows_no_books_message_when_empty()
    {
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
        ]);
        $response = $this->actingAs($user)->get(route('student.books.list'));

        $response->assertSee('No books found.');
    }
}
