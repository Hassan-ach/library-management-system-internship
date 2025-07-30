<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $req)
    {
        $credentials = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->with([
                'error' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.', // Translated
            ]);
        }

        $req->session()->regenerate();

        return redirect()->intended('/')->with('message', 'Connecté avec succès.'); // Translated
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Déconnecté avec succès.'); // Translated
    }
}
