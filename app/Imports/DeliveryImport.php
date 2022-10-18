<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\Delivery;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Reference;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Support\Facades\DB;

class DeliveryImport implements ToCollection, WithHeadingRow, WithCustomValueBinder
{
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {

            // Article
            // Description
            // Reference
            // Stock_Number
            // Unit
            // Value
            // InitialQuantity
            // Delivery_Date
            // Quantity


            $article = Article::where('article', trim($row['article']))->first();
            if (is_null($article)) {
                $article = Article::create([
                    'article' => trim($row['article']),
                    'classification_id' => 4
                ]);
            }

            $unit = Unit::where('unit', trim($row['unit']))->first();
            if (is_null($unit)) {
                $unit = Unit::create([
                    'unit' => trim($row['unit']),
                    'mnemonic' => trim($row['unit']),
                ]);
            }

            // dd($article->article_id);
            // dd($unit->unit_id);
            // dd(trim($row['stock_number']));
            // dd(trim($row['description']));


            $item = Item::where('description', trim($row['description']))
                    ->where('stock_number', trim($row['stock_number']))
                    ->where('article_id', $article->article_id)
                    ->where('unit_id', $unit->unit_id)
                    ->first();

                    if (is_null($item)) {
                        $item = Item::create([
                            'description' => trim($row['description']),
                            'stock_number' => trim($row['stock_number']),
                            'article_id' => $article->article_id,
                            'unit_id' =>  $unit->unit_id
                        ]);
                        $item_id = $item->item_id;
                        $itemExisting = false;
                    } else {
                        $item_id = $item->item_id;
                        $itemExisting = true;
                    }

                    $reference_id = 0;
                    $reference = Reference::where('reference',  trim($row['reference']))
                                    ->where('item_id', $item_id)
                                    ->where('price', trim($row['value']))->first();

                    if (is_null($reference)) {
                        $reference = Reference::create([
                            'reference' => trim($row['reference']),
                            'stock' => trim((empty($row['quantity']) ? (float)$row['initialquantity'] : (float)$row['quantity'] )),
                            'price' => trim($row['value']),
                            'item_id' => $item_id,
                        ]);

                        $reference_id = $reference->reference_id;
                    } else {

                        $reference_id = $reference->reference_id;
                        $reference->reference_id = $reference->reference_id;
                        $reference->stock = $reference->stock + (float)$row['quantity'] + (float)$row['initialquantity'];
                        $reference->save();
                    }

                    // no initial.
                    if (!empty($row['initialquantity'])) {
                        ItemLog::create([
                            'date_request' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['delivery_date'])->format('Y-m-d'),
                            'reference_id' => $reference_id,
                            'action' => 3,
                            'quantity' => trim($row['initialquantity']),
                            'user_id' => Auth()->user()->id,
                        ]);
                    }

                    if (!empty($row['quantity'])) {
                        $delivery = Delivery::create([
                            'delivery_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['delivery_date'])->format('Y-m-d'),
                            'stock' => trim($row['quantity']),
                            'item_id' => $item_id,
                            'reference_id' => $reference_id,
                            'supplier_id' => 1,
                            'user_id' => Auth()->user()->id,
                        ]);

                        ItemLog::create([
                            'date_request' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['delivery_date'])->format('Y-m-d'),
                            'reference_id' => $reference_id,
                            'action' => 2,
                            'quantity' => trim($row['quantity']),
                            'user_id' => Auth()->user()->id,
                        ]);
                    }


                    



            // Delivery::create([
            //     'delivery_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['delivery_date'])->format('Y-m-d'),
            //     'stock' => $row['quantity'],
            //     'supplier_id' => 1,                     // PSDM Default
            //     'reference_id' => 0,                    // reference_id
            //     'item_id' => 0,                         // user id
            //     'user_id' => Auth()->user()->id,
            // ]);
        }

        
    }

    public function bindValue(Cell $cell, $value) {
        if(preg_match('/^E*\d*$/', $cell->getCoordinate())){
                 $cell->setValueExplicit(Date::excelToDateTimeObject($value)->format('Y-m-d'), DataType::TYPE_STRING);
         }
         else{
             $cell->setValueExplicit($value, DataType::TYPE_STRING);
         }
 
         return true;
     }


    public function columnFormats(): array {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD    
        ];
    }
}
