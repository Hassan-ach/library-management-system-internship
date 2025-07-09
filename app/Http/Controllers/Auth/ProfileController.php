<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function index(Request $req)
    {
        $user = get_auth_user();

        if (! $user) {
            abort(404, 'User not found or role mismatch');
        }

        $requests = $user->bookRequests;

        return view('profile-page', compact('user', 'requests'));

    }
}
