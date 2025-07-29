<?php

namespace App\Exports;

use App\Enums\UserRole;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LibrariansExport implements FromQuery, WithHeadings, WithMapping,  ShouldAutoSize, WithCustomStartCell
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Prénom / First Name',
            'Nom / Last Name',
            'Email',
            'Rôle / Role',
            'Statut / Status',
            'Dernière Activité / Last Activity'
        ];
    }

    public function map($librarian): array
    {
        return [
            $librarian->id,
            $librarian->first_name,
            $librarian->last_name,
            $librarian->email,
            'Bibliothécaire / Librarian', // Static since we filter by role
            $librarian->is_active ? 'Actif/Active' : 'Inactif/Inactive',
            $librarian->last_activity_at?->format('d/m/Y H:i') ?? 'N/A'
        ];
    }

    // public function styles(Worksheet $sheet)
    // {
    //     return [
    //         // Header style
    //         1 => [
    //             'font' => [
    //                 'bold' => true,
    //                 'color' => ['rgb' => 'FFFFFF']
    //             ],
    //             'fill' => [
    //                 'fillType' => 'solid',
    //                 'color' => ['rgb' => '5B9BD5'] // Different blue from students
    //             ]
    //         ],
    //         // Status column coloring
    //         'F' => [
    //             'font' => [
    //                 'color' => function($cell) {
    //                     return str_contains($cell->getValue(), 'Actif') 
    //                         ? ['rgb' => '00B050'] // Green
    //                         : ['rgb' => 'FF0000']; // Red
    //                 }
    //             ]
    //         ]
    //     ];
    // }

    public function startCell(): string
    {
        return 'B2';
    }
}