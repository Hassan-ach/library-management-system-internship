<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forget_form_is_accessible()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.forget');
    }

    public function test_send_reset_link_with_valid_email()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_send_reset_link_with_invalid_email()
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'notfound@example.com',
        ]);

        // Always show generic message for security
        $response->assertSessionHas('status');
        Notification::assertNothingSent();
    }

    public function test_reset_form_renders_properly()
    {
        $response = $this->get(route('password.reset', ['token' => 'dummy-token', 'email' => 'test@example.com']));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', 'dummy-token');
    }

    public function test_reset_password_with_valid_data()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
        $this->assertTrue(password_verify('new-password', $user->fresh()->password));
    }

    public function test_reset_password_fails_with_invalid_token()
    {
        $user = User::factory()->create();

        $response = $this->post(route('password.update'), [
            'token' => 'wrong-token',
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors('error');
    }
}
