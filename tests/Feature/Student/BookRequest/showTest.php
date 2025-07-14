<?php

namespace Tests\Feature\Student\BookRequest;

use App\Models\BookRequest;
use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class showTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_their_own_book_request()
    {
        // Arrange: Create a user and book request
        $user = User::factory()->create();
        $bookRequest = BookRequest::factory()->for($user)->create();

        // Create latest request info with status
        $reqInfo = RequestInfo::factory()
            ->for($user)
            ->for($bookRequest)
            ->pending()
            ->create();

        $this->assertDatabaseHas('request_infos', [
            'id' => $reqInfo->id,
        ]);

        // Act: Visit the route as the correct user
        $response = $this->actingAs($user)->get(route('student.requests.show', $bookRequest->id));

        $response->assertViewIs('student.requests.show');
        $response->assertViewHas(['bookReq', 'reqInfo']);
    }

    public function test_student_cannot_view_other_students_request()
    {
        // Arrange: Two different users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Book request belongs to user2
        $bookRequest = BookRequest::factory()->for($user2, 'user')->create();

        // Act: User1 tries to access user2's request
        $response = $this->actingAs($user1)->get(route('student.requests.show', $bookRequest->id));

        // Assert: Should be forbidden (or redirected with error)
        $response->assertStatus(302); // redirect
        $response->assertSessionHas('error', "You're not allowed to see this book request");
    }

    public function test_it_handles_request_info_lookup_failure_gracefully()
    {
        // Arrange: User and valid book request
        $user = User::factory()->create();
        $bookRequest = BookRequest::factory()->for($user, 'user')->create();

        // $this->assertDatabaseHas('request_infos', [
        //     //
        //     'request_id' => $bookRequest->id,
        // ]);

        // Act
        $response = $this->actingAs($user)->get(route('student.requests.show', $bookRequest->id));

        // Assert: Catches the error and shows a friendly message
        $response->assertStatus(422);
        $response->assertSessionHas('error', 'Error while querying request');
    }
}
