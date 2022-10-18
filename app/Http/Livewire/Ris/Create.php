<?php

namespace App\Http\Livewire\Ris;

use Livewire\Component;
use App\Models\Article;
use App\Models\Unit;
use App\Models\Item;
use App\Models\Office;
use App\Models\Reference;
use App\Models\ItemLog;
use App\Models\User;
use App\Models\TempRis;
use App\Models\Ris;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RisNotification;

class Create extends Component
{
    public $item, $quantity, $date_request, $purpose, $office;
    public $temp_ris_id, $user_id;
    public $newOffice;

    public $uniqid;


    protected $rules = [
        'item' => 'required',
        'office' => 'required',
        'date_request' => 'required',
        'purpose' => 'required|min:1',
        'quantity' => 'required|numeric|min:1',
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        'date_request.required' => 'Date Request is required.',
    ];

    public function render() {


        // $notification = [
        //     'model_id' =>  999,
        //     'date_request' => '2022-03-01',
        //     'purpose' => trim('Office supplies'),
        //     'office' => 'GSO',
        //     'action' => 'New RIS Request',
        // ];

        // $users = User::where('office_id', 1)->where('office_id', 1)->get();

        // Notification::send($users, new RisNotification($notification));

        // dd($users);

        $this->uniqid = Str::random(20);
        $session_id = session()->getId();
        // dd($session_id);

        $articles = Article::all();
        $units = Unit::all();

        // $items = Item::orderBy('articles.article')
        //             ->join('articles','articles.article_id', '=', 'items.article_id')
        //             ->orderBy('articles.article', 'ASC')
        //             ->orderBy('items.description', 'ASC')
        //             ->get();

                    $items = Item::orderBy('articles.article')
                    ->join('articles','articles.article_id', '=', 'items.article_id')
                    ->join('references','references.item_id', '=', 'items.item_id')
                    ->join('units','units.unit_id', '=', 'items.unit_id')
                    ->selectRaw('items.item_id,items.description,articles.article,units.unit,items.stock_number,SUM(references.stock) as stock')
                    ->where('stock', '>', 0)
                    ->orderBy('articles.article', 'ASC')
                    ->orderBy('items.description', 'ASC')
                    ->groupBy('items.item_id')
                    ->get();

        $offices = Office::orderBy('office')->get();
        $riss = TempRis::where('user_id', Auth()->user()->id)->orderBy('temp_ris_id', 'desc')->get();

        if ($riss->count()) {
            $this->office = $riss[0]->office_id;
            $this->purpose = $riss[0]->purpose;
            $this->date_request = $riss[0]->date_request;
        }
        
        return view('livewire.ris.create', [
            'articles' => $articles,
            'units' => $units,
            'offices' => $offices,
            'items' => $items,
            'riss' => $riss,
        ])->layout('livewire.layouts.base');
    }

    public function addItem() {
        $this->validate();
        $session_id = session()->getId();

        $reference = DB::table('references')
                                    ->join('items', 'items.item_id', '=', 'references.item_id')
                                    ->select('items.description')
                                    ->selectRaw('SUM(references.stock) as stock')
                                    ->where('references.item_id', $this->item)
                                    ->where('references.stock', '>', 0)
                                    ->first();

        $description = $reference->description;
        $currentStock = $reference->stock;

        if ($reference->stock >= $this->quantity) {
            TempRis::create([
                'date_request' => $this->date_request,
                'purpose' => $this->purpose,
                'item_id' => $this->item,
                'action' => 1,
                'quantity' => $this->quantity,
                'office_id' => $this->office,
                'user_id' => Auth()->user()->id,
            ]);
            $this->reset(['item', 'quantity']);
            session()->flash('message', 'Item has been added.');
        } else {
            session()->flash('danger', 'The request quantity is greater than the current stock of ' . $description . '. The current stock is ' . $currentStock);
        }

        
          
    }

