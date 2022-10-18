<?php

namespace App\Http\Livewire\Item;

use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Ris;
use App\Models\Signatory;
use Livewire\Component;
use Monolog\Handler\IFTTTHandler;

class SSMI extends Component
{

    public $date_from, $date_to;

    public $generatedDate;

    public $message;

    protected $rules = [
        'date_from' => 'required',
        'date_to' => 'required',
    ];

    protected $messages = [
        'date_from.required' => 'Date from is required.',
        'date_to.required' => 'Date to is required.',
    ];

    public function render()
    {
        // $this->message = 'Data is processing please wait.';
        return view('livewire.item.ssmi')->layout('livewire.layouts.base');
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


    public function generateSSMI() {

        $date_from = date('m d - ',strtotime($this->date_from));
        $date_to = date('d Y',strtotime($this->date_to));
        

        $this->generatedDate = $date_from . '' . $date_to;

        $generatedDate = date('M d-', strtotime(session()->get('date_from'))) .date('d, Y',strtotime(session()->get('date_to')));

        $this->validate();

        $iLog = new ItemLog();
        activity()
        ->performedOn($iLog)
        ->causedBy(auth()->user())
        ->withProperties(['attributes' => ['Date' => $generatedDate ]])
        ->log('Generate Report - SSMI');

        session()->flash('message', 'Data is processing please');


        $itemLogs = ItemLog::whereBetween('date_request', [$this->date_from, $this->date_to])->count();

        if($itemLogs == 0) {
            session()->flash('message', 'No RIS/Delivery on selected date.');
        } else {
            session(['date_from' => $this->date_from]);
            session(['date_to' => $this->date_to]);
            $this->reset();
            
            redirect('/items/ssmi/generated');
        }
    }

    public function generated() {


        

        $noting = Signatory::where('ssmi_noting', 1)->first();
        if (is_null($noting)) {
            session()->flash('message', 'There is no assigned signatory for Noting SSMI.');
            return redirect()->to('/items/ssmi/');
        }

        $certifying = Signatory::where('ssmi_certifying', 1)->first();
        if (is_null($certifying)) {
            session()->flash('message', 'There is no assigned signatory for Certifying SSMI.');
            return redirect()->to('/items/ssmi/');
        }

        $approving = Signatory::where('ssmi_approving', 1)->first();
        if (is_null($approving)) {
            session()->flash('message', 'There is no assigned signatory for Approving SSMI.');
            return redirect()->to('/items/ssmi/');
        }

        $generatedDate = date('M d-', strtotime(session()->get('date_from'))) .date('d, Y',strtotime(session()->get('date_to')));

        $ris = Ris::whereBetween('date_request', [session()->get('date_from'), session()->get('date_to')])
                    ->orderBy('date_request','asc')
                    ->orderBy('ris_id', 'ASC')
                    ->get();

        $officeArray = [];
        $officeCount = 0;

        $officeList = array();

        foreach ($ris as $ris_) {

            $office = array(
                'ris_no' => $ris_->ris_no,
                'office' => $ris_->office->office
            );

            array_push($officeList, $office);

            // $itemLog = ItemLog::where('ris_no', $ris_->ris_no)->orderBy('date_request', 'ASC')->get();

            // foreach ($itemLog as $itemLog_) {
            //     $itemReference = array(
            //         'reference_id' => $itemLog_->reference_id,
            //         'quantity' => $itemLog_->quantity,
            //     );
            //     $result[$ris_->ris_no][$ris_->office->office][] = $itemReference;
            // }

            // $officeCount++;
        }

        // dd($officeList);



        $ssmi  = array();
        $items = Item::orderBy('articles.article')
                        ->join('articles', 'articles.article_id', 'items.article_id')
                        ->join('references', 'references.item_id', 'items.item_id')
                        ->join('item_logs', 'item_logs.reference_id', 'references.reference_id')
                        ->join('units', 'units.unit_id', 'items.unit_id')
                        ->orderBy('articles.article', 'ASC')
                        ->orderBy('items.description', 'ASC')
                        ->whereBetween('item_logs.date_request', [session()->get('date_from'), session()->get('date_to')])
                        ->where('item_logs.action', 1)
                        ->select('items.item_id', 'items.description', 'items.stock_number', 'references.reference','units.unit', 'articles.article', 'references.price', 'references.reference_id')
                        ->groupBy('items.item_id', 'references.reference')
                        ->get();
                        // '2022-04-01' and '2022-04-07'
        
        $officeRIS = [];

        foreach ($items as $item) {

            foreach ($ris as $ris_) {

                $risItem = ItemLog::where('reference_id', $item->reference_id)
                        ->where('ris_no', $ris_->ris_no)
                        ->whereBetween('item_logs.date_request', [session()->get('date_from'), session()->get('date_to')])
                        ->first();

                $itemReference = array(
                    'office' => $ris_->office->office,
                    'quantity' => (is_null($risItem)) ? 0 : $risItem->quantity
                );

                $officeRIS[$item->reference_id][$ris_->ris_no][] = $itemReference;

            }
        }

        foreach($items as $item) {
            $newdata =  array (
                'reference_id' => $item->reference_id,
                'reference' => $item->reference,
                'stock_number' => $item->stock_number,
                'description' => $item->article . ', ' .$item->description,
                'unit' => $item->unit,
                'unitCost' => $item->price,
            );
            array_push($ssmi, $newdata);
        }

        return view('livewire.item.ssmi.generated', [
            'generatedDate' =>$generatedDate,
            'noting' => $noting,
            'ssmi' => $ssmi,
            'certifying' => $certifying,
            'approving' => $approving,
            'offices' => $officeList,
            'results' => $officeRIS,
        ]);
    }
}
