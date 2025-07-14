<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentStatisticsController extends Controller
{
    //
    public function index(Request $req, $studentId)
    {
        //
        try {
            $student = Student::with('bookRequests.latestRequestInfo')
                ->findOrFail($studentId);

            return view('librarian.statistics.index', compact('student'));

        } catch (\Throwable $th) {
            // throw $th;
            return back()
                ->with(['error' => 'Error while querying Student profile']);
        }

    }
}
