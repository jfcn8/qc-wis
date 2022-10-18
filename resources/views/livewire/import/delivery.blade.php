<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Import Delivery</h6>
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

        <div class="col-md-4">
            <form action="../import/delivery" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" name="delivery" placeholder="Select Formatted Delivery" required>
                    </div>
                </div>
               

                <button type="submit" class="btn btn-primary">Import Delivery</button>
            </form>
        </div>
    </div>
</div>