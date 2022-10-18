{{-- <h1 class="h3 mb-2 text-gray-800">Office</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">DBM/GSD Pricelist</h6>

        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newDBM">New</button>
        @endif
        

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
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($dbms->count() > 0)
                        @foreach ($dbms as $dbm)
                            <tr>
                                <td>{{ $dbm->remarks }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-info" target="_blank" href="{{ asset('storage/files/' . $dbm->filename) }} ">View</a>
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $dbm->id }})">Delete</button>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="2">
                            <div class="not-found">
                                <img src="{{ asset('img/no-record-found.gif') }}" alt="">
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                </tbody>
            </table>
            {{ $dbms->links() }}
        </div>
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newDBM" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New DBM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveDBM" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="filename">File</label>
                                <input type="file" wire:model="filename" class="form-control" id="filename" required >
                                @error('filename')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <input type="text" wire:model="remarks" class="form-control" id="remarks" autocomplete="off" required>
                                @error('remarks')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>


            <div wire:ignore.self class="modal fade" id="deleteDBM" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete DBM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteDBM()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('close-modal' , event => {
            $('#newDBM').modal('hide');
            $('#deleteDBM').modal('hide');
        });


        window.addEventListener('show-delete-dbm-modal' , event => {
            $('#deleteDBM').modal('show');
        });


    </script>
@endpush
  
 