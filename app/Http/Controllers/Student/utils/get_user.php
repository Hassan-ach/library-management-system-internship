<?php

namespace App\Http\Controllers\Student\utils;

use App\Enums\UserRole;
use App\Models\Admin;
use App\Models\Librarian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

function get_auth_user(): ?User
{
    $baseUser = Auth::user();

    return match ($baseUser->role) {
        UserRole::STUDENT => get_student($baseUser->id),
        UserRole::LIBRARIAN => get_librarian($baseUser->id),
        UserRole::ADMIN => get_admin($baseUser->id),
        default => null,
    };
}

function get_student($id): Student
{
    return Student::with('bookRequests')->find($id);
}
function get_admin($id): Admin
{
    return Admin::find($id);
}
function get_librarian($id): Librarian
{
    return Librarian::with('requestInfo')->find($id);
}
