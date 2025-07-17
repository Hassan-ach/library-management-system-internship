<?php

namespace App\Exports;

use App\Models\User;
use \Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\Exportable;
// use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// class UsersExport implements FromView, ShouldAutoSize){
    //     return User::all();
    // }
class UsersExport implements FromView
{
    // use Exportable;

    protected $users;

    public function __construct($users){
        $this->users = $users;
    }

    // public function view():View 
    // {
    //     $users = User::all();
    //     return view('users.export', [
    //         'users'=> $users
    //     ]);
    // }

    /**
     * @return \Illuminate\Support\Collection
     */

    // public function collection(){
    //     return User::all();
    // }

    public function view():View{
        return view("/admin/exports/usersExoprt",
         ["users"=> $this->users]);
    }
}
