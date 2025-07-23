<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::STUDENT->value,
            'password' => bcrypt('password'),
        ]);
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);

        $response->assertViewIs('student.profile.show');

        $response->assertViewHasAll([
            'user', 'requests',
        ]);

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_view_profile(): void
    {
        //
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
