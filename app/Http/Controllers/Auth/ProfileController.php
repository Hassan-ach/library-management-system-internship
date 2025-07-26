<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function show(Request $req)
    {
        $user = get_auth_user();

        if (! $user) {
            abort(404, 'User not found or role mismatch');
        }

        $requests = $user->bookRequests;

        return match ($user->role) {
            UserRole::STUDENT => view('student.profile.show', compact('user', 'requests')),
            UserRole::LIBRARIAN => view('librarian.profile.show', compact('user')),
            UserRole::ADMIN => view('admin.profile.show', compact('user')),
            default => null,
        };

    }
}
