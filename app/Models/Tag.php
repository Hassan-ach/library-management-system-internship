<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['label'];

    public function books():BelongsToMany{
        return $this->belongsToMany( Book::class, 'book_tags', 'tag_id', 'book_id');
    }
}
