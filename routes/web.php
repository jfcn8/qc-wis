<?php

use App\Http\Livewire\Office;
use App\Http\Livewire\Unit;
use App\Http\Livewire\Article;
use App\Http\Livewire\Classification;
use App\Http\Livewire\Item;
use App\Http\Livewire\Reference;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Supplier;
use App\Http\Livewire\Delivery;
use App\Http\Livewire\Ris;
use App\Http\Livewire\Signatory;
use App\Http\Livewire\Dbm;
use App\Http\Livewire\Import\Delivery as ImportDelivery;
use App\Http\Livewire;
use App\Http\Livewire\Activity;
use App\Http\Livewire\Account;
use App\Http\Livewire\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', Dashboard\Index::class)->middleware(['auth'])->name('dashboard');
Route::get('offices', Office\Index::class)->middleware(['auth'])->name('offices');
Route::get('units', Unit\Index::class)->middleware(['auth'])->name('units');
Route::get('classifications', Classification\Index::class)->middleware(['auth'])->name('classifications');
Route::get('articles', Article\Index::class)->middleware(['auth'])->name('articles');

Route::get('items', Item\Index::class)->middleware(['auth'])->name('items');
Route::get('items/stock-card', Item\StockCard::class)->middleware(['auth'])->name('stock-card');
Route::get('items/stock-card/{id}', [Item\StockCard::class, 'item'])->middleware(['auth'])->name('stock-cards');
Route::get('items/mism', Item\Mism::class)->middleware(['auth'])->name('mism');
Route::get('items/mism/generated', [Item\Mism::class, 'generated'])->middleware(['auth'])->name('mism');
Route::get('items/ssmi', Item\SSMI::class)->middleware(['auth'])->name('ssmi');
Route::get('items/ssmi/generated', [Item\SSMI::class, 'generated'])->middleware(['auth'])->name('ssmi');

Route::get('references/{id}', Reference\Index::class)->middleware(['auth'])->name('references');
Route::get('suppliers', Supplier\Index::class)->middleware(['auth'])->name('suppliers');
Route::get('deliveries', Delivery\Index::class)->middleware(['auth'])->name('deliveries');

Route::get('ris', Ris\Index::class)->middleware(['auth'])->name('ris');
Route::get('ris/create', Ris\Create::class)->middleware(['auth'])->name('ris.create');
Route::get('ris/item/{id}', Ris\Item::class)->middleware(['auth'])->name('ris.item');
Route::get('ris/item/print/{id}', [Ris\Item::class, 'print'])->middleware(['auth'])->name('ris.print');

Route::get('dbms', Dbm\Index::class)->middleware(['auth'])->name('dbms');
Route::get('signatories', Signatory\Index::class)->middleware(['auth'])->name('signatories');
Route::get('activities', Activity\Index::class)->middleware(['auth'])->name('activities');

Route::get('import/delivery', ImportDelivery::class)->middleware(['auth'])->name('import-delivery');
Route::post('import/delivery', [ImportDelivery::class, 'ImportDelivery'])->middleware(['auth'])->name('importDelivery');

Route::get('accounts', Account\Index::class)->middleware(['auth'])->name('accounts');

Route::get('profile', Account\Profile::class)->middleware(['auth'])->name('profile');

Route::get('notifications', Notification\Index::class)->middleware(['auth'])->name('notifications');

// Route::get('ris/create', Ris\Create::class)->middleware(['auth']);

// Route::get('/reference/{id}', function ($id) {
//     return $id;
// })->middleware(['auth'])->name('reference');


require __DIR__.'/auth.php';
