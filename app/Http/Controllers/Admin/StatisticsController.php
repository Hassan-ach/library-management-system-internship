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
    public function exportUsers(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }
}
