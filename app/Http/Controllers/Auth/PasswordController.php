<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    /**
     * Show the forget password form.
     */
    public function forget_form()
    {
        return view('auth.passwords.forget');
    }

    /**
     * Handle the reset link email sending.
     */
    public function send(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
        ]);

        try {
            $status = Password::sendResetLink(
                $req->only('email')
            );

            // Always show the same message for security
            return back()->with('status', 'If your email is registered, we’ve sent you a reset link.');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * Show the password reset form with token.
     */
    public function reset_form(Request $req, string $token)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $req->email]);
    }

    /**
     * Handle the password reset.
     */
    public function reset(Request $req)
    {
        $req->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $status = Password::reset(
                $req->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', 'Your password has been reset successfully.')
                : back()->withErrors(['error' => $status]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Something went wrong. Please try again later.']);
        }
    }
}
