{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}
<?php
    $access = explode(',', Auth()->user()->access);
    $permissions = explode(',', Auth()->user()->permissions);
?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">References</h6>
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

        <p><b>Article :</b> {{ $item->article->article }}<br>
        <b>Item:</b> {{ $item->description }}<br>
        <b>Stock Number :</b> {{ $item->stock_number }}<br>
        <b>Unit :</b> {{ $item->unit->unit }}
        </p>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($references->count() > 0)
                        @foreach ($references as $item)
                            <tr>
                                <td>{{ $item->reference }}</td>
                                <td>{{ number_format($item->stock) }} </td>
                                <td>{{ '₱'. number_format($item->price, 2) }}</td>
                                <td>
                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getReference({{ $item->reference_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $item->reference_id }})">Delete</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="4">
                            <div class="not-found">
                                <img src="{{ asset('img/no-record-found.gif') }}" alt="">
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
  

            <div wire:ignore.self class="modal fade" id="editReference" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Reference</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateReference">
                        
                            <div class="form-group">
                                <label for="reference">Reference</label>
                                <input type="text" wire:model="reference" class="form-control" id="reference" autocomplete="off" placeholder="Item Reference">
                                @error('reference')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" wire:model="price" class="form-control" id="price" autocomplete="off" placeholder="Reference Price">
                                @error('price')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                        
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deleteReference" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Reference</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteReference()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="viewItem" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <th>Reference</th>
                                        <td>{{ $reference_ }} </td>
                                    </tr>
                                    <tr>
                                        <th>Stock</th>
                                        <td>{{ $stock_ }} </td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td>{{ '₱'. number_format($price_, 2) }} </td>
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
            $('#editReference').modal('hide');
            $('#deleteReference').modal('hide');
        });

        window.addEventListener('show-edit-reference-modal' , event => {
            $('#editReference').modal('show');
        });

        window.addEventListener('show-delete-reference-modal' , event => {
            $('#deleteReference').modal('show');
        });

    </script>
@endpush
  
 