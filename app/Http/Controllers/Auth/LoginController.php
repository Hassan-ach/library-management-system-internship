<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Error;
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
        try {
            if (! Auth::attempt($credentials)) {
                return redirect('/login')->withErrors(['email' => 'Invalid credentials.'])->withInput();
            }

            return redirect()->intended()->with('message', 'Logged in successfully');
            // return redirect('/')->with('message', 'Logged in successfully');
        } catch (Error $e) {
            return redirect('/login')->with('error', 'Could not create token. Please try again.');

        }

    }
}
