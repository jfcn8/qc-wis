{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Summary of Supplies and Materials Issued</h6>
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

        <form wire:submit.prevent="generateSSMI" target="_blank">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="date_from">Date From</label>
                        <input type="date" class="form-control" wire:model.defer="date_from">
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
                        <input type="date" class="form-control" wire:model.defer="date_to">
                        @error('date_to')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Proceed</button>
        </form>

        {{$message}}

    </div>
</div>


