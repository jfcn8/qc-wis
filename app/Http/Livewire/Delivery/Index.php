<?php

namespace App\Http\Livewire\Delivery;

use App\Exports\DeliveryExport;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Supplier;
use App\Models\Reference;
use App\Models\Delivery;
use App\Models\ItemLog;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    public $delivery_id, $delivery_date, $stock, $supplier_id, $item_id, $user_id, $article;
    public $stock_, $supplier_, $item, $user;

    public $description, $items, $supplier, $initial;
    public $stock_number, $unit_id, $reference, $price, $description_;

    public $delivery_price, $delivery_unit, $delivery_reference, $delivery_stock_number, $delivery_supplier, $delivery_stock, $delivery_date_, $delivery_description_;
    public $searchDateFrom, $searchDateTo;

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'delivery_date' => 'required|min:3',
        'description' => 'required',
        'article' => 'required',
        'unit_id' => 'required',
        'supplier' => 'required',
        'stock_number' => 'required|min:3',
        'reference' => 'required|min:3',
        'stock' => 'required|numeric',
        'price' => 'required|numeric',
    ];

    public function mount() {
        $this->items = collect();
        $this->stock_number = collect();
        $this->unit_id = collect();

        $this->supplier = 1;
    }

    public function updatedArticle($article_id) {
        $this->items = Item::where('article_id', $article_id)->orderBy('description')->get();
    }
    
    public function updatedDescription($description) {

        
        $stock__ = Item::where('item_id', $description)->first();
        $this->stock_number  = trim($stock__->stock_number);
        $this->description_ = trim($stock__->description);
        $this->unit_id = $stock__->unit_id;

    }

    protected $messages = [
        'article.required' => 'Article is required.',
        'delivery_date.required' => 'Delivery Date is required.',
        'unit_id.required' => 'Unit is required.',
        'stock_number.required' => 'Stock Number is required.',
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Deliveries', $access)) {
            session()->flash('message', "Sorry, you don't have access to Delivery page.");
            $this->redirect('/profile');
        }


        // $deliveries = DB::select('select d.*,i.description from deliveries d inner join items i on i.item_id=d.item_id order by d.delivery_date')->paginate(10);
        
        if ($this->searchDateFrom != null && $this->searchDateTo != null) {
            $deliveries = DB::table('deliveries')
                        ->join('items', 'items.item_id', '=', 'deliveries.item_id')
                        ->join('articles', 'articles.article_id', '=', 'items.article_id')
                        ->join('units', 'units.unit_id', '=', 'items.unit_id')
                        ->join('references', 'references.reference_id', '=', 'deliveries.reference_id')
                        ->join('item_logs', 'item_logs.reference_id', '=', 'references.reference_id')
                        ->join('suppliers', 'suppliers.supplier_id', '=', 'deliveries.supplier_id')
                        ->select('deliveries.delivery_id','deliveries.delivery_date','deliveries.stock','suppliers.supplier','items.description','articles.article','items.stock_number','references.reference','units.unit','references.price')
                        ->groupBy('deliveries.delivery_id')
                        ->whereBetween('deliveries.delivery_date', [$this->searchDateFrom, $this->searchDateTo])
                        ->orderBy('deliveries.delivery_date', 'DESC')
                        ->orderBy('deliveries.delivery_id', 'DESC')
                        ->paginate(15);
        } else {
            $deliveries = DB::table('deliveries')
            ->join('items', 'items.item_id', '=', 'deliveries.item_id')
            ->join('articles', 'articles.article_id', '=', 'items.article_id')
            ->join('units', 'units.unit_id', '=', 'items.unit_id')
            ->join('references', 'references.reference_id', '=', 'deliveries.reference_id')
            ->join('item_logs', 'item_logs.reference_id', '=', 'references.reference_id')
            ->join('suppliers', 'suppliers.supplier_id', '=', 'deliveries.supplier_id')
            ->select('deliveries.delivery_id','deliveries.delivery_date','deliveries.stock','suppliers.supplier','items.description','articles.article','items.stock_number','references.reference','units.unit','references.price')
            ->groupBy('deliveries.delivery_id')
            ->orderBy('deliveries.delivery_date', 'DESC')
            ->orderBy('deliveries.delivery_id', 'DESC')
            ->paginate(15);
        }

        

                        // dd($deliveries);

                        // dd($deliveries);

                        // ->paginate(10);

        // dd($deliveries);
        
        $articles = Article::orderBy('article')->get();
        $units = Unit::orderBy('unit')->get();
        $suppliers = Supplier::orderBy('supplier')->get();

        return view('livewire.delivery.index', [
            'deliveries' => $deliveries,
            'articles' => $articles,
            'units' => $units,
            'items' => null,
            'suppliers' => $suppliers,
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');
    }

    public function saveDelivery() {

        $this->validate();
        $msg = '';
        $action = 2;

        $itemCheck = Item::where('item_id' ,$this->description)->first();

        // dd($itemCheck);

        if ($itemCheck->unit_id == $this->unit_id &&
            $itemCheck->article_id == $this->article &&
            $itemCheck->stock_number == $this->stock_number) {

                //check if reference exists
                $ref = Reference::where('reference', $this->reference)
                                ->where('item_id', $this->description)->first();

                if(!is_null($ref)) {

                    $refPrice = number_format($ref->price, 2);
                    $this->price = number_format($this->price, 2);

                    // check if price is the same
                    if($refPrice === $this->price) {

                        // update reference stock
                        $ref_stock = trim($ref->stock);
                        $ref->reference_id = $ref->reference_id;
                        $ref->stock = $ref_stock + $this->stock;
                        $ref->save();

                        ItemLog::create([
                            'date_request' => $this->delivery_date,
                            'reference_id' => $ref->reference_id,
                            'action' => 2,
                            'quantity' => trim($this->stock),
                            'user_id' => Auth()->user()->id,
                        ]);

                        $delivery = Delivery::create([
                            'delivery_date' => $this->delivery_date,
                            'stock' => trim($this->stock),
                            'item_id' => trim($this->description),
                            'reference_id' => $ref->reference_id,
                            'supplier_id' => $this->supplier,
                            'user_id' => Auth()->user()->id,
                        ]);
                        
                    } else {

                        // update reference with new price
                        $reference = Reference::create([
                            'reference' => trim($this->reference),
                            'stock' => trim($this->stock),
                            'price' => trim($this->price),
                            'item_id' => $itemCheck->item_id,
                        ]);

                        ItemLog::create([
                            'date_request' => $this->delivery_date,
                            'reference_id' => $reference->reference_id,
                            'action' => 2,
                            'quantity' => trim($this->stock),
                            'user_id' => Auth()->user()->id,
                        ]);

                        $delivery = Delivery::create([
                            'delivery_date' => $this->delivery_date,
                            'stock' => trim($this->stock),
                            'item_id' => trim($this->description),
                            'reference_id' => $reference->reference_id,
                            'supplier_id' => $this->supplier,
                            'user_id' => Auth()->user()->id,
                        ]);

                    }

                } else {

                    // 1 RIS, 2 Delivery, 3 Initial
                    // reference doesnt exists. add reference
                    $reference = Reference::create([
                        'reference' => trim($this->reference),
                        'stock' => trim($this->stock),
                        'price' => trim($this->price),
                        'item_id' => $itemCheck->item_id,
                    ]);


                    
                    if ($this->initial == true) {
                        $action = 3;
                    } else {
                        $delivery = Delivery::create([
                            'delivery_date' => $this->delivery_date,
                            'stock' => trim($this->stock),
                            'item_id' => trim($this->description),
                            'reference_id' => $reference->reference_id,
                            'supplier_id' => $this->supplier,
                            'user_id' => Auth()->user()->id,
                        ]);
                    }

                    ItemLog::create([
                        'date_request' => $this->delivery_date,
                        'reference_id' => $reference->reference_id,
                        'action' => $action,
                        'quantity' => trim($this->stock),
                        'user_id' => Auth()->user()->id,
                    ]);

                    

                }

        } else {

            $item = Item::create([
                'description' => trim($this->description_),
                'stock_number' => trim($this->stock_number),
                'article_id' => $this->article,
                'unit_id' => $this->unit_id,
            ]);
    
            $reference = Reference::create([
                'reference' => trim($this->reference),
                'stock' => trim($this->stock),
                'price' => trim($this->price),
                'item_id' => $item->item_id,
            ]);
        
            ItemLog::create([
                'date_request' => $this->delivery_date,
                'reference_id' => $reference->reference_id,
                'action' => 3,
                'quantity' => trim($this->stock),
                'user_id' => Auth()->user()->id,
            ]);

            $delivery = Delivery::create([
                'delivery_date' => $this->delivery_date,
                'stock' => trim($this->stock),
                'item_id' => $item->item_id,
                'reference_id' => $reference->reference_id,
                'supplier_id' => $this->supplier,
                'user_id' => Auth()->user()->id,
            ]);
        }


        // run this if it match all reference, unit, article description.
        if ($action == 3) {
            session()->flash('message',  'Initial has been added successfully.');
        } else {
            session()->flash('message',  'Delivery has been added successfully.');
        }
        
        $this->dispatchBrowserEvent('close-modal');
        $this->resetExcept('supplier');
    }

    public function viewDelivery($id) {

        $delivery = DB::table('deliveries')
                        ->join('items', 'items.item_id', '=', 'deliveries.item_id')
                        ->join('articles', 'articles.article_id', '=', 'items.article_id')
                        ->join('units', 'units.unit_id', '=', 'items.unit_id')
                        ->join('references', 'references.reference_id', '=', 'deliveries.reference_id')
                        ->join('suppliers', 'suppliers.supplier_id', '=', 'deliveries.supplier_id')
                        ->select('deliveries.delivery_id','deliveries.delivery_date','deliveries.stock','suppliers.supplier','items.description','items.stock_number','references.reference','units.unit','references.price')
                        ->where('deliveries.delivery_id', '=', $id)
                        ->first();

        $this->delivery_id = $delivery->delivery_id;
        $this->delivery_description_ = $delivery->description;
        $this->delivery_date_ = $delivery->delivery_date;
        $this->delivery_stock = $delivery->stock;
        $this->delivery_supplier = $delivery->supplier;
        $this->delivery_stock_number = $delivery->stock_number;
        $this->delivery_reference = $delivery->reference;
        $this->delivery_unit = $delivery->unit;
        $this->delivery_price = $delivery->price;

        $this->dispatchBrowserEvent('show-delivery-modal');
    }

    public function deleteConfirmation($id) {
        $this->delivery_id = $id;
        $this->dispatchBrowserEvent('show-delete-delivery-modal');
    }

    public function deleteDelivery() {
        $delivery = Delivery::where('delivery_id', $this->delivery_id)->first();
        $this->delivery_id = $delivery->delivery_id;
        $this->reference = $delivery->reference_id;
        $this->delivery_date = $delivery->delivery_date;
        $this->stock = $delivery->stock;

        $itemLog = ItemLog::where('date_request', $this->delivery_date)
                        ->where('action', 2)
                        ->where('quantity', $delivery->stock)
                        ->where('reference_id', $delivery->reference_id)
                        ->first();

        $delivery->delete();
        $itemLog->delete();

        $ref = Reference::where('reference_id', $this->reference)->first();
        $refStock = $ref->stock - $this->stock;
        $ref->stock = $refStock;
        $ref->save();

        session()->flash('message', 'Delivery has been deleted successfully.');
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function getDelivery($id) {

        $delivery = DB::table('deliveries')
                        ->join('items', 'items.item_id', '=', 'deliveries.item_id')
                        ->join('articles', 'articles.article_id', '=', 'items.article_id')
                        ->join('units', 'units.unit_id', '=', 'items.unit_id')
                        ->join('references', 'references.reference_id', '=', 'deliveries.reference_id')
                        ->join('suppliers', 'suppliers.supplier_id', '=', 'deliveries.supplier_id')
                        ->select('deliveries.delivery_id','deliveries.delivery_date','deliveries.stock','suppliers.supplier','suppliers.supplier_id','items.description','items.item_id','items.stock_number','references.reference','units.unit','units.unit_id','references.price','articles.article_id')
                        ->where('deliveries.delivery_id', $id)
                        ->first();

        $this->delivery_id = $delivery->delivery_id;
        $this->delivery_date = $delivery->delivery_date;
        $this->stock = $delivery->stock;
        $this->supplier = $delivery->supplier_id;
        $this->description = $delivery->item_id;
        $this->unit_id = $delivery->unit_id;
        $this->reference = $delivery->reference;
        $this->article = $delivery->article_id;
        $this->price = $delivery->price;
        $this->dispatchBrowserEvent('show-edit-delivery-modal');
    }

    public function updateDelivery() {

        $validatedData = $this->validate([
            'stock' => 'required|numeric|min:1',
            'delivery_date' => 'required',
            'supplier' => 'required',
        ]);

        $delivery = Delivery::where('delivery_id', $this->delivery_id)->first();

        $deliveryDate = $delivery->delivery_date;
        $delivery_stock = $delivery->stock;
        $reference_stock = $delivery->Reference->stock;

        $delivery->stock = trim($this->stock);
        $delivery->delivery_date = $this->delivery_date;
        $delivery->supplier_id = $this->supplier;
        $delivery->save();

        $reference = Reference::where('reference_id', $delivery->reference_id)->first();
        $reference->stock = trim($this->stock);
        $reference->save();

        $itemLogDelivery = ItemLog::where('reference_id' ,$delivery->reference_id)
                              ->where('date_request', $deliveryDate)
                              ->where('action', 2)
                              ->first();
        $itemLogDelivery->quantity = trim($this->stock);
        $itemLogDelivery->date_request = $this->delivery_date;
        $itemLogDelivery->save();

        session()->flash('message', 'Delivery has been updated successfully.');
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function resetSearch() {
        $this->reset(['searchDateFrom', 'searchDateTo']);
    }

    public function export() {
        if ($this->searchDateFrom != null && $this->searchDateTo != null) {
            return Excel::download(new DeliveryExport($this->searchDateFrom, $this->searchDateTo), 'deliveries.xlsx');
        } else {
            return Excel::download(new DeliveryExport(null, null), 'deliveries.xlsx');
        }
    }

}