    public function saveOffice() {

        $this->validate([
            'newOffice' => 'required|min:2',
        ]);
    
        $office = Office::create([
            'office' => trim($this->newOffice),
        ]);

        session()->flash('message', $this->newOffice . ' has been added successfully.');
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->to('ris/create');
    }
    public function deleteConfirmation($id) {
        $this->temp_ris_id = $id;
        $this->dispatchBrowserEvent('show-delete-tempris-modal');
    }
    public function deleteTempItem() {
        $tempris = TempRis::where('temp_ris_id', $this->temp_ris_id)->first();
        $tempris->delete();
        session()->flash('message', 'Item has been removed successfully.');
        $this->dispatchBrowserEvent('close-modal');
    }
    public function resetConfirmation($id) {
        $this->user_id = $id;
        $this->dispatchBrowserEvent('show-reset-tempris-modal');
    }
    public function resetRIS() {
        $tempris = TempRis::where('user_id', $this->user_id);
        $tempris->delete();
        session()->flash('message', 'RIS reset successfully.');
        $this->dispatchBrowserEvent('close-modal');
    }
    public function getStock($id) {
        $temp = TempRis::where('temp_ris_id', $id)->first();
        $this->quantity = $temp->quantity;
        $this->temp_ris_id = $temp->temp_ris_id;

        $this->dispatchBrowserEvent('show-edit-temp-modal');
    }
    public function saveTempStock() {

        $validatedData = $this->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        $temp = TempRis::where('temp_ris_id', $this->temp_ris_id)->first();
        $item_id = $temp->item_id;
        $temp->temp_ris_id = $this->temp_ris_id;

        $reference = DB::table('references')
                                    ->join('items', 'items.item_id', '=', 'references.item_id')
                                    ->select('items.description')
                                    ->selectRaw('SUM(references.stock) as stock')
                                    ->where('references.item_id', $item_id)
                                    ->where('references.stock', '>', 0)
                                    ->first();

        $description = $reference->description;
        $currentStock = $reference->stock;


        if ($reference->stock >= $this->quantity) {

            $temp->quantity = $this->quantity;
            $temp->save();
            $this->reset(['item', 'quantity']);
            session()->flash('message', 'Stock has been updated successfully.');
            
        } else {
            $this->reset(['item', 'quantity']);
            session()->flash('danger', 'The request quantity is greater than the current stock of ' . $description . '. The current stock is ' . $currentStock);
        }


        $this->reset(['item', 'quantity']);
        $this->dispatchBrowserEvent('close-modal');
    }
    public function saveConfirmation($id) {
        $this->user_id = $id;
        
        $temp_ris = TempRis::where('user_id', $this->user_id)->get();
        
        if ($temp_ris->count() > 0) {
            $this->dispatchBrowserEvent('show-save-modal');       
        } else {
            session()->flash('message', 'No item to add in RIS.');
        }
    }

    public function cancel() {
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveRIS() {
        // dd('saved');

        $st = '';
        $tempRisCount = 0;
        $itemGive = 0;
        $temp_ris = DB::table('temp_ris')
                            ->select('item_id')
                            ->selectRaw('SUM(quantity) as quantity')
                            ->where('user_id', $this->user_id)
                            ->groupBy('item_id')
                            ->get();

        foreach ($temp_ris as $ris) {
            $tempRisCount++;
            $reference = DB::table('references')
                                ->where('item_id', $ris->item_id)
                                ->where('stock', '>', 0)
                                ->orderBy('reference_id', 'ASC')
                                ->get();

            $remaining = $ris->quantity;
            $requestQuantity = $ris->quantity;
            
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
                    'date_request' => $this->date_request,
                    'reference_id' => $ref->reference_id,
                    'action' => 1,
                    'ris_no' => $this->uniqid,
                    'quantity' => $give,
                    'user_id' => Auth()->user()->id,
                ]);

                $reference = Reference::where('reference_id', $ref->reference_id)->first();
                $reference->reference_id = $ref->reference_id;
                $reference->stock = $nowStock;
                $reference->save();


                if ($remaining === 0) {
                    $itemGive++;
                    break 1;
                }
            }
        }

        if ($itemGive === $tempRisCount) {
            // dd($st);

        
            $ris = Ris::create([
                'date_request' => $this->date_request,
                'ris_no' => $this->uniqid,
                'purpose' => trim($this->purpose),
                'office_id' => $this->office,
                'user_id' => Auth()->user()->id,
                'gso' => 0,
                'budget' => 0,
            ]);

            $o = Office::where('office_id', $this->office)->first();

            $notification = [
                'model_id' =>  $ris->ris_id,
                'date_request' => $this->date_request,
                'purpose' => trim($this->purpose),
                'office' => $o->office,
                'action' => 'New RIS Request',
            ];

            if ($this->office == 1) {
                $users = User::where('office_id', $this->office)->where('office_id', 2)->get();
            } elseif($this->office == 2) {
                $users = User::where('office_id', $this->office)->where('office_id', 1)->get();
            } else {
                $users = User::where('office_id', $this->office)
                             ->orWhere('office_id', 1)
                             ->orWhere('office_id', 2)->get();
            }

        
            Notification::send($users, new RisNotification($notification));

            $tempris = TempRis::where('user_id', Auth()->user()->id);
            $tempris->delete();
            $this->reset();
            session()->flash('message', 'RIS Created Successfully!');
            return redirect()->to('ris');
        }
    }
}