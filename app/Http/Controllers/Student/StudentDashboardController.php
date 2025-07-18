<?php

namespace App\Http\Controllers\Student;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = Student::findOrFail(Auth::user()->id);

        // Get all book requests with their latest status, sorted by newest first
        $bookRequests = BookRequest::with('latestRequestInfo')
            ->where('user_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        // books currently borrowed (latestRequestInfo status BORROWED)
        $borrowed = $bookRequests->filter(fn ($req) => $req->latestRequestInfo && $req->latestRequestInfo->status === RequestStatus::BORROWED
        );

        // requests still pending approval (status PENDING)
        $pending = $bookRequests->filter(fn ($req) => $req->latestRequestInfo && $req->latestRequestInfo->status === RequestStatus::PENDING
        );

        // books successfully returned (status RETURNED)
        $returned = $bookRequests->filter(fn ($req) => $req->latestRequestInfo && $req->latestRequestInfo->status === RequestStatus::RETURNED
        );

        // overdue books
        // $overdue = $bookRequests->filter(fn ($req) => $req->latestRequestInfo && $req->latestRequestInfo->status === RequestStatus::OVERDUE);
        $maxDuree = Setting::find(1)->DUREE_EMPRUNT_MAX ?? 4;
        $today = now();

        $overdue = $bookRequests->filter(function ($req) use ($today, $maxDuree) {
            $latestInfo = $req->latestRequestInfo;

            if ($latestInfo === RequestStatus::OVERDUE) {
                return true;
            }

            if (! $latestInfo || $latestInfo->status !== RequestStatus::BORROWED) {
                return false;
            }

            $borrowedDate = $latestInfo->created_at;
            $dueDate = $borrowedDate->copy()->addDays($maxDuree);

            return $dueDate->lt($today);
        });

        // Recent borrowings (limit to 5 most recent)
        $recent = $bookRequests->take(5);

        return view('student.dashboard', compact(
            'student',
            'borrowed',
            'pending',
            'returned',
            'overdue',
            'recent'
        ));

    }
}
