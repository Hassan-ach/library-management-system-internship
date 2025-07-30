<?php

namespace App\Models;

use App\Enums\RequestStatus;
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

    protected $attributes = [
        'role' => UserRole::STUDENT,
    ];

    // Relatioships
    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function requestInfo()
    {
        return $this->hasMany(RequestInfo::class, 'user_id');
    }

    public function get_totale_borrowed_books(): int
    {
        return BookRequest::join('request_infos', function ($join) {
            $join->on('book_requests.id', '=', 'request_infos.request_id')
                ->whereRaw('request_infos.id = (
                 SELECT MAX(ri.id)
                 FROM request_infos ri
                 WHERE ri.request_id = book_requests.id
             )');
        })
            ->where('book_requests.user_id', $this->id)
            ->whereIn('request_infos.status', [
                RequestStatus::BORROWED,
                RequestStatus::APPROVED,
                RequestStatus::OVERDUE,
            ])
            ->count();
    }
}
