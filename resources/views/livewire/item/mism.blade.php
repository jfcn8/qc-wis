{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Monthly Inventory of Supplies and Materials</h6>
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

        <form wire:submit.prevent="generateMISM" target="_blank">
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

            {{-- <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="selectedMonth">Month</label>
                        <select class="form-control" wire:model.defer="selectedMonth">
                            <option value="">Select Month</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{$month}}">{{ date("F", mktime(0, 0, 0, $month, 10)); }}</option>
                            @endfor
                        </select>
                        @error('selectedMonth')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Year</label>
                        <select wire:model.defer="selectedYear" class="form-control">
                            <option value="">Select Year</option>
                            @for($year = 2022; $year <= date('Y'); $year++)
                                <option value="{{ $year }}">{{$year}}</option>
                            @endfor
                        </select>
                        @error('selectedYear')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div> --}}
            <button type="submit" class="btn btn-primary">Proceed</button>
        </form>

    </div>
</div>


