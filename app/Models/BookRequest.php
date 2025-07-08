<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
    ];

    public function requestInfo()
    {
        return $this->hasMany(RequestInfo::class, 'book_request_id');
    }

    public function student()
    {
        return $this->belongsTo(Book::class.'book_id');

    }

    public function librarian()
    {
        return $this->belongsTo(Librarian::class, 'user_id');
    }
}
