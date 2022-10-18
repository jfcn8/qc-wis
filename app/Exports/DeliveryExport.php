<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DeliveryExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    public $dateFrom, $dateTo;

    function __construct($dateFrom = null, $dateTo = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    // use Exportable;
    public function collection()
    {
        if ($this->dateFrom != null && $this->dateTo != null) {
            $deliveries = DB::table('deliveries')
            ->join('items', 'items.item_id', '=', 'deliveries.item_id')
            ->join('articles', 'articles.article_id', '=', 'items.article_id')
            ->join('units', 'units.unit_id', '=', 'items.unit_id')
            ->join('references', 'references.reference_id', '=', 'deliveries.reference_id')
            ->join('item_logs', 'item_logs.reference_id', '=', 'references.reference_id')
            ->join('suppliers', 'suppliers.supplier_id', '=', 'deliveries.supplier_id')
            ->selectRaw('deliveries.delivery_date,articles.article,
                    items.description,
                    items.stock_number,
                    references.reference,
                    units.unit,
                    deliveries.stock,
                    references.price,
                    (deliveries.stock * references.price) as Cost,
                    suppliers.supplier')
            ->whereBetween('deliveries.delivery_date', [$this->dateFrom, $this->dateTo])
            ->orderBy('deliveries.delivery_date', 'DESC')
            ->groupBy('deliveries.delivery_id')
            ->get();

            return $deliveries;

        } else {
            $deliveries = DB::table('deliveries')
            ->join('items', 'items.item_id', '=', 'deliveries.item_id')
            ->join('articles', 'articles.article_id', '=', 'items.article_id')
            ->join('units', 'units.unit_id', '=', 'items.unit_id')
            ->join('references', 'references.reference_id', '=', 'deliveries.reference_id')
            ->join('item_logs', 'item_logs.reference_id', '=', 'references.reference_id')
            ->join('suppliers', 'suppliers.supplier_id', '=', 'deliveries.supplier_id')
            ->selectRaw('deliveries.delivery_date,articles.article,
                    items.description,
                    items.stock_number,
                    references.reference,
                    units.unit,
                    deliveries.stock,
                    references.price,
                    (deliveries.stock * references.price) as Cost,
                    suppliers.supplier')
            ->orderBy('deliveries.delivery_date', 'DESC')
            ->groupBy('deliveries.delivery_id')
            ->get();

            return $deliveries;
        }
        
    }

    public function headings(): array
    {
        return [
            'Delivery Date',
            'Article',
            'Description',
            'Stock Number',
            'Reference',
            'Unit',
            'Quantity',
            'Value',
            'Cost',
            'Supplier',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}