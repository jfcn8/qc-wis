<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ItemExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    // use Exportable;
    public function collection()
    {
        return DB::table('items')
                ->join('articles', 'articles.article_id', 'items.article_id')
                ->join('units', 'units.unit_id', 'items.unit_id')
                ->join('references', 'references.item_id', 'items.item_id')
                ->selectRaw('articles.article,items.description,items.stock_number,units.unit,sum(references.stock) as stock')
                ->where('stock', '<>' , 0)
                ->groupBy('items.item_id','items.description','items.stock_number','articles.article','units.unit')
                ->orderby('articles.article')
                ->get();
    }

    public function headings(): array
    {
        return [
            'Article',
            'Description',
            'Stock Number',
            'Unit',
            'Current Stock'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
