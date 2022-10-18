<?php

namespace App\Exports;

use App\Models\Office;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OfficeExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Office::orderBy('office')->select(['office'])->get();
    }

    public function headings(): array
    {
        return [
            'Office',
        ];
    }
}
