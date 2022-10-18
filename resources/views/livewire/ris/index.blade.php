{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}


<div class="card shadow mb-4">    
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Requisition and Issue Slip</h6>
    
        @if (in_array('Add', $permissions))
            <a class="btn btn-sm btn-outline-primary mt-1" href="{{ route('ris.create') }}">New</a>
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
                        <th>Date Request</th>
                        <th>Purpose</th>
                        <th>Office</th>
                        <th>GSO</th>
                        <th>BUDGET</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($ris->count() > 0)
                        @foreach ($ris as $ris_)
                            <tr>
                                <td>{{ date("M. d, Y", strtotime($ris_->date_request))  }}</td>
                                <td>{{ $ris_->purpose }}</td>
                                <td>{{ $ris_->Office->office }}</td>
                                <td class="{{ ($ris_->gso == 1) ? "bg-primary text-white" : ($ris_->gso == 2 ? "bg-danger text-white" : "bg-success text-white" )}}">{{ ($ris_->gso == 1) ? "Approve" : ($ris_->gso == 2 ? "Denied" : "Pending")  }}</td>
                                <td class="{{ ($ris_->budget == 1) ? "bg-primary text-white" : ($ris_->budget == 2 ? "bg-danger text-white" : "bg-success text-white" )}}">{{ ($ris_->budget == 1) ? "Approve" : ($ris_->budget == 2 ? "Denied" : "Pending")  }}</td>
                                <td>

                                    @if (in_array('Approve/Disapprove RIS', $permissions))
                                        <button class="btn btn-sm btn-primary" wire:click="approveConfirmation({{ $ris_->ris_id }})">Approve</button>
                                        <button class="btn btn-sm btn-danger" wire:click="denyConfirmation({{ $ris_->ris_id }})">Deny</button>
                                    @endif

                                    |

                                    @if (in_array('Edit', $permissions))
                                        <a class="btn btn-sm btn-outline-info"  href="{{ URL('ris/item/' .$ris_->ris_id) }}">View/Update</a>
                                    @endif
                                    
                                    @if (in_array('Generate', $permissions))
                                        <a class="btn btn-sm btn-outline-primary" target="_blank"  href="{{ URL('ris/item/print/' .$ris_->ris_id) }}">Print</a>
                                    @endif
                                    
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $ris_->ris_id }})">Delete</button>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No RIS Record</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
            {{ $ris->links() }}
        </div>

        <div wire:ignore.self class="modal fade" id="deleteRIS" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Delete RIS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                   <h5>Are you sure you want to delete this record?</h5>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="deleteRIS()">Yes! Delete</button>
                </div>
            </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="denyRIS" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Deny RIS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                   <h5>Are you sure you want to deny this RIS?</h5>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="denyRIS()">Yes! Deny</button>
                </div>
            </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="approveRIS" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Approve RIS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                   <h5>Are you sure you want to Approve this RIS?</h5>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="approveRIS()">Yes! Approve</button>
                </div>
            </div>
            </div>
        </div>

    </div>
</div>



@push('scripts')
    <script>
        window.addEventListener('close-modal' , event => {
            $('#deleteRIS').modal('hide');
            $('#denyRIS').modal('hide');
            $('#approveRIS').modal('hide');
        });

        window.addEventListener('show-delete-ris-modal' , event => {
            $('#deleteRIS').modal('show');
        });
        window.addEventListener('show-deny-ris-modal' , event => {
            $('#denyRIS').modal('show');
        });
        window.addEventListener('show-approve-ris-modal' , event => {
            $('#approveRIS').modal('show');
        });
    </script>
@endpush
  
 
 