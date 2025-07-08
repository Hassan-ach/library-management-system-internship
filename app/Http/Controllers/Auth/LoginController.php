<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $req)
    {
        $credentials = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'failed' => 'The provided credentials do not match our records.',
            ]);
        }

        $req->session()->regenerate();

        return redirect()->intended('/')->with('message', 'Logged in successfully');
    }
}
