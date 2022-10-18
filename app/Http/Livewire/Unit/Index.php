<?php

namespace App\Http\Livewire\Unit;

use App\Models\Unit;
use App\Models\Activity;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\UnitExport;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    public $unit, $unit_id;
    public $unit_name, $mnemonic, $mnemonic_;

    use WithPagination;
 
    protected $paginationTheme = 'bootstrap';

    public $searchKey;
    
    public function render()
    {
        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Units', $access)) {
            session()->flash('message', "Sorry, you don't have access to Unit page.");
            $this->redirect('/profile');
        }
        
        return view('livewire.unit.index', [
            'units' => Unit::when($this->searchKey, function($query, $searchKey){
                return $query->where('unit', 'LIKE', "%$searchKey%")->orderBy('unit');
            })->orderBy('unit')->paginate(10),
            'permissions' => $permissions
            ])->layout('livewire.layouts.base');
    }

    protected $rules = [
        'unit' => 'required|min:3',
    ];


    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function saveUnit() {

        $this->validate();
        $unit = Unit::create([
            'unit' => trim($this->unit),
            'mnemonic' => trim($this->mnemonic),
        ]);

        session()->flash('message', $this->unit . ' has been added successfully.');

        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function getUnit($id) {
        $unit = Unit::where('unit_id', $id)->first();
        $this->unit = $unit->unit;
        $this->unit_id = $unit->unit_id;
        $this->mnemonic = $unit->mnemonic;
        $this->dispatchBrowserEvent('show-edit-unit-modal');
    }

    public function deleteConfirmation($id) {
        $this->unit_id = $id;

        $this->dispatchBrowserEvent('show-delete-unit-modal');
    }

    public function deleteUnit() {
        $unit = Unit::where('unit_id', $this->unit_id)->first();
        $this->unit = $unit->unit;
        $unit->delete();

        session()->flash('message', $this->unit . ' has been deleted successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewUnitDetails($id) {

        $unit_ = Unit::where('unit_id', $id)->first();
        
        $this->unit_name = $unit_->unit;
        $this->mnemonic = $unit_->mnemonic;

        $this->dispatchBrowserEvent('show-unit-modal');

    }

    public function updateUnit() {

        $this->validate();
        $unit = Unit::where('unit_id', $this->unit_id)->first();
        $unit_name_ = $unit->unit;

        $unit->unit_id = $this->unit_id;
        $unit->unit = trim($this->unit);
        $unit->mnemonic = trim($this->mnemonic);
        $unit->save();
        
        session()->flash('message', $this->unit . ' has been updated successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function export() {
        return Excel::download(new UnitExport, 'units.xlsx');
    }
}
