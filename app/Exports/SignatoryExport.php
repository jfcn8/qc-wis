<?php

namespace App\Exports;

use App\Models\Signatory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SignatoryExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Signatory::orderBy('name')->select([
            'name',
            'designation',
            'mism_certified',
            'mism_approved',
            'ssmi_noting',
            'ssmi_certifying',
            'ssmi_approving',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Designation',
            'MISM Certify',
            'MISM Approve',
            'SSMI Note',
            'SSMI Certify',
            'SSMI Approve',
        ];
    }
}
