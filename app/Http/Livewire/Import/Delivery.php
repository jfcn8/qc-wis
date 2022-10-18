<?php

namespace App\Http\Livewire\Import;

use App\Imports\DeliveryImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Delivery extends Component
{
    use WithFileUploads;

    public $delivery;

    public function render()
    {
        return view('livewire.import.delivery')->layout('livewire.layouts.base');
    }

    protected $rules = [
        'delivery' => 'required|file|mimes:xls,xlsx',
    ];

    protected $messages = [
        'delivery.required' => 'The delivery excel file is required.',
    ];

    public function importDelivery(Request $request) {

        $file = $request->file('delivery')->store('imports');

        Excel::import(new DeliveryImport, $file);

        session()->flash('message', 'Import Successfully.');
        return Redirect('import/delivery');


        // $request->file('delivery')->store('public/imports');

        // $file->hashName();
        // $path = Storage::putFile('deliveries', $request->file('delivery'), $request->file('delivery')->hashName());
        
       

    }
}
