{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Item Stock Card</h6>
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

        <form wire:submit.prevent="generateItems">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="date_from">Date From</label>
                        <input type="date" wire:model.defer="date_from" class="form-control" id="date_from" required>
                        @error('date_from')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="date_to">Date To</label>
                        <input type="date" wire:model.defer="date_to" class="form-control" id="date_to" required>
                        @error('date_to')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Proceed</button>
        </form>

        @if (count($items) > 0)
            <div class="table-responsive mt-4">
                <h3>Available items on selected date</h3>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->article . ' - ' . $item->description . ' - ' . $item->unit }}</td>
                                <td><a target="_blank" href="{{ url('items/stock-card/' . $item->item_id) }}" class="btn btn-primary">Print</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @if ($proceed > 0) 
                <div class="alert alert-danger mt-5" role="alert">
                    No Stock Card on selected date.
                </div>
            @endif
        @endif
        
        
        
    </div>
</div>


