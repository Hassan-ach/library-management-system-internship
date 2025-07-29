<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Retourne la requête pour l'export / Return query for export
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * En-têtes des colonnes / Column headers
     */
    public function headings(): array
    {
        return [
            'ID',
            'Prénom / First Name',
            'Nom / Last Name',
            'Email',
            'Date d\'inscription / Registration Date',
            'Statut / Status',
            'Rôle / Role',
            'Dernière Activité / Last Activity'
        ];
    }

    /**
     * Mappage des données / Data mapping
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->created_at ? $user->created_at->format('d/m/Y') : '',
            $user->is_active ? 'Active' : 'Inactive',
            $user->role->value ?? 'N/A',
            $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : ''
        ];
    }

    /**
     * Style du fichier Excel / Excel file styling
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style de l'en-tête / Header styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'color' => ['rgb' => '4472C4']
                ]
            ],
        ];
    }

    public function startCell(): string
    {
        return 'B2';
    }
}