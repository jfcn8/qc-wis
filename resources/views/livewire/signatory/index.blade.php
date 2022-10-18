{{-- <h1 class="h3 mb-2 text-gray-800">signatory</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Signatories</h6>
        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newsignatory">New</button>
        @endif
        @if (in_array('Generate', $permissions))
            <button class="btn btn-sm btn-primary mt-1 float-right" wire:click="export()">Export</button>
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
                        <th>Signatory</th>
                        <th>Designation</th>
                        <th>MISM Noting</th>
                        <th>MISM Certifying</th>
                        <th>MISM Approving</th>
                        <th>SSMI Noting</th>
                        <th>SSMI Certifying</th>
                        <th>SSMI Approving</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($signatories->count() > 0)
                        @foreach ($signatories as $signatory)
                            <tr>
                                <td>{{ $signatory->name }}</td>
                                <td><?php echo nl2br($signatory->designation) ?></td>
                                <td>{{ ($signatory->mism_noting == 1 ? "Yes" : "") }}</td>
                                <td>{{ ($signatory->mism_certified == 1 ? "Yes" : "") }}</td>
                                <td>{{ ($signatory->mism_approved == 1 ? "Yes" : "") }}</td>
                                <td>{{ ($signatory->ssmi_noting == 1 ? "Yes" : "") }}</td>
                                <td>{{ ($signatory->ssmi_certifying == 1 ? "Yes" : "") }}</td>
                                <td>{{ ($signatory->ssmi_approving == 1 ? "Yes" : "") }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info"  wire:click="viewSignatoryDetails({{ $signatory->signatory_id }})">View</button>
                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getSignatory({{ $signatory->signatory_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $signatory->signatory_id }})">Delete</button>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="9">
                            <div class="not-found">
                                <img src="{{ asset('img/no-record-found.gif') }}" alt="">
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                </tbody>
            </table>
            {{ $signatories->links() }}
        </div>
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newsignatory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Signatory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveSignatory">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" wire:model="name" class="form-control" id="name" autocomplete="off" placeholder="Signatory">
                                @error('name')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <textarea wire:model="designation" id="designation" cols="30" rows="3" class="form-control" ></textarea>
                                @error('designation')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="mism_noting">MISM Noting</label>
                                <input type="checkbox" wire:model="mism_noting" class="form-control" id="mism_noting">
                            </div>

                            <div class="form-group">
                                <label for="mism_certified">MISM Certifier</label>
                                <input type="checkbox" wire:model="mism_certified" class="form-control" id="mism_certified">
                            </div>

                            <div class="form-group">
                                <label for="mism_approved">MISM Approver</label>
                                <input type="checkbox" wire:model="mism_approved" class="form-control" id="mism_approved">
                            </div>

                            <div class="form-group">
                                <label for="ssmi_noting">SSMI Noting</label>
                                <input type="checkbox" wire:model="ssmi_noting" class="form-control" id="ssmi_noting">
                            </div>

                            <div class="form-group">
                                <label for="ssmi_certifying">SSMI Certifier</label>
                                <input type="checkbox" wire:model="ssmi_certifying" class="form-control" id="ssmi_certifying">
                            </div>

                            <div class="form-group">
                                <label for="ssmi_approving">SSMI Approver</label>
                                <input type="checkbox" wire:model="ssmi_approving" class="form-control" id="ssmi_approving">
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="editsignatory" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Signatory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateSignatory">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" wire:model="name" class="form-control" id="name" autocomplete="off" placeholder="Signatory">
                                @error('name')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <textarea wire:model="designation" id="designation" cols="30" rows="3" class="form-control" ></textarea>
                                @error('designation')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="mism_noting">MISM Noting</label>
                                <input type="checkbox" wire:model="mism_noting" class="form-control" id="mism_noting">
                            </div>

                            <div class="form-group">
                                <label for="mism_certified">MISM Certifier</label>
                                <input type="checkbox" wire:model="mism_certified" class="form-control" id="mism_certified">
                            </div>

                            <div class="form-group">
                                <label for="mism_approved">MISM Approver</label>
                                <input type="checkbox" wire:model="mism_approved" class="form-control" id="mism_approved">
                            </div>

                            <div class="form-group">
                                <label for="ssmi_noting">SSMI Noting</label>
                                <input type="checkbox" wire:model="ssmi_noting" class="form-control" id="ssmi_noting">
                            </div>

                            <div class="form-group">
                                <label for="ssmi_certifying">SSMI Certifier</label>
                                <input type="checkbox" wire:model="ssmi_certifying" class="form-control" id="ssmi_certifying">
                            </div>

                            <div class="form-group">
                                <label for="ssmi_approving">SSMI Approver</label>
                                <input type="checkbox" wire:model="ssmi_approving" class="form-control" id="ssmi_approving">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deleteSignatory" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Signatory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteSignatory()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="viewSignatory" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Signatory</h5>
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
                                        <td>{{ $name_ }} </td>
                                    </tr>
                                    <tr>
                                        <th>Designation</th>
                                        <td>{{ $designation_ }} </td>
                                    </tr>

                                    <tr>
                                        <th>MISM Noting</th>
                                        <td>{{ ($mism_noting_ == 1 ? "Yes" : "")}} </td>
                                    </tr>

                                    <tr>
                                        <th>MISM Certifier</th>
                                        <td>{{ ($mism_certified_ == 1 ? "Yes" : "")}} </td>
                                    </tr>
                                    <tr>
                                        <th>MISM Approver</th>
                                        <td>{{ ($mism_approved_ == 1 ? "Yes" : "")}} </td>
                                    </tr>
                                    <tr>
                                        <th>SSMI Noting</th>
                                        <td>{{ ($ssmi_noting_ == 1 ? "Yes" : "")}} </td>
                                    </tr>
                                    <tr>
                                        <th>SSMI Certifier</th>
                                        <td>{{ ($ssmi_certifying_ == 1 ? "Yes" : "")}} </td>
                                    </tr>
                                    <tr>
                                        <th>SSMI Approver</th>
                                        <td>{{ ($ssmi_approving_ == 1 ? "Yes" : "") }} </td>
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
            $('#newsignatory').modal('hide');
            $('#editsignatory').modal('hide');
            $('#deleteSignatory').modal('hide');
            $('#viewSignatory').modal('hide');
        });

        window.addEventListener('show-edit-signatory-modal' , event => {
            $('#editsignatory').modal('show');
        });

        window.addEventListener('show-delete-signatory-modal' , event => {
            $('#deleteSignatory').modal('show');
        });

        window.addEventListener('show-signatory-modal' , event => {
            $('#viewSignatory').modal('show');
        });
    </script>
@endpush
  
 