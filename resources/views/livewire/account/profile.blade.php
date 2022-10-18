{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
    </div>
    <div class="card-body">
        @include('livewire.layouts.loading')
        

        <div>
            @if (session()->has('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <div>
            <form wire:submit.prevent="updateInformation">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Name</label>
                                <input wire:model="name" type="text" class="form-control" id="name" autocomplete="off" value="{{ $name }}">
                                @error('name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
        
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="position">Position</label>
                                <input wire:model="position" type="text" class="form-control" id="position" autocomplete="off" value="{{ $position }}">
                                @error('position')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="email">Email</label>
                                <label for="email" class="form-control">{{ $email }}</label>
                            </div>
                        </div>
        
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="Office">Office</label>
                                <label for="Office" class="form-control">{{ $office }}</label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="password">Password</label>
                                <input wire:model="password" type="password" class="form-control" id="password" autocomplete="off" >
                                @error('password')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" wire:model="password_confirmation" class="form-control" id="password_confirmation" autocomplete="off">
                                @error('password_confirmation')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox"id="showPassword">
                            <label class="form-check-label" for="showPassword">
                            Show Password
                            </label>
                        </div>

                    </div>
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-lg-6">
                                Permissions to:
                                <ol>
                                    @php
                                    $permissions_ = explode(',', $permissions);
                                    foreach ($permissions_ as $keyPermission => $Permissionvalue) {
                                        echo '<li>'.$Permissionvalue . '</li>';
                                    }
                                    @endphp
                                </ol>

                                Access to:
                                <ol>
                                    @php
                                    $access_ = explode(',', $access);
                                    foreach ($access_ as $keyAccess => $Accessvalue) {
                                        echo '<li>'.$Accessvalue . '</li>';
                                    }
                                    @endphp
                                </ol>
                            </div>
                        </div>
                    </div>

                    

                </div>

                <div class="alert alert-warning" role="alert">
                    Leave the password blank if you don't want to change it.<br>
                    You do not have the option to edit your email address, office, permissions and access on your account. <br>
                    You will need to contact your administrator if you wish to change your email addresses, office, permissions and access.
                </div>

                
                <button type="submit" class="btn btn-primary">Update Information</button>
            </form>
        </div>
        
    </div>
</div>


@push('scripts')
    <script>


        $(document).ready(function(){


            $('#showPassword').on('change', function(){
                $('#password').attr('type',$('#showPassword').prop('checked')==true?"text":"password"); 
                $('#password_confirmation').attr('type',$('#showPassword').prop('checked')==true?"text":"password"); 
            });

        });



    </script>
@endpush
  