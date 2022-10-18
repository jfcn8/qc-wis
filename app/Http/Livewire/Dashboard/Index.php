<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Item;
use App\Models\Ris;
use DateTime;

class Index extends Component
{
    public function render()
    {
        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        $itemsLowStock = DB::table('items')
                        ->join('articles', 'articles.article_id', '=', 'items.article_id')
                        ->join('units', 'units.unit_id', '=', 'items.unit_id')
                        ->join('references', 'references.item_id', '=', 'items.item_id')
                        ->select('items.item_id','items.description','items.stock_number','articles.article','units.unit')
                        ->selectRaw('SUM(references.stock) as stock')
                        ->having('stock', '<=', 100)
                        // ->where('references.stock', '>=', 20)
                        ->groupBy('items.item_id','items.description','items.stock_number','articles.article','units.unit')
                        ->orderBy('stock')
                        // ->toSql();
                        ->get();


                        // dd($itemsLowStock);


        $itemCount = Item::count();

        $now = new DateTime();

        $datenow = date('Y-m-d');// date now
        $mon = new DateTime($datenow);
        $sun = new DateTime($datenow);
        $mon->modify('Last Monday');
        $sun->modify('Next Sunday');

        $from = $mon->format('Y-m-d');
        $to = $sun->format('Y-m-d');

        $deliveryCount = Delivery::whereBetween('delivery_date', [$from, $to])->count();
        $pendingCount = DB::table('ris')
                        ->where('gso', 0)
                        ->orWhere('budget', 0)
                        ->groupBy('ris_no')
                        ->count();

        $risCount = Ris::whereBetween('date_request', [$from, $to])->count();
    
        return view('livewire.dashboard.index', [
            'itemsLowStock' => $itemsLowStock,
            'itemCount' => $itemCount,
            'deliveryCount' => $deliveryCount,
            'pendingCount' => $pendingCount,
            'risCount' => $risCount,
            'access' => $access,
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');
    }
}
