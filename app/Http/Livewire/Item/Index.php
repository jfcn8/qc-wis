<?php

namespace App\Http\Livewire\Item;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Article;
use App\Models\Delivery;
use App\Models\Unit;
use App\Models\Item;
use App\Models\Reference;
use App\Models\ItemLog;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use App\Exports\ItemExport;
use App\Models\Classification;
use App\Models\Office;
use App\Models\User;
use App\Notifications\RisNotification;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    public $item_id, $description, $stock_number, $reference, $article_id, $unit_id, $stock, $price, $initial_date;
    public $description_name, $stock_number_name, $article_name, $unit_name, $stock_, $price_;
    public $searchKey;
    public $delivery, $classification, $article;
    public $mnemonic, $unit;
    public $classifications = [];
    

    use WithPagination;
 
    protected $paginationTheme = 'bootstrap';


    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Items', $access)) {
            session()->flash('message', "Sorry, you don't have access to Item page.");
            $this->redirect('/profile');
        }

        $articles = Article::orderBy('article')->get();
        $units = Unit::orderBy('unit')->get();
        

        $this->searchKey = trim($this->searchKey);

        $items = DB::table('items')
                ->join('articles', 'articles.article_id', 'items.article_id')
                ->join('units', 'units.unit_id', 'items.unit_id')
                ->join('references', 'references.item_id', 'items.item_id')
                ->selectRaw('items.item_id,items.description,items.stock_number,articles.article,units.unit,sum(references.stock) as stock')
                ->groupBy('items.item_id','items.description','items.stock_number','articles.article','units.unit')
                ->where('articles.article', 'LIKE', "%$this->searchKey%")
                ->orWhere('items.description', 'LIKE', "%$this->searchKey%")
                ->orWhere('items.stock_number', 'LIKE', "%$this->searchKey%")
                ->orderby('articles.article')
                ->paginate(15);

        return view('livewire.item.index', [
            'articles' => $articles,
            'units' => $units,
            'items' => $items,
            'access' => $access,
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');
    }

    protected $rules = [
        'initial_date' => 'required',
        'description' => 'required|min:3',
        'stock_number' => 'required|min:3',
        'article_id' => 'required',
        'unit_id' => 'required',
        'reference' => 'required|min:3',
        'stock' => 'required|numeric',
        'price' => 'required|numeric',
    ];

    protected $messages = [
        'article_id.required' => 'Article is required.',
        'initial_date.required' => 'Date is required.',
        'unit_id.required' => 'Unit is required.',
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function saveItem() {

        $this->validate();
        $item_id = '';
        $itemExisting = false;
        $item = Item::all()
                    ->where('description', $this->description)
                    ->where('stock_number', $this->stock_number)
                    ->where('article_id', $this->article_id)
                    ->where('unit_id', $this->unit_id)
                    ->first();
                    
        if (is_null($item)) {
            $item_ = Item::create([
                'description' => trim($this->description),
                'stock_number' => trim($this->stock_number),
                'article_id' => $this->article_id,
                'unit_id' => $this->unit_id,
            ]);
            $item_id = $item_->item_id;
            $itemExisting = false;
        } else {
            $item_id = $item->item_id;
            $itemExisting = true;
        }

        $reference_id = 0;
        $ref_ = Reference::where('reference', trim($this->reference))
                         ->where('item_id', $item_id)
                         ->where('price', $this->price)->first();

        if (is_null($ref_)) {
            $reference = Reference::create([
                'reference' => trim($this->reference),
                'stock' => trim($this->stock),
                'price' => trim($this->price),
                'item_id' => $item_id,
            ]);

            $reference_id = $reference->reference_id;
        } else {

            $reference_id = $ref_->reference_id;
            $ref_->reference_id = $ref_->reference_id;
            $ref_->stock = $ref_->stock + $this->stock;
            $ref_->save();
        }

        

        if ($this->delivery) {
            $delivery = Delivery::create([
                'delivery_date' => $this->initial_date,
                'stock' => trim($this->stock),
                'item_id' => $item_id,
                'reference_id' => $reference_id,
                'supplier_id' => 1,
                'user_id' => Auth()->user()->id,
            ]);
        }
    
        ItemLog::create([
            'date_request' => $this->initial_date,
            'reference_id' => $reference_id,
            'action' => ($this->delivery) ? 2 : 3,
            'quantity' => trim($this->stock),
            'user_id' => Auth()->user()->id,
        ]);

        if ($itemExisting) {
            session()->flash('message', trim($this->description) . ' is already encoded. Reference Successfully added!');
        } else {
            session()->flash('message', trim($this->description) . ' has been added successfully.');
        }
        
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewItem($id) {

        $item_ = DB::table('items')
                        ->join('articles', 'articles.article_id', '=', 'items.article_id')
                        ->join('units', 'units.unit_id', '=', 'items.unit_id')
                        ->join('references', 'references.item_id', '=', 'items.item_id')
                        ->where('items.item_id', '=', $id)
                        ->get();

        $this->description_name = $item_[0]->description;
        $this->article_name = $item_[0]->article;
        $this->unit_name = $item_[0]->unit;
        $this->stock_number_name = $item_[0]->stock_number;
        $this->price_ = '₱'. number_format($item_[0]->price, 2);
        $this->stock_ = $item_[0]->stock;

        $this->dispatchBrowserEvent('show-item-modal');
    }

    public function getItem($id) {

        $item_ = Item::where('item_id', $id)->first();

        $this->item_id = $item_->item_id;
        $this->description = $item_->description;
        $this->stock_number = $item_->stock_number;
        $this->article_id = $item_->article_id;
        $this->unit_id = $item_->unit_id;
        
        $this->dispatchBrowserEvent('show-edit-item-modal');
    }

    public function updateItem() {

        $this->validate([
            'description' => 'required|min:3',
            'stock_number' => 'required|min:3',
            'article_id' => 'required',
            'unit_id' => 'required',
        ]);
        
        $item_ = Item::where('item_id', $this->item_id)->first();

        $item_->description = trim($this->description);
        $item_->stock_number = trim($this->stock_number);
        $item_->article_id = $this->article_id;
        $item_->unit_id = $this->unit_id;
        $item_->save();

        session()->flash('message', trim($this->description) . ' has been updated successfully.');
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteConfirmation($id) {
        $this->item_id = $id;
        $this->dispatchBrowserEvent('show-delete-item-modal');
    }

    public function deleteItem() {
        $item = Item::where('item_id', $this->item_id)->first();
        $this->description = $item->description;
        $item->delete();

        session()->flash('message', $this->description . ' has been deleted successfully.');
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function export() {
        return Excel::download(new ItemExport, 'items.xlsx');
    }

    public function newArticle() {
        $classification_ = Classification::orderBy('classification')->get();

        $this->classifications = $classification_;

        $this->dispatchBrowserEvent('close-item');
        $this->dispatchBrowserEvent('new-article-modal');
    }

    public function newUnit() {

        $this->dispatchBrowserEvent('close-item-unit');
        $this->dispatchBrowserEvent('new-unit-modal');
    }

    public function saveArticle() {

        $validatedData = $this->validate([
            'classification' => 'required',
            'article' => 'required|min:3',
        ]);

        $a = Article::create([
            'article' => trim($this->article),
            'classification_id' => $this->classification
        ]);

        session()->flash('message', $this->article . ' has been added successfully. You can add item now.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
        $this->dispatchBrowserEvent('show-new-item-modal');
    }

    public function saveUnit() {


        $validatedData = $this->validate([
            'mnemonic' => 'required|min:2',
            'unit' => 'required|min:2',
        ]);

        $a = Unit::create([
            'mnemonic' => trim($this->mnemonic),
            'unit' => trim($this->unit)
        ]);

        session()->flash('message', $this->unit . ' has been added successfully. You can add item now.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
        $this->dispatchBrowserEvent('show-new-item-modal');
    }

    




}
