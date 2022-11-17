<?php

namespace App\Http\Livewire\Item;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Reference;
use App\Models\Ris;
use DateTime;

use Livewire\Component;

class StockCard extends Component
{
    public $date_from, $date_to, $items = [], $proceed = 0;
    

    public function render()
    {
        return view('livewire.item.stock-card')->layout('livewire.layouts.base');
    }

    public function generateItems() {

        $this->items = [];


        $this->items = DB::select("SELECT a.article,i.description,u.unit,i.item_id FROM `item_logs` il
            inner join `references` r on r.reference_id=il.reference_id
            inner join items i on i.item_id=r.item_id
            inner join articles a on a.article_id = i.article_id
            inner join units u on u.unit_id=i.unit_id 
            where il.date_request between '".$this->date_from."' and '".$this->date_to."'
            group by i.item_id
            order by a.article;");

        session(['date_from' => $this->date_from]);
        session(['date_to' => $this->date_to]);

        $this->proceed = 1;

    }

    public function item($id) {

        $stockCard  = array();

        
        $generatedDate = date('M d-', strtotime(session()->get('date_from'))) .date('t Y',strtotime(session()->get('date_from')));
        $date = new DateTime(session()->get('date_from'));
        $previousDay = $date->modify('-1 day');
        $previousDay = $previousDay->format('Y-m-d');


        $itemSelected = Item::where('item_id', $id)->first();
        $itemLogs = ItemLog::whereBetween('item_logs.date_request', [session()->get('date_from'), session()->get('date_to')])
                        ->selectRaw('item_logs.date_request,item_logs.action,item_logs.quantity,references.reference,item_logs.ris_no')
                        ->orderBy('item_logs.date_request')
                        ->join('references', 'item_logs.reference_id', 'references.reference_id')
                            ->where('references.item_id', $id)
                        ->get();
                        // dd($itemLogs);

        $previousitemLogs = ItemLog::where('item_logs.date_request', '<=', $previousDay)
                        ->selectRaw('item_logs.date_request,item_logs.action,item_logs.quantity,references.reference,item_logs.ris_no')
                        ->orderBy('item_logs.date_request')
                        ->join('references', 'item_logs.reference_id', 'references.reference_id')
                            ->where('references.item_id', $id)
                        ->get();
        $previousBalance = 0;

        if($previousitemLogs->count() > 0) {
            foreach ($previousitemLogs as $prev) {
                if ($prev->action == 3 || $prev->action == 2) {
                    $previousBalance += $prev->quantity;
                } else {
                    $previousBalance -= $prev->quantity;
                }
            }
        }

        $grand = array(
            "BalanceQty"=> ($previousBalance > 0 ) ? $previousBalance : 0
        );


        $row = -1;
        foreach($itemLogs as $item) {
            $row++;

            $ReceiptQty = ($row > 0) ? $stockCard[$row-1]['BalanceQty'] : 0; 

            if ($row == 0) {
                $ReceiptQty += $previousBalance;
            }

            if ($item->action == 1) {

                $grand['BalanceQty'] -= $item->quantity;
                $ris = Ris::where('ris_no', $item->ris_no)->first();

                $newdata =  array (
                    'Date' =>  date('M. d, Y', strtotime($item->date_request)),
                    'Reference' => $item->reference,
                    'ReceiptQty' => $ReceiptQty ,
                    'IssuanceQty' => $item->quantity,
                    'Office' => $ris->Office->office,
                    'BalanceQty' => $grand['BalanceQty'],
                );

            } else {
                $grand['BalanceQty'] += $item->quantity;

                $newdata =  array (
                    'Date' => date('M. d, Y', strtotime($item->date_request)),
                    'Reference' => $item->reference,
                    'ReceiptQty' => $ReceiptQty,
                    'IssuanceQty' => ($item->action == 2) ? $item->quantity : 0,
                    'Office' => ($item->action == 2) ? '*DELIVERY*' : '',
                    'BalanceQty' => $grand['BalanceQty'],
                );
            }
            array_push($stockCard, $newdata);
        }

        $stockCardDate = date('M. d - ', strtotime(session()->get('date_from'))) . date(' M. d, Y', strtotime(session()->get('date_to')));
                    
        return view('livewire.item.stock-card.index', [
            'fileName' => $this->RemoveSpecialChar("StockCard_" .$itemSelected->Article->article .'_'.$itemSelected->description.'_'.session()->get('date_from') . '-' . session()->get('date_to')) .'.xls',
            'items' => $itemSelected,
            'stockCard' => $stockCard,
            'stockCardDate' => $stockCardDate,
        ]);

    }


    function RemoveSpecialChar($str)
    {
        $str = str_replace(array('[\', \']'), '', $str);
        $str = preg_replace('/\[.*\]/U', '', $str);
        $str = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $str);
        $str = htmlentities($str, ENT_COMPAT, 'utf-8');
        $str = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $str );
        $str = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $str);
        return strtolower(trim($str, '-'));
    }


}
