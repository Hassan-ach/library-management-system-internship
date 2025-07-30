<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Student;



class StudentStatisticsController extends Controller
{
    public function index(Student $student){
        try{
            $user = Student::findOrFail($student->id);
            $requests = $user->bookRequests()
                ->with('latestRequestInfo', 'book')
                ->get();
            $nbr_request = $requests->count();

            return view('librarian.student.studentStatistics', compact('user', 'requests', 'nbr_request'));

        }catch(\Throwable $th){
            return $th;
        }
        
        
    }
}
