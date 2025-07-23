<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
// for butter excel view 
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithCustomStartCell, ShouldAutoSize
{
    public function collection()
    {
        return User::all();
    }

    public function startCell(): string
    {
        return 'B2';
    }
}