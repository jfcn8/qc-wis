{{-- <h1 class="h3 mb-2 text-gray-800">Supplier</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Supplier</h6>
        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newSupplier">New</button>
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
            <input type="text" wire:model="searchKey" class="form-control" placeholder="Supplier">
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
                        <th>Supplier</th>
                        <th>Other Information</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($suppliers->count() > 0)
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->supplier }}</td>
                                <td>{{ $supplier->other_information }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info"  wire:click="viewSupplierDetails({{ $supplier->supplier_id }})">View</button>
                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getSupplier({{ $supplier->supplier_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $supplier->supplier_id }})">Delete</button>
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
            {{ $suppliers->links() }}
        </div>
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveSupplier">
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <input type="text" wire:model="supplier" class="form-control" id="supplier" autocomplete="off" placeholder="Supplier">
                                @error('supplier')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="other_information">Other Information</label>
                                <textarea wire:model="other_information" class="form-control" cols="30" rows="10"></textarea>
                                @error('other_information')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="editSupplier" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateSupplier">
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <input type="text" wire:model="supplier" class="form-control" id="supplier" autocomplete="off" placeholder="Supplier">
                                @error('supplier')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="other_information">Other Information</label>
                                <textarea wire:model="other_information" class="form-control" cols="30" rows="10"></textarea>
                                @error('other_information')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deleteSupplier" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteSupplier()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="viewSupplier" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $supplier_ }} </td>
                                    </tr>
                                    <tr>
                                        <th>Other Information</th>
                                        <td>{{ $other_information_ }} </td>
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
            $('#newSupplier').modal('hide');
            $('#editSupplier').modal('hide');
            $('#deleteSupplier').modal('hide');
            $('#viewSupplier').modal('hide');
        });

        window.addEventListener('show-edit-supplier-modal' , event => {
            $('#editSupplier').modal('show');
        });

        window.addEventListener('show-delete-supplier-modal' , event => {
            $('#deleteSupplier').modal('show');
        });

        window.addEventListener('show-supplier-modal' , event => {
            $('#viewSupplier').modal('show');
        });
    </script>
@endpush
  
 