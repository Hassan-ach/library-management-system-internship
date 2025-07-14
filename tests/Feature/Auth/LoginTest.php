<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {

        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'password' => bcrypt('password123'),
        ]);

        // Simulate login request
        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Assert the response redirects or is successful
        $response->assertRedirect('/');

        // Assert user is authenticated
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::STUDENT,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }
}
