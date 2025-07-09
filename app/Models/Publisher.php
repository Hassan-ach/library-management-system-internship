<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Publisher extends Model
{
    protected $fillable = ['name'];

    public function books():BelongsToMany{
        return $this->belongsToMany( Book::class, 'book_publishers', 'publisher_id', 'book_id');
    }
}
