{{-- <h1 class="h3 mb-2 text-gray-800">Unit</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Unit</h6>

        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newUnit">New</button>
        @endif

        @if (in_array('Generate', $permissions))
            <button class="btn btn-sm btn-primary mt-1 float-right" wire:click="export()">Export</button>
        @endif

    </div>
    <div class="card-body">
        @include('livewire.layouts.loading')
        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" wire:model="searchKey" class="form-control" placeholder="Unit">
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
                        <th>Mnemonic</th>
                        <th>Unit Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($units->count() > 0)
                        @foreach ($units as $Unit)
                            <tr>
                                <td>{{ $Unit->mnemonic }}</td>
                                <td>{{ $Unit->unit }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info"  wire:click="viewUnitDetails({{ $Unit->unit_id }})">View</button>
                                    
                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getUnit({{ $Unit->unit_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $Unit->unit_id }})">Delete</button>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">
                                <div class="not-found">
                                    <img src="{{ asset('img/no-record-found.gif') }}" alt="">
                                </div>
                            </td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
            {{ $units->links() }}
        </div>
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newUnit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveUnit">

                            <div class="form-group">
                                <label for="mnemonic">Mnemonic</label>
                                <input type="text" wire:model="mnemonic" class="form-control" id="mnemonic" autocomplete="off" placeholder="PC">
                                @error('mnemonic')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <input type="text" wire:model="unit" class="form-control" id="unit" autocomplete="off" placeholder="Piece">
                                @error('unit')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="editUnit" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateUnit">

                            <div class="form-group">
                                <label for="mnemonic">Mnemonic</label>
                                <input type="text" wire:model="mnemonic" class="form-control" id="mnemonic" autocomplete="off" placeholder="PC">
                                @error('mnemonic')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <input type="text" wire:model="unit" class="form-control" id="unit" autocomplete="off" placeholder="Pcs/Bag/">
                                @error('unit')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deleteUnit" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteUnit()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="viewUnit" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <th>Mnemonic</th>
                                        <td>{{ $mnemonic_ }} </td>
                                    </tr>

                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $unit_name }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>


        
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('close-modal' , event => {
            $('#newUnit').modal('hide');
            $('#editUnit').modal('hide');
            $('#deleteUnit').modal('hide');
            $('#viewUnit').modal('hide');
        });

        window.addEventListener('show-edit-unit-modal' , event => {
            $('#editUnit').modal('show');
        });

        window.addEventListener('show-delete-unit-modal' , event => {
            $('#deleteUnit').modal('show');
        });

        window.addEventListener('show-unit-modal' , event => {
            $('#viewUnit').modal('show');
        });
    </script>
@endpush
  
 