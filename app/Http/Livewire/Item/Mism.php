<?php

namespace App\Http\Livewire\Item;

use App\Models\ItemLog;
use App\Models\Reference;
use App\Models\Signatory;
use App\Models\Item;
use Livewire\Component;
use DateTime;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

class Mism extends Component
{
    public $selectedYear, $selectedMonth, $from, $to;

    public $generatedDate;

    public $date_from, $date_to;

    protected $rules = [
        'date_from' => 'required',
        'date_to' => 'required',
        // 'selectedYear' => 'required',
        // 'selectedMonth' => 'required',
    ];

    protected $messages = [
        'date_from.required' => 'Date from is required.',
        'date_to.required' => 'Date to is required.',
    ];

    
    public function render()
    {
        return view('livewire.item.mism')->layout('livewire.layouts.base');
    }

    public function generateMISM() {

        $this->validate();


        $date_from = date('Y-m-d',strtotime($this->date_from));
        $date_to = date('Y-m-d',strtotime($this->date_to));

        $generatedDate = date('M d-', strtotime($date_from)) . date('d, Y',strtotime($date_to));


        $iLog = new ItemLog();
            activity()
            ->performedOn($iLog)
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => ['Date' => $generatedDate]])
            ->log('Generate Report - MISM');


        // $this->from = $this->selectedYear . '-'. $this->selectedMonth .'-01';
        // $this->to = $this->selectedYear . '-'. $this->selectedMonth .'-'. date('t',strtotime($this->from));

        $itemLogs = ItemLog::whereBetween('date_request', [$this->date_from, $this->date_to])->count();

        if($itemLogs == 0) {
            session()->flash('message', 'No RIS/Delivery on selected date.');
        } else {
            session(['date_from' => $date_from]);
            session(['date_to' => $date_to]);

            

            $this->reset();
            redirect('/items/mism/generated');
        }
    }

    public function aasort (&$array, $key) {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
    }


    public function generated() {

        $certify = Signatory::where('mism_certified', 1)->first();
        if (is_null($certify)) {
            session()->flash('message', 'There is no assigned signatory for certifying MISM.');
            return redirect()->to('/items/mism/');
        }

        $noting = Signatory::where('mism_noting', 1)->first();
        if (is_null($noting)) {
            session()->flash('message', 'There is no assigned signatory for Noting MISM.');
            return redirect()->to('/items/mism/');
        }

        $approving = Signatory::where('mism_approved', 1)->first();
        if (is_null($approving)) {
            session()->flash('message', 'There is no assigned signatory for approving MISM.');
            return redirect()->to('/items/mism/');
        }

        $date = new DateTime(session()->get('date_from'));

        // $previousMonth = $date->modify('last month');
        // $previousMonth = $previousMonth->format('Y-m-d');
        $previousDay = $date->modify('-1 day');
        $previousDay = $previousDay->format('Y-m-d');

        // dd($previousDay);
        // dd($previousMonth);

        $items = Item::orderBy('articles.article')
                        ->join('articles', 'articles.article_id', 'items.article_id')
                        ->orderBy('items.description')
                        // ->where('item_id', 5)
                        ->get();

        $generatedDate = date('M d-', strtotime(session()->get('date_from'))) .date('d Y',strtotime(session()->get('date_to')));

        $mism  = array();
        $grand = array(
            "BeginningAmount"=> 0,
            "DeliveryQty"=> 0,
            "DeliveryAmount"=> 0,
            "SSMIQty"=> 0,
            "SSMIAmount"=> 0,
            "EndingQty"=> 0,
            "EndingAmount"=> 0,
        );

        foreach($items as $item) {

            $references = Reference::where('item_id', $item->item_id)->get();
            // ->where('reference_id', 82)

            foreach($references as $reference_) {

                $article = $reference_->Item->Article->article;
                $description = $item->description;
                $unit = $item->Unit->unit;
                $stock_number = $item->stock_number;
                $reference = $reference_->reference;
                $value = $reference_->price;
                $deliveryQty = 0;
                $SSMIQty = 0;
                $beginningBalance = 0;
                $gotPrevious = 0;

                $itemLogsPreviousMonth = ItemLog::selectRaw('*, sum(quantity) as sum')
                        ->groupBy('reference_id', 'action')
                        ->where('reference_id', $reference_->reference_id)
                        ->where('date_request', '<=', date("Y-m-d", strtotime($previousDay)))
                        ->orderby('date_request')
                        ->get();
                        // ->toSql();
                        // ->where('date_request', '<=', date("Y-m-t", strtotime($previousMonth)))
                // dd($itemLogsPreviousMonth);

                // dd($itemLogsPreviousMonth);
                        
                
                if ($itemLogsPreviousMonth->count() > 0) {
                    // use the previous ending balance for beginning balance.

                    foreach ($itemLogsPreviousMonth as $ilpm) {
                        if ($ilpm->action == 3 || $ilpm->action == 2) {
                            $beginningBalance += $ilpm->sum;
                        } else {
                            $beginningBalance -= $ilpm->sum;
                        }
                    }
                    $gotPrevious = 1;
                }
                
                $itemLogsForSelectedDate = ItemLog::whereBetween('date_request', [session()->get('date_from'), session()->get('date_to')])
                        ->selectRaw('*, sum(quantity) as sum')
                        ->groupBy('reference_id', 'action')
                        ->where('reference_id', $reference_->reference_id)
                        ->orderby('date_request')
                        ->get();

                
                if ($itemLogsForSelectedDate->count() > 0) {

                    // dd($itemLogsForSelectedDate);
                    // use the previous ending balance for beginning balance.
                    foreach ($itemLogsForSelectedDate as $ilfsd) {

                        if ($ilfsd->action == 2) {
                            $deliveryQty += $ilfsd->sum;
                        }

                        if ($ilfsd->action == 1) {
                            $SSMIQty += $ilfsd->sum;
                        }

                        if ($ilfsd->action == 3) {
                            $beginningBalance += $ilfsd->sum;
                        }

                        // if (date("Y-m-01", strtotime($previousMonth) == '2022-01-01')) {
                        //     if ($gotPrevious == 0) {
                        //         if ($ilfsd->action == 3 || $ilfsd->action == 2) {
                        //             $beginningBalance += $ilfsd->sum;
                        //         } else {
                        //             $beginningBalance -= $ilfsd->sum;
                        //         }
                        //     }
                        // }
                    }
                }


                $newdata =  array (
                    'article' => $article,
                    'description' => $description,
                    'reference' => $reference,
                    'stock_number' => $stock_number,
                    'unit' => $unit,
                    'value' => $value,
                    'beginningQty' => $beginningBalance,
                    'beginningAmount' => $beginningBalance * $value,
                    'deliveryQty' => $deliveryQty,
                    'deliveryAmount' => $deliveryQty * $value,
                    'SSMIQty' => $SSMIQty,
                    'SSMIAmount' => $SSMIQty * $value,
                    'EndingQty' => ($beginningBalance + $deliveryQty) - $SSMIQty,
                    'EndingAmount' => (($beginningBalance + $deliveryQty) - $SSMIQty) * $value
                );

                $grand['BeginningAmount'] += $beginningBalance  * $value;
                $grand['DeliveryAmount'] += $deliveryQty * $value;
                $grand['SSMIQty'] += $SSMIQty;
                $grand['SSMIAmount'] += $SSMIQty * $value;
                $grand['EndingAmount'] += (($beginningBalance + $deliveryQty) - $SSMIQty) * $value;
                
                array_push($mism, $newdata);
            }
        }

        // dd($mism);


        // foreach ($itemLogs as $item) {
        //     // $reference_ = Reference::where('reference_id', $item->reference_id)->first();
            
        //     // $article = $reference_->Item->Article->article;
        //     // $description = $reference_->Item->description;
        //     // $unit = $reference_->Item->Unit->unit;
        //     // $stock_number = $reference_->Item->stock_number;
        //     // $reference = $reference_->reference;
        //     // $value = $reference_->price;
        //     // $deliveryQty = 0;
        //     // $SSMIQty = 0;
        //     // $beginningBalance = 0;

        //     // if ($item->action == 2) {
        //     //     $deliveryQty = $item->sum;
        //     // }

        //     // if ($item->action == 1) {
        //     //     $SSMIQty = $item->sum;
        //     // }

        //     // if ($item->action == 3) {
        //     //     $beginningBalance = $item->sum;
        //     // }

        //     // $newdata =  array (
        //     //     'article' => $article,
        //     //     'description' => $description,
        //     //     'reference' => $reference,
        //     //     'stock_number' => $stock_number,
        //     //     'unit' => $unit,
        //     //     'value' => $value,
        //     //     'beginningQty' => $beginningBalance,
        //     //     'beginningAmount' => $beginningBalance * $value,
        //     //     'deliveryQty' => $deliveryQty,
        //     //     'deliveryAmount' => $deliveryQty * $value,
        //     //     'SSMIQty' => $SSMIQty,
        //     //     'SSMIAmount' => $SSMIQty * $value,
        //     //     'EndingQty' => ($beginningBalance + $deliveryQty) - $SSMIQty,
        //     //     'EndingAmount' => (($beginningBalance + $deliveryQty) - $SSMIQty) * $value
        //     // );

        //     // $grand['BeginningAmount'] += $beginningBalance  * $value;
        //     // $grand['DeliveryAmount'] += $deliveryQty * $value;
        //     // $grand['SSMIQty'] += $SSMIQty;
        //     // $grand['SSMIAmount'] += $SSMIQty * $value;
        //     // $grand['EndingAmount'] += (($beginningBalance + $deliveryQty) - $SSMIQty) * $value;
            
        //     // array_push($mism, $newdata);
        // }

        $this->aasort($mism, "article");

        
        foreach($mism as $item => $val) {

            if  ($val['beginningQty'] == 0 && $val['deliveryQty'] == 0 && $val['SSMIQty'] == 0 && $val['EndingQty'] == 0 ) {
                unset($mism[$item]);
            }
        }

        return view('livewire.item.mism.generated', [
            'generatedDate' => $generatedDate,
            'noting' => $noting,
            'certify' => $certify,
            'approving' => $approving,
            'mism' => $mism,
            'grand' => $grand,
        ]);
    }
}
