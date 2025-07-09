<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Category extends Model
{
    protected $fillable = ['label', 'description'];

    public function books():BelongsToMany{
        return $this->belongsToMany(Book::class, 'book_categories', 'category_id', 'book_id');
    }
}
