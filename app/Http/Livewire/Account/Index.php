<?php

namespace App\Http\Livewire\Account;

use App\Mail\EmailFromWebsite;
use App\Models\Office;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $office , $name, $email, $position, $password, $password_confirmation, $isActive;

    public $user_id;
    public $access = ['Activities'];
    public $permissions = ['View'];
    public $SAccess = [];
    public $Spermissions = [];
    public $searchKey;
    public $selectedAccess;
    public $selectedPermissions;
    public $searchUser;

    public function render()
    {
        $searchUser = trim($this->searchUser);

        $offices = Office::orderBy('office')->get();
        $users = User::orderBy('name')
                    ->where('name', 'LIKE', "%$searchUser%")
                    ->paginate(20);

        return view('livewire.account.index', ['users' => $users, 'offices' => $offices])->layout('livewire.layouts.base');
    }

    // protected $rules = [
    //     'name' => 'required|min:2',
    //     'email' => 'required|email|unique:users,email',
    //     'position' => 'required|min:2',
    //     'office' => 'required',
    //     'password' => 'required|confirmed|min:6|max:255',
    // ];

    public function rules() {
        return [
            'name' => 'required|min:2',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user_id)],
            'position' => 'required|min:2',
            'office' => 'required',
            'password' => 'required|confirmed|min:6|max:255',
        ];
    }


    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        // 'confirm_password.required' => 'Confirm password is required.',
    ];

   
    
    public function saveUser() {
        $this->validate();


        // dd(json_encode($this->access));

        $a = User::create([
            'name' => trim($this->name),
            'email' => trim($this->email),
            'position' => trim($this->position),
            'password' => Hash::make($this->password),
            'office_id' => $this->office,
            'permissions' => implode(',', $this->permissions),
            'access' => implode(',', $this->access),
            'isActive' => 1
        ]);

        Mail::to($this->email)->send(new EmailFromWebsite
        (
            '[QC WIS] New User',
            trim($this->name),
            trim($this->email),
            $this->password,
            implode(',', $this->permissions),
            implode(',', $this->access),
            'Account Registered'
        ));

        // dd(implode(',', $this->permissions));

        session()->flash('message', $this->name . ' has been added successfully. Password sent to email.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function newUser() {
        $this->reset();

        $password = Str::random(15);

        $this->password = $password;
        $this->password_confirmation = $password;
        $this->dispatchBrowserEvent('show-new-user-modal');
    }

    public function getUser($id) {

        
        $user = User::where('id', $id)->first();
        $this->cancel();

        $this->user_id = $user->id;
        $this->email = $user->email;
        $this->name = $user->name;
        $this->position = $user->position;
        $this->office = $user->office_id;

        $this->selectedAccess = explode(',', $user->access);
        $this->selectedPermissions = explode(',', $user->permissions);

        $this->Spermissions = explode(',', $user->permissions);
        $this->SAccess = explode(',', $user->access);
        $this->isActive = $user->isActive;

        

        $this->dispatchBrowserEvent('show-edit-user-modal');
    }

    
    public function updateUser() {


        $validatedData = $this->validate([
            'name' => 'required|min:6',
            'email' => 'required|email',
            'position' => 'required|min:3',
            'office' => 'required',
        ]);


        if (trim($this->password)) {
            $validatedData = $this->validate([
                'password' => 'required|confirmed|min:6|max:255',
            ]);
        }

        $user = User::where('id', $this->user_id)->first();

        $user->name = $this->name;
        $user->email = $this->email;
        $user->position = $this->position;
        $user->office_id = $this->office;
        $user->permissions = trim(implode(',', $this->Spermissions),",");
        $user->access = trim(implode(',', $this->SAccess),",");
        if (trim($this->password)) {
            $user->password = Hash::make($this->password);
        }
        $user->isActive = ($this->isActive) ? 1 : 0;
        $user->save();

        session()->flash('message', $this->name . ' has been updated successfully.');
        $this->cancel();
    
    }

    public function resetPassword() {

        $password = Str::random(15);

        $user = User::where('id', $this->user_id)->first();
        $user->password = Hash::make($password);
        $user->save();
        

        Mail::to($this->email)->send(new EmailFromWebsite
        (
            '[QC WIS] Password Reset',
            null,
            null,
            $password,
            null,
            null,
            'Password Reset'
        ));

        session()->flash('message', 'Password of ' . $this->name . ' has been reset successfully. Computer generated password sent in email.');
        $this->cancel();

    }
}
