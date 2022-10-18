<?php

namespace App\Http\Livewire\Account;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public $name, $position, $email, $office, $permissions, $access, $password, $password_confirmation;
    public $user_id;

    public function render()
    {
        // $user = User::where('id', Auth()->user()->id)->first();

        return view('livewire.account.profile')->layout('livewire.layouts.base');
    }


    public function mount()
    {
        $user = User::where('id', Auth()->user()->id)->first();
        if(!empty($user)) {
            $this->user_id = $user->id;
            $this->name = $user->name;  
            $this->position = $user->position;
            $this->email = $user->email;
            $this->office = $user->office->office;
            $this->permissions = $user->permissions;
            $this->access = $user->access;
        } else {
            session()->flash('message', 'User not found.');
            Redirect('/');
        }
    }

    public function updateInformation() {

        $validatedData = $this->validate([
            'name' => 'required|min:3',
            'position' => 'required|min:3',
        ]);


        if (trim($this->password)) {
            $validatedData = $this->validate([
                'password' => 'required|confirmed|min:6|max:255',
            ]);
        }

        $user = User::where('id', $this->user_id)->first();

        $user->name = $this->name;
        $user->position = $this->position;
        if (trim($this->password)) {
            $user->password = Hash::make($this->password);
        }
        $user->save();

        session()->flash('message', 'Your info has been updated successfully.');
        $this->cancel();

    }

    public function cancel() {
        // $this->reset();
    }
}
