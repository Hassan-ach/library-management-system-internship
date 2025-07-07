<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_logout(): void
    {
        // Create and log in user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/logout');

        // Assert redirection after logout
        $response->assertRedirect('/login-page');

        //  Assert the user is logged out
        $this->assertGuest();
    }

    public function test_non_authenticated_user_can_logout(): void
    {
        // Create and log in user
        $user = User::factory()->create();

        $response = $this->get('/logout');

        // Assert redirection to login
        $response->assertRedirect('/login');

        $response->assertSessionHasErrors();

        //  Assert the user is logged out
        $this->assertGuest();
    }
}
