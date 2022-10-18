{{-- <h1 class="h3 mb-2 text-gray-800">Delivery</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Delivery</h6>
        
        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newDelivery">New</button>
        @endif
        
        
        @if (in_array('Generate', $permissions))
            <button class="btn btn-sm btn-primary mt-1 float-right" wire:click="export()">Export</button>
        @endif
    </div>
    <div class="card-body">
        @include('livewire.layouts.loading')
        {{-- <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" wire:model="searchKey" class="form-control" placeholder="Delivery">
        </div> --}}


        <div>
            @if (session()->has('message'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="searchDateFrom">Date From</label>
                <input type="date" wire:model="searchDateFrom" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="searchDateTo">Date To</label>
                <input type="date" wire:model="searchDateTo" class="form-control">
            </div>
            <div class="col-md-1"><br>
                <button class="btn mt-2 btn-outline-success btn-sm" wire:click="resetSearch()">Reset</button>
            </div>
            
        </div>
        
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Item</th>
                        <th>Value</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($deliveries->count() > 0)
                        @foreach ($deliveries as $delivery)
                            <tr>
                                <td>{{ date("M. d, Y", strtotime($delivery->delivery_date))  }}</td>
                                <td>{{ $delivery->reference }}</td>
                                
                                <td>{{ $delivery->article . ' - '.  $delivery->description . ' - ' . $delivery->stock_number . ' | ' . $delivery->unit }}</td>
                                <td>{{ '₱' . number_format($delivery->price ,2)}}</td>
                                <td>{{ number_format($delivery->stock) }}</td>
                                <td>{{ '₱' . number_format($delivery->stock * $delivery->price, 2) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info"  wire:click="viewDelivery({{ $delivery->delivery_id }})">View</button>
                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getDelivery({{ $delivery->delivery_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $delivery->delivery_id }})">Delete</button>
                                    @endif
                                    
                                    
                                    
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="8">No Delivery Record</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
            {{ $deliveries->links() }}
        </div>
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newDelivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Delivery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveDelivery">
                            <div class="form-group">
                                <label for="delivery_date">Delivery Date</label>
                                <input type="date" wire:model="delivery_date" class="form-control" id="delivery_date" autocomplete="off" >
                                @error('delivery_date')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="article">Article</label>
                                <select  wire:model="article" id="article" class="form-control">
                                    <option value="">Select Article</option>
                                    @foreach ($articles as $article)
                                        <option value="{{ $article->article_id }}">{{ $article->article }}</option>
                                    @endforeach
                                </select>
                                @error('article')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- {{ $article_id }} --}}

                            @if(!empty($article))
                            <div class="form-group">
                                <label for="description">Description</label>
                                <select  wire:model="description" id="description" class="form-control">
                                    <option value="">Select Description</option>
                                    @if (!is_null($items))
                                        @foreach ($items as $item)
                                            <option value="{{ $item->item_id }}">{{ $item->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="badge badge-pill badge-primary">Note</span>
                                If no item description appears, <a class="badge badge-primary" href="{{ route('items') }}">Click here</a> to add the item.
                                @error('description')<br>
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="reference">Reference</label>
                                <input type="text" wire:model="reference" class="form-control" id="reference" autocomplete="off" placeholder="Reference">
                                @error('reference')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            @if(!empty($stock_number))
                                <div class="form-group">
                                    <label for="stock_number">Stock Number</label>
                                    <input type="text" wire:model="stock_number" class="form-control" id="stock_number" autocomplete="off" placeholder="Stock Number">
                                    @error('stock_number')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            @if(!empty($unit_id))
                            <div class="form-group">
                                <label for="unit_id">Unit</label>
                                <select class="form-control" wire:model="unit_id">
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->unit_id }}">{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                            

                            
                            
                            <div class="form-group">
                                <label for="stock">Quantity</label>
                                <input type="text" wire:model="stock" class="form-control" id="stock" autocomplete="off" placeholder="Stock">
                                @error('stock')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="price">Value</label>
                                <input type="text" wire:model="price" class="form-control" id="price" autocomplete="off" placeholder="Unit Value">
                                @error('price')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" wire:model="supplier">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $item)
                                        <option value="{{ $item->supplier_id }}">{{ $item->supplier }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="display: none;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="initial" id="initial">
                                    <label class="form-check-label" for="initial">
                                      Check if the stock is for initial.
                                    </label>
                                  </div>
                            </div>
                            
                            
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="editDelivery" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Delivery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateDelivery">
                            <div class="form-group">
                                <label for="delivery_date">Delivery Date</label>
                                <input type="date" wire:model="delivery_date" class="form-control" id="delivery_date" autocomplete="off" >
                                @error('delivery_date')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" wire:model="stock" class="form-control" autocomplete="off" placeholder="Stock">
                                @error('stock')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" wire:model="supplier">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $item)
                                        <option value="{{ $item->supplier_id }}">{{ $item->supplier }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deleteDelivery" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Delivery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteDelivery()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="viewDelivery" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delivery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <th>Delivery Date</th>
                                        <td>{{ $delivery_date_ }} </td>
                                    </tr>

                                    <tr>
                                        <th>Item</th>
                                        <td>{{ $delivery_description_ }}</td>
                                    </tr>

                                    <tr>
                                        <th>Stock Number</th>
                                        <td>{{ $delivery_stock_number }}</td>
                                    </tr>

                                    <tr>
                                        <th>Unit</th>
                                        <td>{{ $delivery_unit }}</td>
                                    </tr>

                                    <tr>
                                        <th>Reference</th>
                                        <td>{{ $delivery_reference }}</td>
                                    </tr>

                                    <tr>
                                        <th>Stock</th>
                                        <td>{{ number_format($delivery_stock) }}</td>
                                    </tr>

                                    <tr>
                                        <th>Unit Value</th>
                                        <td>{{ '₱' . number_format($delivery_price, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th>Cost</th>
                                        <td>{{ '₱' . number_format($delivery_stock * $delivery_price, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th>Supplier</th>
                                        <td>{{ $delivery_supplier }}</td>
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
            $('#newDelivery').modal('hide');
            $('#editDelivery').modal('hide');
            $('#deleteDelivery').modal('hide');
            $('#viewDelivery').modal('hide');
        });

        window.addEventListener('show-edit-delivery-modal' , event => {
            $('#editDelivery').modal('show');
        });

        window.addEventListener('show-delete-delivery-modal' , event => {
            $('#deleteDelivery').modal('show');
        });

        window.addEventListener('show-delivery-modal' , event => {
            $('#viewDelivery').modal('show');
        });
    </script>
@endpush
  
 