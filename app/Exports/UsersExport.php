<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithCustomStartCell
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