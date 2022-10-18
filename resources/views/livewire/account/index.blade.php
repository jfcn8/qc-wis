<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Users</h6>
        
        <button type="button" class="btn btn-outline-primary btn-sm mt-1" wire:click="newUser()">New</button>
        <button class="btn btn-sm btn-primary mt-1 float-right" wire:click="export()">Export</button>
    </div>
    <div class="card-body">

        @include('livewire.layouts.loading')

        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" wire:model="searchUser" class="form-control" placeholder="Name">
        </div>

        <div>
            @if (session()->has('message'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Office</th>
                        <td>Status</td>
                        <th>Permission</th>
                        <th>Access</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($users->count() > 0)
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->position }}</td>
                                <td>{{ $user->office->office }}</td>
                                <td <?php echo (!$user->isActive) ? "class='bg-danger text-white'" : "" ?>>{{ ($user->isActive) ? "Active" : "Inactive" }}</td>
                                <td style="font-size: 13px; font-weight: 800;">
                                    @php
                                        $permissions = explode(',', $user->permissions);
                                        foreach ($permissions as $keyPermission => $valuePermission) {
                                            echo $valuePermission . '<br>';
                                        }
                                    @endphp
                                </td>
                                <td style="font-size: 13px; font-weight: 800;">
                                    @php
                                        $access = explode(',', $user->access);
                                        foreach ($access as $keyAccess => $Accessvalue) {
                                            echo $Accessvalue . '<br>';
                                        }
                                    @endphp
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" wire:click="getUser({{ $user->id }})">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="8">
                            <div class="not-found">
                                <img src="{{ asset('img/no-record-found.gif') }}" alt="">
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            {{ $users->links() }}
        </div>

        <div wire:ignore.self class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveUser">
                        <div class="row mb-3">
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" wire:model="name" class="form-control" id="name" autocomplete="off" placeholder="Juan Dela Cruz">
                                    @error('name')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" wire:model="email" class="form-control" id="email" autocomplete="off" placeholder="JuanDelaCruz@gmail.com">
                                    @error('email')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" wire:model="position" class="form-control" id="position" autocomplete="off" placeholder="Admin">
                                    @error('position')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
        
                                <div class="form-group">
                                    <label for="office">Office</label>
                                    <select class="form-control" wire:model="office">
                                        <option value="">Select Office</option>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->office_id }}">{{ $office->office }}</option>
                                        @endforeach
                                    </select>
                                    @error('office')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" wire:model="password" class="form-control" id="password" autocomplete="off" value="">
                                    @error('password')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" wire:model="password_confirmation" class="form-control" id="password_confirmation" autocomplete="off">
                                    @error('password_confirmation')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"id="showPassword">
                                    <label class="form-check-label" for="showPassword">
                                    Show Password
                                    </label>
                                </div>

                                <span class="text-danger mb-3">Password is computer generated.<br>If you want to change it, type-in your custom password.<br>Password will be sent in registered email.</span>
                            
                            </div>
                            <div class="col-lg-5">
                                <span><b>Permissions</b></span>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="permissions" value="View" id="View">
                                    <label class="form-check-label" for="View">
                                    View
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="permissions" value="Add" id="add">
                                    <label class="form-check-label" for="add">
                                    Can Add
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="permissions" value="Edit" id="edit">
                                    <label class="form-check-label" for="edit">
                                    Can Edit
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="permissions" value="Delete" id="delete">
                                    <label class="form-check-label" for="delete">
                                    Can Delete
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="permissions" value="Generate" id="generate">
                                    <label class="form-check-label" for="generate">
                                    Can Generate
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="permissions" value="Approve/Disapprove RIS" id="Approve">
                                    <label class="form-check-label" for="Approve">
                                    Can Approve/Disapprove RIS
                                    </label>
                                </div>

                                <hr>

                                <span><b>Access</b></span>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Dashboard" id="dashboard">
                                    <label class="form-check-label" for="dashboard">
                                        Dashboard
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Items" id="items">
                                    <label class="form-check-label" for="items">
                                        Items
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Deliveries" id="Deliveries">
                                    <label class="form-check-label" for="Deliveries">
                                        Deliveries
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="RIS" id="RIS">
                                    <label class="form-check-label" for="RIS">
                                        RIS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Offices" id="Offices">
                                    <label class="form-check-label" for="Offices">
                                        Offices
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Units" id="Units">
                                    <label class="form-check-label" for="Units">
                                        Units
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Classifications" id="Classifications">
                                    <label class="form-check-label" for="Classifications">
                                        Classifications
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Articles" id="Articles">
                                    <label class="form-check-label" for="Articles">
                                        Articles
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Suppliers" id="Suppliers">
                                    <label class="form-check-label" for="Suppliers">
                                        Suppliers
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Signatories" id="Signatories">
                                    <label class="form-check-label" for="Signatories">
                                        Signatories
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Price Lists" id="price">
                                    <label class="form-check-label" for="price">
                                        Price List
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Accounts" id="Accounts">
                                    <label class="form-check-label" for="Accounts">
                                        Accounts
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="access" value="Activities" id="Activities">
                                    <label class="form-check-label" for="Activities">
                                        Activities
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        
                        <button type="submit" class="btn btn-sm btn-outline-primary">Save Changes</button>
                    </form>
                </div>
            </div>
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUser" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editUser">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateUser">
                        <div class="row mb-3">
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" wire:model="name" class="form-control" id="uname" autocomplete="off" placeholder="Juan Dela Cruz">
                                    @error('name')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" wire:model="email" class="form-control" id="uemail" autocomplete="off" placeholder="JuanDelaCruz@gmail.com">
                                    @error('email')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" wire:model="position" class="form-control" id="uposition" autocomplete="off" placeholder="Admin">
                                    @error('position')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
        
                                <div class="form-group">
                                    <label for="office">Office</label>
                                    <select class="form-control" wire:model="office">
                                        <option value="">Select Office</option>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->office_id }}">{{ $office->office }}</option>
                                        @endforeach
                                    </select>
                                    @error('office')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" wire:model="password" class="form-control" id="upassword" autocomplete="off">
                                    @error('password')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" wire:model="password_confirmation" class="form-control" id="upassword_confirmation" autocomplete="off">
                                    @error('password_confirmation')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <span class="text-danger mb-3">Leave the password blank if you don't want to change it.</span>


                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"id="ushowPassword">
                                    <label class="form-check-label" for="ushowPassword">
                                    Show Password
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"id="isActive" wire:model="isActive">
                                    <label class="form-check-label" for="isActive">
                                    is Active
                                    </label>
                                </div>

                            </div>
                            <div class="col-lg-5">
                                <span><b>Permissions</b></span>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="Spermissions" value="View" id="uView">
                                    <label class="form-check-label" for="uView">
                                    View
                                    </label>
                                </div>

                                

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="Spermissions" value="Add" id="uadd" checked >
                                    <label class="form-check-label" for="uadd">
                                    Can Add
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="Spermissions" value="Edit" id="uedit">
                                    <label class="form-check-label" for="uedit">
                                    Can Edit
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="Spermissions" value="Delete" id="udelete" >
                                    <label class="form-check-label" for="udelete">
                                    Can Delete
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="Spermissions" value="Generate" id="ugenerate">
                                    <label class="form-check-label" for="ugenerate">
                                    Can Generate
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="Spermissions" value="Approve/Disapprove RIS" id="uApprove">
                                    <label class="form-check-label" for="uApprove">
                                    Can Approve/Disapprove RIS
                                    </label>
                                </div>

                                <hr>

                                <span><b>Access</b></span>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Dashboard" id="udashboard" >
                                    <label class="form-check-label" for="udashboard">
                                        {{-- @if(in_array('Dashboard',$selectedAccess , $strict = FALSE)) checked @endif --}}
                                        Dashboard
                                        
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Items" id="uitems">
                                    <label class="form-check-label" for="uitems">
                                        Items
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Deliveries" id="uDeliveries">
                                    <label class="form-check-label" for="uDeliveries">
                                        Deliveries
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="RIS" id="uRIS">
                                    <label class="form-check-label" for="uRIS">
                                        RIS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Offices" id="uOffices">
                                    <label class="form-check-label" for="uOffices">
                                        Offices
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Units" id="uUnits">
                                    <label class="form-check-label" for="uUnits">
                                        Units
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Classifications" id="uClassifications">
                                    <label class="form-check-label" for="uClassifications">
                                        Classifications
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Articles" id="uArticles">
                                    <label class="form-check-label" for="uArticles">
                                        Articles
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Suppliers" id="uSuppliers">
                                    <label class="form-check-label" for="uSuppliers">
                                        Suppliers
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Signatories" id="uSignatories">
                                    <label class="form-check-label" for="uSignatories">
                                        Signatories
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Price Lists" id="uprice">
                                    <label class="form-check-label" for="uprice">
                                        Price List
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Accounts" id="uAccounts">
                                    <label class="form-check-label" for="uAccounts">
                                        Accounts
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="SAccess" value="Activities" id="uActivities">
                                    <label class="form-check-label" for="uActivities">
                                        Activities
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        
                        <button type="submit" class="btn btn-sm btn-primary">Save Changes</button><br><br>

                        <button type="submit" class="btn btn-sm btn-danger"
                                                data-toggle="popover"
                                                data-trigger="hover"
                                                data-placement="right"
                                                data-content="Computer generated password will be sent through registered email. Password be changed immediately."
                                                wire:click="resetPassword()">Reset Password</button>
                    </form>
                </div>
            </div>
            </div>
        </div>


    </div>
</div>


@push('scripts')
    <script>
        window.addEventListener('close-modal' , event => {
            $('#newUser').modal('hide');
            $('#editUser').modal('hide');
        });

        window.addEventListener('show-edit-user-modal' , event => {
            $('#editUser').modal('show');
        });
        window.addEventListener('show-new-user-modal' , event => {
            $('#newUser').modal('show');
        });



        $(document).ready(function(){

            $(function () {
  $('[data-toggle="popover"]').popover()
})

            $('#showPassword').on('change', function(){
                $('#password').attr('type',$('#showPassword').prop('checked')==true?"text":"password"); 
                $('#password_confirmation').attr('type',$('#showPassword').prop('checked')==true?"text":"password"); 
            });

            $('#ushowPassword').on('change', function(){
                $('#upassword').attr('type',$('#ushowPassword').prop('checked')==true?"text":"password"); 
                $('#upassword_confirmation').attr('type',$('#ushowPassword').prop('checked')==true?"text":"password"); 
            });
        });



    </script>
@endpush
  