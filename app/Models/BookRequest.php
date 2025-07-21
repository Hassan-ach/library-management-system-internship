<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Carbon\Carbon;
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

    public function latestRequestInfo()
    {
        return $this->hasOne(RequestInfo::class, 'request_id')->latestOfMany();
    }

    public function return_date()
    {
        $setting = Setting::find(1);
        $latestInfo = $this->latestRequestInfo;

        if ($latestInfo && $latestInfo->status === RequestStatus::BORROWED) {
            $maxDuree = $setting?->DUREE_EMPRUNT_MAX ?? 3;

            return Carbon::parse($latestInfo->created_at)->addDays($maxDuree);
        }

        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
