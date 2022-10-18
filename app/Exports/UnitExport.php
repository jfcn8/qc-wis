<?php

namespace App\Exports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnitExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Unit::orderBy('unit')->select('mnemonic','unit')->get();
    }

    public function headings(): array
    {
        return [
            'Mnemonic',
            'Unit',
        ];
    }
}
