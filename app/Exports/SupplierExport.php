<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Supplier::orderBy('supplier')->select('supplier')->get();
    }

    public function headings(): array
    {
        return [
            'Supplier',
        ];
    }
}
