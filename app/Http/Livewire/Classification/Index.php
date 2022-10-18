<?php

namespace App\Http\Livewire\Classification;
use App\Models\Classification;
use App\Models\Activity;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassificationExport;
class Index extends Component
{
    public $classification, $classification_id;
    public $classification_name;
    public $searchKey;

    use WithPagination;
 
    protected $paginationTheme = 'bootstrap';


    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Classifications', $access)) {
            session()->flash('message', "Sorry, you don't have access to Item page.");
            $this->redirect('/profile');
        }
        
        return view('livewire.classification.index', [
            'classifications' => Classification::when($this->searchKey, function($query, $searchKey) {
                return $query->where('classification', 'LIKE', "%$searchKey%");
            })->paginate(10),
            'permissions' => $permissions
            ])->layout('livewire.layouts.base');
    }

    protected $rules = [
        'classification' => 'required|min:3',
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function saveClassification() {

        $this->validate();
        $c = Classification::create([
            'classification' => trim($this->classification),
        ]);

        session()->flash('message', $this->classification . ' has been added successfully.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function getClassification($id) {
        $Classification = Classification::where('classification_id', $id)->first();
        $this->classification = $Classification->classification;
        $this->classification_id = $Classification->classification_id;
        $this->dispatchBrowserEvent('show-edit-classification-modal');
    }

    public function deleteConfirmation($id) {
        $this->classification_id = $id;

        $this->dispatchBrowserEvent('show-delete-classification-modal');
    }

    public function deleteClassification() {
        $classification = Classification::where('Classification_id', $this->classification_id)->first();
        $this->classification = $classification->classification;
        $classification->delete();

        session()->flash('message', $this->classification . ' has been deleted successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewClassificationDetails($id) {

        $classification_ = Classification::where('classification_id', $id)->first();
        
        $this->classification_name = $classification_->classification;

        

        $this->dispatchBrowserEvent('show-classification-modal');
    }

    public function updateClassification() {

        $this->validate();
        $Classification = Classification::where('classification_id', $this->classification_id)->first();
        $cName = $Classification->classification;

        $Classification->classification_id = $this->classification_id;
        $Classification->classification = trim($this->classification);
        $Classification->save();

        session()->flash('message', trim($this->classification) . ' has been updated successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }
    public function export() {
        return Excel::download(new ClassificationExport, 'classifications.xlsx');
    }

}
