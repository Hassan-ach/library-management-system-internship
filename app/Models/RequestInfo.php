<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Model;

class RequestInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'book_request_id',
        'status',
    ];

    // Relationships
    public function bookRequest()
    {
        return $this->belongsTo(BookRequest::class, 'book_request_id');
    }

    public function librarians()
    {
        return $this->belongsTo(Librarian::class, 'user_id');
    }

    protected function casts()
    {
        return [
            'status' => RequestStatus::class,
        ];
    }
}
