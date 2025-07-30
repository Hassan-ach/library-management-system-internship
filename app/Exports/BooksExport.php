<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithCustomStartCell
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
            'Titre',
            'ISBN',
            'Nombre des pages',
            'Copies total'
        ];
    }

    public function map($book): array
    {
        return [
            $book->title,
            $book->isbn,
            $book->number_of_pages,
            $book->total_copies
        ];
    }

    public function startCell(): string
    {
        return 'B2';
    }
}