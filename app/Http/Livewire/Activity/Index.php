<?php

namespace App\Http\Livewire\Activity;

use Livewire\Component;
use Livewire\WithPagination;
// use App\Models\Activity;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Activities', $access)) {
            session()->flash('message', "Sorry, you don't have access to Activities page.");
            $this->redirect('/profile');
        }

        // if (in_array('Delete', $permissions)) {
        //     $activities = Activity::orderBy('created_at', 'DESC')->paginate(20);
        // } else {
        //     $activities = Activity::orderBy('activity_log.created_at', 'DESC')
        //                 ->join('users', 'users.id', 'activity_log.causer_id')
        //                 ->where('users.office_id', Auth()->user()->office_id)
        //                 ->paginate(20);
        // }

         $activities = Activity::orderBy('activity_log.created_at', 'DESC')
                        ->paginate(20);


        return view('livewire.activity.index', [
            'activities' => $activities,
            'permissions' => $permissions,
        ])->layout('livewire.layouts.base');
    }
}
