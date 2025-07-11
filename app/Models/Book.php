<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

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

    protected $dates = ['publication_date'];

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class, 'book_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_categories', 'book_id', 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'book_tags', 'book_id', 'tag_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }

    public function publishers(): BelongsToMany
    {
        return $this->belongsToMany(Publisher::class, 'book_publishers', 'book_id', 'publisher_id');
    }
}
