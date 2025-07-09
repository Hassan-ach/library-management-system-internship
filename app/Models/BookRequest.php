<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    public function requestInfo()
    {
        return $this->hasMany(RequestInfo::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class.'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
