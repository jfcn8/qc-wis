{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Item</h6>
        
        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newItem">New</button>
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

        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" wire:model="searchKey" class="form-control" placeholder="Article | Description | Stock Number">
        </div>
        


        


        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Description</th>
                        <th>Stock Number</th>
                        <th>Unit</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($items->count() > 0)
                    @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->article }} </td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->stock_number }}</td>
                                
                                <td>{{ $item->unit }}</td>
                                <td>{{ number_format($item->stock) }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-success"  href="{{ URL('references/' .$item->item_id) }}">Reference</a>
                                    <button class="btn btn-sm btn-outline-info"  wire:click="viewItem({{ $item->item_id }})">View</button>

                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getItem({{ $item->item_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $item->item_id }})">Delete</button>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">
                                <div class="not-found">
                                    <img src="{{ asset('img/no-record-found.gif') }}" alt="">
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{ $items->links() }}
        </div>
        
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="cancel()" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveItem">

                            <div class="form-group">
                                <label for="initial_date">Date</label>
                                <input type="date" wire:model="initial_date" class="form-control" id="initial_date" >
                                @error('initial_date')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="article_id">Article</label>
                                    <select class="form-control" wire:model="article_id">
                                        <option value="">Select Article</option>
                                        @foreach ($articles as $article)
                                            <option value="{{ $article->article_id }}">{{ $article->article }}</option>
                                        @endforeach
                                    </select>
                                    @error('article_id')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-primary" wire:click="newArticle()">New Article</button>
                                    </div>
                                    
                                </div>
                                
                            </div>

                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" wire:model="description" class="form-control" id="description" autocomplete="off" placeholder="Item Description">
                                @error('description')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reference">Reference</label>
                                <input type="text" wire:model="reference" class="form-control" id="reference" autocomplete="off" placeholder="Reference">
                                @error('reference')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="stock_number">Stock Number</label>
                                <input type="text" wire:model="stock_number" class="form-control" id="stock_number" autocomplete="off" placeholder="Item Stock Number">
                                @error('stock_number')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-9">
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
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-primary" wire:click="newUnit()">&nbsp; New Unit &nbsp;</button>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            

                            <div class="form-group">
                                <label for="price">Value</label>
                                <input type="text" wire:model="price" class="form-control" id="price" autocomplete="off" placeholder="Price">
                                @error('price')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="form-group">
                                <label for="stock">Quantity</label>
                                <input type="text" wire:model="stock" class="form-control" id="stock" autocomplete="off" placeholder="Stock">
                                @error('stock')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" wire:model="delivery" id="delivery">
                                    <label class="form-check-label" for="delivery">
                                        Check if this item is delivery.
                                    </label>
                                  </div>
                            </div>
                            

                            

                            <button class="btn btn-sm btn-outline-info" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateItem">

                            <div class="form-group">
                                <label for="article_id">Article</label>
                                <select class="form-control" wire:model="article_id">
                                    <option value="">Select Article</option>
                                    @foreach ($articles as $article)
                                        <option value="{{ $article->article_id }}">{{ $article->article }}</option>
                                    @endforeach
                                </select>
                                @error('article_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" wire:model="description" class="form-control" id="description" autocomplete="off" placeholder="Item Description">
                                @error('description')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="stock_number">Stock Number</label>
                                <input type="text" wire:model="stock_number" class="form-control" id="stock_number" autocomplete="off" placeholder="Item Stock Number">
                                @error('stock_number')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

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

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

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
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteItem()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="newArticle" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">New Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       
                        <div class="form-group">
                            <label for="classification">Classification</label>
                            <select class="form-control" wire:model="classification">
                                <option value="">Select Classification</option>
                                @if ($classifications)
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->classification_id }}">{{ $classification->classification }}</option>
                                @endforeach
                                @endif
                            </select>
                            @error('classification')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="article">Article</label>
                            <input type="text" wire:model="article" class="form-control" id="article" autocomplete="off" placeholder="Item Article">
                            @error('article')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="saveArticle()">Save Article</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="newUnit" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">New Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
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
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="saveUnit()">Save Unit</button>
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
                                        <th>Description</th>
                                        <td>{{ $description_name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Article</th>
                                        <td>{{ $article_name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Stock Number</th>
                                        <td>{{ $stock_number_name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Unit</th>
                                        <td>{{ $unit_name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Stock</th>
                                        <td>{{ number_format($stock_) }} </td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td>{{ $price_ }} </td>
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
            $('#newItem').modal('hide');
            $('#editItem').modal('hide');
            $('#deleteItem').modal('hide');
            $('#viewItem').modal('hide');
            $('#newArticle').modal('hide');
        });

        window.addEventListener('close-item' , event => {
            $('#newItem').modal('hide');
            $('#editItem').modal('hide');
            $('#deleteItem').modal('hide');
            $('#viewItem').modal('hide');
        });
        
        window.addEventListener('close-item-unit' , event => {
            $('#newItem').modal('hide');
            $('#editItem').modal('hide');
            $('#deleteItem').modal('hide');
            $('#viewItem').modal('hide');
            $('#newArticle').modal('hide');
        });

        window.addEventListener('show-new-item-modal' , event => {
            $('#newItem').modal('show');
        });

        window.addEventListener('show-edit-item-modal' , event => {
            $('#editItem').modal('show');
        });

        window.addEventListener('show-delete-item-modal' , event => {
            $('#deleteItem').modal('show');
        });

        window.addEventListener('show-item-modal' , event => {
            $('#viewItem').modal('show');
        });

        window.addEventListener('new-article-modal' , event => {
            $('#newArticle').modal('show');
        });

        window.addEventListener('new-unit-modal' , event => {
            $('#newUnit').modal('show');
        });
    </script>
@endpush
  
 