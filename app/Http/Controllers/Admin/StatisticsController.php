<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
{
    //
    public function dashboard(){
        return view('admin.statistics.index');
    }
    public function users_stat(Request $request)
    {
        // try {
            $users = User::latest()->paginate(25);

            return view('admin.statistics.users', compact('users'));
        // }
        //  catch (\Exception $e) {
        //     return redirect()->route('admin.users.index')
        //         ->with('error', 'Unable to load users: '.$e->getMessage());
        // }
    }

    public function books_stat(Request $request)
    {
        // try {
            $users = User::latest()->paginate(25);

            return view('admin.statistics.books', compact('users'));

    }

    public function requests_stat(Request $request)
    {
        // try {
            $users = User::latest()->paginate(25);

            return view('admin.statistics.requests', compact('users'));

    }
    
    public function exportUsers(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }

    public function exportRequests(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }

    public function exportBooks(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }
}
