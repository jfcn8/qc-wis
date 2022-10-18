<?php

namespace App\Http\Livewire\Notification;

use App\Notifications\RisNotification;
use Illuminate\Notifications\Notification;
use Livewire\Component;

class Index extends Component
{
    public $notification_id;

    public function render()
    {
        // $notifications = Notification::all();

        $notifications = Auth()->user()->notifications;

        return view('livewire.notification.index', [
            'notifications' => $notifications
        ])->layout('livewire.layouts.base');
    }

    public function markAsRead($id) {
        $user = Auth()->user();
        $notif = $user->notifications()->find($id);
        $notif->markAsRead();
        session()->flash('message', 'Notification marked as read successfully.');
    }

    public function markAllAsRead() {
        $user = Auth()->user();
        $user->unreadNotifications->markAsRead();
        session()->flash('message', 'Notification marked all as read successfully.');
    }
}
