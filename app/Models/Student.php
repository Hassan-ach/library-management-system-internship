<?php

namespace App\Models;

use App\Enums\UserRole;

class Student extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('student', function ($query) {
            $query->where('role', UserRole::STUDENT->value);
        });
    }

    // Relatioships
    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class, 'user_id');
    }

    public function requestInfo()
    {
        return $this->hasMany(RequestInfo::class, 'user_id');
    }
}
