<?php

namespace App\Http\Livewire\Supplier;


use Livewire\Component;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierExport;

class Index extends Component
{
    public $searchKey;

    public $supplier_id, $supplier, $other_information;

    public $supplier_, $other_information_;

    protected $rules = [
        'supplier' => 'required|min:3',
    ];


    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Suppliers', $access)) {
            session()->flash('message', "Sorry, you don't have access to Supplier page.");
            $this->redirect('/profile');
        }


        return view('livewire.supplier.index', [
            'suppliers' => Supplier::when($this->searchKey, function($query, $searchKey) {
                return $query->where('supplier', 'LIKE', "%$searchKey%");
            })->paginate(10),
            'permissions' => $permissions
            ])->layout('livewire.layouts.base');
    }

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function saveSupplier() {

        $this->validate();
        Supplier::create([
            'supplier' => trim($this->supplier),
            'other_information' => trim($this->other_information),
        ]);
        session()->flash('message', $this->supplier . ' has been added successfully.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function getSupplier($id) {
        $supplier = Supplier::where('supplier_id', $id)->first();
        $this->supplier = $supplier->supplier;
        $this->supplier_id = $supplier->supplier_id;
        $this->other_information = $supplier->other_information;
        $this->dispatchBrowserEvent('show-edit-supplier-modal');
    }

    public function deleteConfirmation($id) {
        $this->supplier_id = $id;

        $this->dispatchBrowserEvent('show-delete-supplier-modal');
    }

    public function deleteSupplier() {
        $supplier = Supplier::where('supplier_id', $this->supplier_id)->first();
        $this->supplier = $supplier->supplier;
        $supplier->delete();

        session()->flash('message', $this->supplier . ' has been deleted successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewSupplierDetails($id) {

        $supplier = Supplier::where('supplier_id', $id)->first();
        
        $this->supplier_ = $supplier->supplier;
        $this->other_information_ = $supplier->other_information;
        $this->dispatchBrowserEvent('show-supplier-modal');

    }

    public function updateSupplier() {

        $this->validate();
        $supplier = Supplier::where('supplier_id', $this->supplier_id)->first();

        $supplier->supplier_id = $this->supplier_id;
        $supplier->supplier = trim($this->supplier);
        $supplier->other_information = trim($this->other_information);
        $supplier->save();
        session()->flash('message', $this->supplier . ' has been updated successfully.');
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function export() {
        return Excel::download(new SupplierExport, 'suppliers.xlsx');
    }
}
