<?php

namespace Tests\Feature\Student;

use App\Enums\RequestStatus;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_and_shows_correct_data()
    {
        // Create a user with STUDENT role and related student record
        $student = User::factory()->create(['role' => 'student']);

        // Create book requests for student
        $bookRequest1 = BookRequest::factory()->create(['user_id' => $student->id]);
        $bookRequest2 = BookRequest::factory()->create(['user_id' => $student->id]);
        $bookRequest3 = BookRequest::factory()->create(['user_id' => $student->id]);

        // Add latestRequestInfo with different statuses
        RequestInfo::factory()->create([
            'request_id' => $bookRequest1->id,
            'status' => RequestStatus::BORROWED,
            'created_at' => now()->subDays(2),
        ]);

        RequestInfo::factory()->create([
            'request_id' => $bookRequest2->id,
            'status' => RequestStatus::PENDING,
            'created_at' => now()->subDays(2),
        ]);

        RequestInfo::factory()->create([
            'request_id' => $bookRequest3->id,
            'status' => RequestStatus::RETURNED,
            'created_at' => now()->subDay(0),
        ]);

        // Act as the student and request dashboard page
        $response = $this->actingAs($student)->get(route('student.dashboard'));

        // Assert successful response and view loaded
        $response->assertStatus(200);
        $response->assertViewIs('student.dashboard');

        // Assert view has required data keys
        $response->assertViewHasAll([
            'student',
            'borrowed',
            'pending',
            'returned',
            'overdue',
            'recent',
        ]);

        // Extract data from view
        $borrowed = $response->viewData('borrowed');
        $pending = $response->viewData('pending');
        $returned = $response->viewData('returned');
        $overdue = $response->viewData('overdue');
        $recent = $response->viewData('recent');

        // Assert correct counts based on setup data
        $this->assertCount(1, $borrowed);
        $this->assertCount(1, $pending);
        $this->assertCount(1, $returned);
        $this->assertCount(0, $overdue);
        $this->assertLessThanOrEqual(5, $recent->count());
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('student.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
