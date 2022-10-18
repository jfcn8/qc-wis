<?php

namespace App\Http\Livewire\Dbm;

use App\Models\DBM;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $filename, $remarks, $file_id;

    protected $rules = [
        'filename' => 'required|mimes:jpg,jpeg,png,svg,gif,pdf,doc,docx,xls,xlsx|max:5048',
        'remarks' => 'required|min:3'
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }


    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Price Lists', $access)) {
            session()->flash('message', "Sorry, you don't have access to Price Lists page.");
            $this->redirect('/profile');
        }

        $dbms = DBM::paginate(10);
        return view('livewire.dbm.index', [
            'dbms' => $dbms,
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');

    }

    public function saveDBM() {

        $this->validate();

        DBM::create([
            'filename' => $this->filename->hashName(),
            'remarks' => trim($this->remarks),
        ]);

        $this->filename->store('files', 'public');

        session()->flash('message', 'File has been added successfully.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }


    public function deleteConfirmation($id) {
        $this->file_id = $id;

        $this->dispatchBrowserEvent('show-delete-dbm-modal');
    }

    public function deleteDBM() {
        $dbm = DBM::where('id', $this->file_id)->first();
        $dbm->delete();
        session()->flash('message', 'File has been deleted successfully.');
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }
}
