<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'isbn',
        'description',
        'publication_date',
        'number_of_pages',
        'total_copies',
    ];

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class, 'book_id');
    }
}
