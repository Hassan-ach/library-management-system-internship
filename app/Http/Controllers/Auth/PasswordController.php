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

        try {
            $req->validate([
                'email' => 'required|email|exist',
            ]);
            $status = Password::sendResetLink(
                $req->only('email')
            );

            return back()->with('status', 'Si votre adresse e-mail est enregistrée, nous vous avons envoyé un lien de réinitialisation.'); // Translated
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur est survenue. Veuillez réessayer plus tard.'); // Translated
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
                       ? redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé avec succès.') // Translated
                       : back()->withErrors(['error' => $status]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer plus tard.']); // Translated
        }
    }
}
