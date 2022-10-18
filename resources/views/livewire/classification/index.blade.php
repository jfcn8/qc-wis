{{-- <h1 class="h3 mb-2 text-gray-800">classification</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Classification</h6>
        

        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newClassification">New</button>
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
            <input type="text" wire:model="searchKey" class="form-control" placeholder="Classification">
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
                        <th>Classification</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($classifications->count() > 0)
                        @foreach ($classifications as $classification)
                            <tr>
                                <td>{{ $classification->classification }}</td>
                                <td>
                                    
                                    <button class="btn btn-sm btn-outline-info"  wire:click="viewClassificationDetails({{ $classification->classification_id }})">View</button>
                                    
                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getClassification({{ $classification->classification_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $classification->classification_id }})">Delete</button>
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
            {{ $classifications->links() }}
        </div>
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newClassification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New classification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveClassification">
                            <div class="form-group">
                                <label for="classification">Classification</label>
                                <input type="text" wire:model="classification" class="form-control" id="classification" autocomplete="off" placeholder="Item Classification">
                                @error('classification')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="editClassification" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update classification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateClassification">
                            <div class="form-group">
                                <label for="classification_id">Classification</label>
                                <input type="text" wire:model="classification" class="form-control" id="classification" autocomplete="off" placeholder="Item Classification">
                                @error('classification')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deleteClassification" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Classification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteClassification()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="viewClassification" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Classification</h5>
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
                                        <td>{{ $classification_name }} </td>
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
            $('#newClassification').modal('hide');
            $('#editClassification').modal('hide');
            $('#deleteClassification').modal('hide');
            $('#viewClassification').modal('hide');
        });

        window.addEventListener('show-edit-classification-modal' , event => {
            $('#editClassification').modal('show');
        });

        window.addEventListener('show-delete-classification-modal' , event => {
            $('#deleteClassification').modal('show');
        });

        window.addEventListener('show-classification-modal' , event => {
            $('#viewClassification').modal('show');
        });
    </script>
@endpush
  
 