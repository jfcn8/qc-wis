{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Requisition and Issue Slip</h6>
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
            <form wire:submit.prevent="updateRIS">
                <div class="form-row">
                    <div class="form-group col-md-6 col-sm-12 col-xs-12 col-lg-4 col-xl-3">
                        <label for="date_request">Date Request</label>
                        <input wire:model.defer="date_request" type="date" class="form-control" id="date_request">
                        @error('date_request')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-6 col-sm-12 col-xs-12 col-lg-4 col-xl-3">
                        <label for="office">Office</label>
                        <div wire:ignore>
                            <select wire:model.defer="office" id="office" class="form-control office">
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
                        <input wire:model.defer="purpose" type="text" class="form-control" id="purpose" autocomplete="off">
                        @error('purpose')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if (in_array('Edit', $permissions))
                    <button type="submit" class="btn btn-outline-info mb-2">Update</button>
                @endif
                
            </form>
        </div>
        
        <div>
            @if (session()->has('item'))
                <div class="alert alert-success" role="alert">
                    {{ session('item') }}
                </div>
            @endif
        </div>


        <div class="mb-3">
            <form wire:submit.prevent="addItem">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="item">Items</label>
                        <div wire:ignore>
                            <select wire:model="item" id="item" class="form-control item">
                                <option value="">Select Item</option>
                                @foreach ($items as $item)
                                <option value="{{ $item->item_id }}">{{ $item->article . ', ' . $item->description . ' | '  . number_format($item->stock) . '-'  . $item->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('item')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-5">
                        <label for="quantity">Quantity</label>
                        <input wire:model="quantity" type="number" class="form-control" id="quantity" placeholder="Quantity" autocomplete="off">
                        @error('quantity')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                  </div>

                @if (in_array('Add', $permissions))
                    <button type="submit" class="btn btn-success">Add Item</button>
                @endif
                
            </form>
        </div>


        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($itemLogs->count() > 0)
                        @foreach ($itemLogs as $itemLog)
                            <tr>
                                <td>{{ $itemLog->Reference->reference }}</td>
                                <td>{{ $itemLog->Reference->RefItem->Article->article . ', ' . $itemLog->Reference->RefItem->description . ' - ' .  $itemLog->Reference->RefItem->Unit->unit }}</td>
                                <td>{{ number_format($itemLog->quantity) }} </td>
                                <td>
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $itemLog->item_log_id }})">Delete</button>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Item Record</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>
  

            {{-- <div wire:ignore.self class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Quantity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateItem">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" wire:model="quantity" class="form-control" id="quantity">
                                @error('quantity')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div> --}}

            <div wire:ignore.self class="modal fade" id="deleteItem" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <h5>{{ $deleteMessage }}</h5>
                        <br>
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteItem()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>



        
    </div>
</div>



@push('scripts')
    <script>

        $(document).ready(function () {
            $('.item').select2();
            $('.item').on('change', function (e) {
                var data = $('.item').select2("val");
                @this.set('item', data);
            });
        });
        window.addEventListener('close-modal' , event => {
            $('#deleteItem').modal('hide');
            $('#editItem').modal('hide');
        });

        window.addEventListener('show-delete-item-modal' , event => {
            $('#deleteItem').modal('show');
        });
        window.addEventListener('show-edit-item-modal' , event => {
            $('#editItem').modal('show');
        });

    </script>
@endpush
  
 