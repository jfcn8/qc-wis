<?php

namespace App\Http\Livewire\Office;

use App\Exports\OfficeExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Activity;
use App\Models\Office;
use App\Notifications\RisNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Notification;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $office, $office_id;
    public $office_name;
    public $searchKey;

    protected $rules = [
        'office' => 'required|min:2',
    ];

    public function render()
    {
        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Offices', $access)) {
            session()->flash('message', "Sorry, you don't have access to Office page.");
            $this->redirect('/profile');
        }
        
        return view('livewire.office.index', [
            'offices' => Office::when($this->searchKey, function($query, $searchKey) {
                return $query->where('office', 'LIKE', "%$searchKey%")->orderBy('office');
            })->orderBy('office')->paginate(10),
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');
    }

    public function export() {
        return Excel::download(new OfficeExport, 'offices.xlsx');
    }

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function saveOffice() {

        $this->validate();
        $office = Office::create([
            'office' => trim($this->office),
        ]);

        session()->flash('message', $this->office . ' has been added successfully.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function getOffice($id) {
        $office = Office::where('office_id', $id)->first();
        $this->office = $office->office;
        $this->office_id = $office->office_id;
        $this->dispatchBrowserEvent('show-edit-office-modal');
    }

    public function deleteConfirmation($id) {
        $this->office_id = $id;

        $this->dispatchBrowserEvent('show-delete-office-modal');
    }

    public function deleteOffice() {
        $office = Office::where('office_id', $this->office_id)->first();
        $this->office = $office->office;
        $office->delete();

        session()->flash('message', $this->office . ' has been deleted successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewOfficeDetails($id) {

        $office_ = Office::where('office_id', $id)->first();
        
        $this->office_name = $office_->office;

        $this->dispatchBrowserEvent('show-office-modal');

    }

    public function updateOffice() {

        $this->validate();
        $office = Office::where('office_id', $this->office_id)->first();
        $office_name = $office->office;
        $office->office_id = $this->office_id;
        $office->office = trim($this->office);
        $office->save();

        session()->flash('message', $this->office . ' has been updated successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }
}
