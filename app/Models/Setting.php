<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'DUREE_EMPRUNT_MAX',
        'NOMBRE_EMPRUNTS_MAX',
        'DUREE_RESERVATION',
    ];

    public static function getSettings()
    {
        return self::firstOrCreate([]);
    }
}
