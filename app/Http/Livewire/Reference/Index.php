<?php

namespace App\Http\Livewire\Reference;

use App\Models\Item;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Reference;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $item_id, $price, $stock, $reference, $reference_id;
    public $reference_, $stock_, $price_;

    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Items', $access)) {
            session()->flash('message', "Sorry, you don't have access to Item Reference page.");
            $this->redirect('/profile');
        }

        $item = Item::where('item_id', $this->item_id)->first();

        $references = Reference::where('item_id',$this->item_id)->get();

        return view('livewire.reference.index', ['references' => $references, 'item' => $item])->layout('livewire.layouts.base');
    }

    protected $rules = [
        'reference' => 'required|min:3',
        'stock' => 'required|numeric',
        'price' => 'required|numeric',
    ];

    public function mount($id)
    {
        $ref = DB::table('references')->where('item_id', $id)->first();

        if(!empty($ref)) {
            $this->item_id = $ref->item_id; 
        } else {
            session()->flash('message', 'Item not found!');
            Redirect('item');
        }
    }

    public function getReference($id) {
        $reference = Reference::where('reference_id', $id)->first();
        $this->reference = $reference->reference;
        $this->reference_id = $reference->reference_id;
        $this->stock = $reference->stock;
        $this->price = $reference->price;
        $this->dispatchBrowserEvent('show-edit-reference-modal');
    }

    public function updateReference() {

        $this->validate();
        $reference = Reference::where('reference_id', $this->reference_id)->first();

        $reference->reference_id = $this->reference_id;
        $reference->reference = trim($this->reference);

        // if ($this->stock < $reference->stock) {
        //     // 4, 5
        // }

        // $reference->stock = trim($this->stock);
        $reference->price = trim($this->price);
        $reference->save();

        

        session()->flash('message', trim($this->reference) . ' has been updated successfully.');
        
        $this->resetExcept('item_id');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteConfirmation($id) {
        $this->reference_id = $id;
        $this->dispatchBrowserEvent('show-delete-reference-modal');
    }

    public function deleteReference() {
        $reference = Reference::where('reference_id', $this->reference_id)->first();
        $this->reference = $reference->reference;
        $reference->delete();

        session()->flash('message', $this->reference . ' has been deleted successfully.');

        $this->resetExcept('item_id');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel() {
        $this->resetExcept('item_id');
        $this->dispatchBrowserEvent('close-modal');
    }
}
