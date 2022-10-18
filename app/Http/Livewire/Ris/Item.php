<?php

namespace App\Http\Livewire\Ris;

use App\Models\Item as ModelsItem;
use Livewire\Component;
use App\Models\Reference;
use App\Models\Ris;
use App\Models\ItemLog;
use App\Models\Office;
use App\Models\TempRis;
use Illuminate\Support\Facades\DB;

class Item extends Component
{
    public $ris_id, $ris_no, $item_log_id;
    public $deleteMessage;
    public $office, $purpose, $date_request;
    public $item, $quantity;

    protected $rules = [
        'office' => 'required',
        'date_request' => 'required',
        'purpose' => 'required|min:1',
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        'date_request.required' => 'Date Request is required.',
    ];


    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('RIS', $access)) {
            session()->flash('message', "Sorry, you don't have access to RIS page.");
            $this->redirect('/profile');
        }

        $ris = Ris::where('ris_id', $this->ris_id)->first();
        
        $itemLogs = ItemLog::where('ris_no',$this->ris_no)
                            ->join('references','references.reference_id', '=', 'item_logs.reference_id')
                            ->join('items','items.item_id', '=', 'references.item_id')
                            ->join('articles','articles.article_id', '=', 'items.article_id')
                            ->orderBy('articles.article')
                            ->get();

        $offices = Office::all();

        $items = ModelsItem::orderBy('articles.article')
                    ->join('articles','articles.article_id', '=', 'items.article_id')
                    ->join('references','references.item_id', '=', 'items.item_id')
                    ->join('units','units.unit_id', '=', 'items.unit_id')
                    ->selectRaw('items.item_id,items.description,articles.article,units.unit,SUM(references.stock) as stock')
                    ->where('stock', '>', 0)
                    ->orderBy('articles.article', 'ASC')
                    ->orderBy('items.description', 'ASC')
                    ->groupBy('items.item_id')
                    ->get();

        return view('livewire.ris.item', [
            'itemLogs' => $itemLogs,
            'items' => $items,
            'ris' => $ris,
            'offices' => $offices,
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');
    }

    public function print($id)
    {
        $ris = Ris::where('ris_id', $id)->first();

        $itemLogs = ItemLog::where('ris_no',$ris->ris_no)
                            ->join('references', 'references.reference_id', 'item_logs.reference_id')
                            ->join('items', 'items.item_id', 'references.item_id')
                            ->join('articles', 'articles.article_id', 'items.article_id')
                            ->join('units', 'units.unit_id', 'items.unit_id')
                            ->select('references.reference','articles.article','items.description','units.unit','references.price')
                            ->selectRaw('SUM(item_logs.quantity) as quantity')
                            ->groupBy('references.reference_id')
                            ->orderBy('articles.article','ASC')
                            ->orderBy('items.description','ASC')
                            ->get();

        // dd($itemLogs);

        return view('livewire.ris.print', [
            'itemLogs' => $itemLogs,
            'ris' => $ris,
        ])->layout('livewire.layouts.base');
    }
    

    public function mount($id)
    {
        $ris = Ris::where('ris_id', $id)->first();
        if(!empty($ris)) {
            $this->ris_id = $ris->ris_id; 
            $this->ris_no = $ris->ris_no;
            $this->office = $ris->office_id;
            $this->purpose = $ris->purpose;
            $this->date_request = $ris->date_request;

            $tempris = TempRis::where('user_id', Auth()->user()->id);
            $tempris->delete();

            // $notification = Auth()->user()->unreadNotifications;
            $notification = Auth()->user()->unreadNotifications()
                                            ->where('model_id', $id)
                                            ->whereNull('read_at')
                                            ->first();
            if (!is_null($notification)) {
                $notification->markAsRead();
            }
            

        } else {
            session()->flash('message', 'RIS Request not found. It could be the request was deleted.');
            Redirect('ris');
        }
    }

    public function updateRIS() {
        $this->validate();

        $ris = Ris::where('ris_id', $this->ris_id)->first();
        $ris->date_request = $this->date_request;
        $ris->purpose = $this->purpose;
        $ris->office_id = $this->office;
        $this->ris_no = $ris->ris_no;
        $ris->save();

        ItemLog::where('ris_no', $this->ris_no)->update(['date_request' => $this->date_request]);
        session()->flash('message', 'Update Successful.');
    }

    public function cancel() {
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteConfirmation($id) {
        $this->item_log_id = $id;
        
        $itemLog = ItemLog::where('ris_no', $this->ris_no)->count();
        if ($itemLog <= 1) {
            $this->deleteMessage = 'There is no more item in this RIS if you delete this. Deleting this Item will also delete the Entire RIS.';
        }
        $this->dispatchBrowserEvent('show-delete-item-modal');
    }
    public function deleteItem() {

        $itemRis = ItemLog::where('item_log_id', $this->item_log_id)->first();
        if ($itemRis->first()) {
            $risDeleted = 0;
            $messageDelete = '';
            $itemRis->item_log_id = $this->item_log_id;
            $quantityRequested = $itemRis->quantity;
            $ref = Reference::where('reference_id', $itemRis->reference_id)->first();
            $risNo = $itemRis->ris_no;

            $itemRis->delete();

            $itemRis_ = ItemLog::where('ris_no', $risNo);
            if ($itemRis_->count() <= 1) {
                $risDeleted = 1;
                ItemLog::where('ris_no', $risNo)->delete();
                Ris::where('ris_no', $risNo)->delete();
            }
           
            $ref->stock = $ref->stock + $quantityRequested;
            $ref->save();

            session()->flash(($risDeleted == 1 ? 'message' : 'item'), 'Item has been removed successfully. Quantity returned to its reference. ' . ($risDeleted == 1 ? "RIS Also deleted." : ""));
            if ($risDeleted == 1) {
                return redirect()->to('ris');
            }
        } else {
            session()->flash('item', 'Item not found');
        }
        $this->dispatchBrowserEvent('close-modal');
    }

    public function getItem($id) {
        $itemLog_ = ItemLog::where('item_log_id', $id)->first();
        $this->item_log_id = $itemLog_->item_log_id;
        $this->quantity = $itemLog_->quantity;
        $this->ris_no = $itemLog_->ris_no;
        $this->dispatchBrowserEvent('show-edit-item-modal');
    }

    public function addItem () {

        $validatedData = $this->validate([
            'quantity' => 'required|numeric|min:1',
            'item' => 'required',
        ]);
        

        $reference = DB::table('references')
                                ->where('item_id', $this->item)
                                ->where('stock', '>', 0)
                                ->orderBy('reference_id', 'ASC')
                                ->get();

        $RIS = Ris::where('ris_id', $this->ris_id)->first();
        if(!empty($RIS)) {

            $remaining = $this->quantity;
            $requestQuantity = $this->quantity;

            foreach ($reference as $ref) {
                $give = 0;
                $nowStock = 0;
                if ($ref->stock >= $remaining) {
                    $give += $remaining;
                    $remaining -= $give;
                    $nowStock = $ref->stock - $give;
            
                } else {
                    $remaining -= $ref->stock;
                    $give += $ref->stock;
                    $nowStock = $give - $ref->stock;
                }
            
                // $st .= 'Remaining: ' . $remaining . ' | Give : ' . $give . PHP_EOL . 'Current Stock : ' . $ref->stock . ' | Now Stock :' . $nowStock . PHP_EOL. PHP_EOL;
            
                ItemLog::create([
                    'date_request' => $RIS->date_request,
                    'reference_id' => $ref->reference_id,
                    'action' => 1,
                    'ris_no' => $RIS->ris_no,
                    'quantity' => $give,
                    'user_id' => Auth()->user()->id,
                ]);
            
                $reference = Reference::where('reference_id', $ref->reference_id)->first();
                $reference->reference_id = $ref->reference_id;
                $reference->stock = $nowStock;
                $reference->save();
            
                if ($remaining === 0) {
                    break 1;
                }
            }
        }
        
        session()->flash('item', 'Item successfully added to RIS.');
    }
}
