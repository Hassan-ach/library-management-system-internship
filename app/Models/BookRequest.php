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
        $maxDuree = optional(Setting::find(1))->DUREE_EMPRUNT_MAX ?? 4;

        $borrowedInfo = $this->requestInfo()
            ->where('status', RequestStatus::BORROWED)
            ->orderBy('created_at')
            ->first();

        $returnedInfo = $this->requestInfo()
            ->where('status', RequestStatus::RETURNED)
            ->orderBy('created_at', 'desc')
            ->first();

        $latestStatus = $this->latestRequestInfo?->status;

        if ($latestStatus === RequestStatus::RETURNED && $returnedInfo) {
            return $returnedInfo->created_at; // actual return date
        }

        if (($latestStatus === RequestStatus::BORROWED || $latestStatus === RequestStatus::OVERDUE) && $borrowedInfo) {
            return Carbon::parse($borrowedInfo->created_at)->addDays($maxDuree); // expected return date
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
