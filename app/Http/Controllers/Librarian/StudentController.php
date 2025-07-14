<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function info(Request $req, $studentId)
    {
        //
        try {
            $student = Student::with('bookRequests.latestRequestInfo')
                ->findOrFail($studentId);

            return view('student.profile', compact('student'));

        } catch (\Throwable $th) {
            // throw $th;
            return back()
                ->with(['error' => 'Error while querying Student profile']);
        }

    }
}
