<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_logout(): void
    {
        // Create and log in user
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->get('/logout');

        // Assert redirection after logout
        $response->assertRedirect('/login');

        //  Assert the user is logged out
        $this->assertGuest();
    }

    public function test_non_authenticated_user_cannot_access_logout(): void
    {
        $response = $this->get('/logout');

        // Laravel's default behavior: guest redirected to login
        $response->assertRedirect('/login');

        // Optional: assert no authenticated user
        $this->assertGuest();
    }
}
