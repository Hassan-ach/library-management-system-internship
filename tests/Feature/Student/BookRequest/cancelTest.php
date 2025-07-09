<?php

namespace Tests\Feature\Student\BookRequest;

use App\Enums\RequestStatus;
use App\Enums\UserRole;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class cancelTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_user_can_cancel_his_own_pending_request(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'is_active' => true,
        ]);
        $bookReq = BookRequest::factory()->create([
            'user_id' => $user->id,
        ]);

        RequestInfo::factory()
            ->for($user)
            ->for($bookReq)
            ->pending()
            ->create();
        // RequestInfo::factory()->create([
        //     'user_id' => $user->id,
        //     'request_id' => $bookReq->id,
        //     'status' => RequestStatus::PENDING,
        //     'created_at' => now(),
        // ]);

        $this->assertDatabaseHas('request_infos', [
            'request_id' => $bookReq->id,
            'status' => RequestStatus::PENDING,
        ]);

        $resp = $this->actingAs($user)->patch("/request/{$bookReq->id}");

        $resp->assertRedirect();

        $this->assertDatabaseHas('request_infos', [
            'request_id' => $bookReq->id,
            'status' => RequestStatus::CANCELED,
        ]);

        $resp->assertSessionHas('success', 'Request canceled successfully');

    }

    public function test_user_cannot_cancel_an_other_pending_request(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'is_active' => true,
        ]);
        $user2 = User::factory()->create([
            'role' => UserRole::STUDENT,
            'is_active' => true,
        ]);
        $bookReq = BookRequest::factory()->create([
            'user_id' => $user->id,
        ]);

        RequestInfo::factory()
            ->for($user)
            ->for($bookReq)
            ->pending()
            ->create();
        // RequestInfo::factory()->create([
        //     'user_id' => $user->id,
        //     'request_id' => $bookReq->id,
        //     'status' => RequestStatus::PENDING,
        //     'created_at' => now(),
        // ]);

        $this->assertDatabaseHas('request_infos', [
            'request_id' => $bookReq->id,
            'status' => RequestStatus::PENDING,
        ]);

        $resp = $this->actingAs($user2)->patch("/request/{$bookReq->id}");

        $resp->assertRedirect();

        $resp->assertSessionHas('error', 'You\'re not allowed to cancel this book request');

    }
}
