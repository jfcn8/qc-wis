<?php

namespace App\Exports;

use App\Models\Classification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassificationExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Classification::orderBy('classification')->select('classification')->get();
    }

    public function headings(): array
    {
        return [
            'Classification',
        ];
    }
}
