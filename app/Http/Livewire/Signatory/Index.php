<?php

namespace App\Http\Livewire\Signatory;

use App\Exports\SignatoryExport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Signatory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierExport;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $signatory_, $remarks_;
    public $signatory_id, $name, $designation, $mism_noting, $mism_certified, $mism_approved, $ssmi_noting, $ssmi_certifying, $ssmi_approving;
    public $name_, $designation_, $mism_certified_, $mism_approved_, $ssmi_noting_, $ssmi_certifying_, $ssmi_approving_, $mism_noting_;

    protected $rules = [
        'name' => 'required|min:3',
        'designation' => 'required|min:3',
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Signatories', $access)) {
            session()->flash('message', "Sorry, you don't have access to Signatories page.");
            $this->redirect('/profile');
        }


        $signatories = Signatory::paginate(10);
        return view('livewire.signatory.index', [
            'signatories' => $signatories,
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');
    }

    public function saveSignatory() {

        $this->validate();
        Signatory::create([
            'name' => trim($this->name),
            'designation' => trim($this->designation),
            'mism_noting' => $this->mism_noting,
            'mism_certified' => $this->mism_certified,
            'mism_approved' => $this->mism_approved,
            'ssmi_noting' => $this->ssmi_noting,
            'ssmi_certifying' => $this->ssmi_certifying,
            'ssmi_approving' => $this->ssmi_approving,
        ]);

        session()->flash('message', trim($this->name) . ' has been added successfully.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function getSignatory($id) {
        $signatory = Signatory::where('signatory_id', $id)->first();
        $this->signatory_id = $signatory->signatory_id;
        $this->name = $signatory->name;
        $this->designation = $signatory->designation;
        $this->mism_noting = $signatory->mism_noting;
        $this->mism_certified = $signatory->mism_certified;
        $this->mism_approved = $signatory->mism_approved;
        $this->ssmi_noting = $signatory->ssmi_noting;
        $this->ssmi_certifying = $signatory->ssmi_certifying;
        $this->ssmi_approving = $signatory->ssmi_approving;
        $this->dispatchBrowserEvent('show-edit-signatory-modal');
    }

    public function updateSignatory() {

        $this->validate();
        $signatory = Signatory::where('signatory_id', $this->signatory_id)->first();

        $signatory->signatory_id = $this->signatory_id;
        $signatory->name = trim($this->name);
        $signatory->designation = trim($this->designation);
        $signatory->mism_noting = $this->mism_noting;
        $signatory->mism_certified = $this->mism_certified;
        $signatory->mism_approved = $this->mism_approved;
        $signatory->ssmi_noting = $this->ssmi_noting;
        $signatory->ssmi_certifying = $this->ssmi_certifying;
        $signatory->ssmi_approving = $this->ssmi_approving;
        $signatory->save();

        session()->flash('message', $this->name . ' has been updated successfully.');
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteConfirmation($id) {
        $this->signatory_id = $id;
        $this->dispatchBrowserEvent('show-delete-signatory-modal');
    }

    public function deleteSignatory() {
        $signatory = Signatory::where('signatory_id', $this->signatory_id)->first();
        $this->name = $signatory->name;
        $signatory->delete();

        session()->flash('message', $this->name . ' has been deleted successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewSignatoryDetails($id) {

        $signatory = Signatory::where('signatory_id', $id)->first();
        $this->signatory_id = $signatory->signatory_id;
        $this->name_ = $signatory->name;
        $this->designation_ = $signatory->designation;
        $this->mism_noting_ = $signatory->mism_noting;
        $this->mism_certified_ = $signatory->mism_certified;
        $this->mism_approved_ = $signatory->mism_approved;
        $this->ssmi_noting_ = $signatory->ssmi_noting;
        $this->ssmi_certifying_ = $signatory->ssmi_certifying;
        $this->ssmi_approving_ = $signatory->ssmi_approving;
        $this->dispatchBrowserEvent('show-signatory-modal');

    }

    public function export() {
        return Excel::download(new SignatoryExport, 'signatories.xlsx');
    }
}
