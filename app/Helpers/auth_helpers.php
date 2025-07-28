<?php

use App\Enums\UserRole;
use App\Models\Admin;
use App\Models\Librarian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (! function_exists('get_auth_user')) {
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
}

function get_student(int $id): Student
{
    return Student::with('bookRequests', 'bookRequests.latestRequestInfo', 'bookRequests.book')->findOrFail($id);
}

function get_admin(int $id): Admin
{
    return Admin::findOrFail($id);
}

function get_librarian(int $id): Librarian
{
    return Librarian::with('requestsInfo')->findOrFail($id);
}
