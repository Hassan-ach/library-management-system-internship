<?php

namespace App\Models;

use App\Enums\UserRole;

class Librarian extends User
{
    //
    protected static function booted()
    {
        static::addGlobalScope('student', function ($query) {
            $query->where('role', UserRole::LIBRARIAN->value);
        });
    }

    // Relatioships
    public function requestsInfo()
    {
        return $this->hasMany(RequestInfo::class, 'user_id');
    }
}
