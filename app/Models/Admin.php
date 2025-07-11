<?php

namespace App\Models;

use App\Enums\UserRole;

class Admin extends User
{
    //
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('student', function ($query) {
            $query->where('role', UserRole::ADMIN->value);
        });
    }

    protected $attributes = [
        'role' => UserRole::ADMIN,
    ];

    // Relatioships
}
