{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Requisition and Issue Slip</h6>
        
        {{-- <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newItem">New</button> --}}
        <a class="btn btn-sm btn-outline-primary mt-1" href="{{ route('ris') }}">Back</a>
        <button class="btn btn-sm btn-danger mt-1 float-right" wire:click="resetConfirmation({{ Auth()->User()->id }})">Reset</button>
    </div>
    <div class="card-body">
        @include('livewire.layouts.loading')
        

        <div>
            <form wire:submit.prevent="addItem">
                <div class="form-row">
                    <div class="form-group col-md-4 col-sm-12 col-xs-12 col-lg-4 col-xl-3">
                        <label for="date_request">Date Request</label>
                        <input wire:model="date_request" type="date" class="form-control" id="date_request">
                        @error('date_request')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-6 col-sm-12 col-xs-12 col-lg-4 col-xl-3">
                        <label for="office">Office</label>&nbsp;<button class="btn btn-sm btn-primary" style="font-size: .675rem !important;"  data-toggle="modal" data-target="#newOffice">New Office</button>
                        <div wire:ignore>
                            <select wire:model="office" id="office" class="form-control office">
                                <option value="">Select Office</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->office_id }}">{{ $office->office}}</option>
                                    @endforeach
                            </select>
                        </div>
                        @error('office')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                </div>
        
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="purpose">Purpose</label>
                        <input wire:model="purpose" type="text" class="form-control" id="purpose" autocomplete="off">
                        @error('purpose')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="item">Items</label>
                        <div wire:ignore>
                            <select wire:model="item" id="item" class="form-control item">
                                <option value="">Select Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->item_id }}">{{ $item->article . ', ' . $item->description . ' | ' . $item->stock_number . ' | '  . number_format($item->stock) . '-'  . $item->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('item')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="quantity">Quantity</label>
                        <input wire:model="quantity" type="number" class="form-control" id="quantity" placeholder="Quantity" autocomplete="off">
                        @error('quantity')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                  </div>
    
                <button type="submit" class="btn btn-success">Add Item</button>
            </form>
        </div>
        
        <hr>

        <div>
            @if (session()->has('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('danger'))
                <div class="alert alert-danger" role="alert">
                    {{ nl2br(session('danger')) }}
                </div>
            @endif
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($riss->count() > 0)
                        @foreach ($riss as $ris)
                            <tr>
                                <td>{{  $ris->Item->Article->article . ', '. $ris->Item->description . ' - ' . $ris->Item->Unit->unit }}</td>
                                <td>{{ $ris->quantity }} </td>
                                <td>
                                    {{-- <button class="btn btn-sm btn-outline-primary" wire:click="getStock({{ $ris->temp_ris_id }})">Edit</button> --}}
                                    <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $ris->temp_ris_id }})">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="3">No Item Record</td>
                        </tr>
                    @endif                    
                </tbody>
            </table>
        </div>

        
        <button class="float-right btn btn-primary" wire:click="saveConfirmation({{ Auth()->user()->id }})">Save RIS</button>

        <div wire:ignore.self class="modal fade" id="editTempRis" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit Item Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label for="quantity">Quantity</label>
                        <input wire:model="quantity" type="number" class="form-control" id="quantity" placeholder="Quantity" autocomplete="off">
                        @error('quantity')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="saveTempStock()">Save changes</button>
                </div>
            </div>
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="deleteTempRis" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Delete Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                   <h5>Are you sure you want to delete this item?</h5>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="deleteTempItem()">Yes! Delete</button>
                </div>
            </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="saveRISItem" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Requisition and Issue Slip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                   <h5>Are you sure you want to save this Requisition and Issue Slip?</h5>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-danger pull-left" wire:click="cancel()">No, Cancel!</button>
                    <button class="btn btn-sm btn-outline-primary" wire:click="saveRIS()">Yes, Save!</button>
                </div>
            </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="resetRISItem" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Requisition and Issue Slip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                   <h5>Are you sure you want to reset this Requisition and Issue Slip?</h5>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-danger pull-left" wire:click="cancel()">No, Cancel!</button>
                    <button class="btn btn-sm btn-outline-primary" wire:click="resetRIS()">Yes, Save!</button>
                </div>
            </div>
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="newOffice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Office</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveOffice">
                        <div class="form-group">
                            <label for="newOffice">Office</label>
                            <input type="text" wire:model="newOffice" class="form-control" id="newOffice" autocomplete="off" placeholder="New Office" required>
                            @error('newOffice')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
            </div>
        </div>

    </div>
</div>


    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.item').select2();
            $('.item').on('change', function (e) {
                var data = $('.item').select2("val");
                @this.set('item', data);
            });
            
            $('.office').select2();
            $('.office').on('change', function (e) {
                var data = $('.office').select2("val");
                @this.set('office', data);
            });
        });

        window.addEventListener('close-modal' , event => {
            $('#deleteTempRis').modal('hide');
            $('#saveRISItem').modal('hide');
            $('#resetRISItem').modal('hide');
            $('#editTempRis').modal('hide');
            $('#newOffice').modal('hide');
        });

        window.addEventListener('show-save-modal' , event => {
            $('#saveRISItem').modal('show');
        });

        window.addEventListener('show-edit-temp-modal' , event => {
            $('#editTempRis').modal('show');
        });

        window.addEventListener('show-delete-tempris-modal' , event => {
            $('#deleteTempRis').modal('show');
        });

        window.addEventListener('show-reset-tempris-modal' , event => {
            $('#resetRISItem').modal('show');
        });
    </script>   
    @endpush