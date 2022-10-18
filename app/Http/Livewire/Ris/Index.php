<?php

namespace App\Http\Livewire\Ris;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Article;
use App\Models\Unit;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Office;
use App\Models\Reference;
use App\Models\Ris;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RisNotification;

class Index extends Component
{
    public $ris_, $itemLog_;
    public $ris_id;

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {

        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('RIS', $access)) {
            session()->flash('message', "Sorry, you don't have access to RIS page.");
            $this->redirect('/profile');
        }

        $ris = Ris::orderBy('date_request', 'desc')->orderBy('ris_id', 'desc')->paginate(15);

        return view('livewire.ris.index', [
            'ris' => $ris,
            'itemLog_' => $this->itemLog_,
            'permissions' => $permissions
        ])->layout('livewire.layouts.base');
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewRis($id) {

        $ris_ = Ris::where('ris_id', $id)->first();
        $itemLog_ = ItemLog::where('ris_no', $ris_->ris_no)->get();

        $this->ris_ = $ris_;
        // $this->itemLog_ = $itemLog_;
        $this->dispatchBrowserEvent('show-ris-modal');
    }

    public function denyConfirmation($id) {
        $this->ris_id = $id;
        $this->dispatchBrowserEvent('show-deny-ris-modal');
    }
    public function denyRIS() {
        $ris = Ris::where('ris_id', $this->ris_id)->first();
        $off = "";
        if (Auth()->user()->office_id == 1) {
            $off = " by GSO";
            $ris->gso = 2;
            $ris->save();
        } elseif (Auth()->user()->office_id == 2) {
            $off = " by Budget Office";
            $ris->budget = 2;
            $ris->save();
        }

        $users = User::where('office_id', $ris->office_id)
                         ->orWhere('office_id', 1)
                         ->orWhere('office_id', 2)->get();

        $notification = [
            'model_id' =>  $ris->ris_id,
            'date_request' => '',
            'purpose' => $ris->purpose,
            'office' => $ris->Office->office,
            'action' => 'Denied RIS Request' . $off,
        ];

        Notification::send($users, new RisNotification($notification));

        $this->dispatchBrowserEvent('close-modal');
        session()->flash('message', "RIS Request successfully denied.");
    }

    public function approveConfirmation($id) {
        $this->ris_id = $id;
        $this->dispatchBrowserEvent('show-approve-ris-modal');
    }
    public function approveRIS() {
        $ris = Ris::where('ris_id', $this->ris_id)->first();
        $off = "";
        if (Auth()->user()->office_id == 1) {
            $off = " by GSO";
            $ris->gso = 1;
            $ris->save();
        } elseif (Auth()->user()->office_id == 2) {
            $off = " by Budget Office";
            $ris->budget = 1;
            $ris->save();
        }

        $users = User::where('office_id', $ris->office_id)
                         ->orWhere('office_id', 1)
                         ->orWhere('office_id', 2)->get();

        $notification = [
            'model_id' =>  $ris->ris_id,
            'date_request' => '',
            'purpose' => $ris->purpose,
            'office' => $ris->Office->office,
            'action' => 'Approved RIS Request' . $off,
        ];

        Notification::send($users, new RisNotification($notification));


        $this->dispatchBrowserEvent('close-modal');
        session()->flash('message', "RIS Request successfully approved.");
        
    }

    


    public function deleteConfirmation($id) {
        $this->ris_id = $id;
        $this->dispatchBrowserEvent('show-delete-ris-modal');
    }

    public function deleteRIS() {

        $ris = Ris::where('ris_id', $this->ris_id)->first();

        $itemLogs = ItemLog::where('ris_no', $ris->ris_no)->get();
        foreach($itemLogs as $itemlog) {
            $reference = Reference::where('reference_id', $itemlog->reference_id)->first();
            $reference->stock = $reference->stock + $itemlog->quantity;
            $reference->save();
            ItemLog::where('item_log_id', $itemlog->item_log_id)->delete();
        }
        $ris->delete();

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
        session()->flash('message', "RIS Request successfully deleted. Item's requested quantity has been returned to its reference.");
    }

}
