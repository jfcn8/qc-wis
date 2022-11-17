{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Dashboard</h6>
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

        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Number of Items
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">

                                    @if (in_array('Items', $access))
                                        <a href="{{route('items')}}">{{ $itemCount }}</a>
                                    @else
                                        {{ $itemCount }}
                                    @endif
                                    
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    # of Delivery This week
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    
                                    @if (in_array('Deliveries', $access))
                                    <a href="{{ route('deliveries') }}">{{ $deliveryCount }}</a>
                                    @else
                                        {{ $deliveryCount }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    # of Requisition and Issue Slip This week
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            @if (in_array('RIS', $access))
                                                <a href="{{route('ris')}}">{{ $risCount }}</a>
                                            @else
                                                {{ $risCount }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Requisition and Issue Slip Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <a href="{{route('ris')}}">{{$pendingCount }}</a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Item - Low stock</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th>Description</th>
                                        <th>Stock #</th>
                                        <th>Unit</th>
                                        <th class="text-center">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($itemsLowStock->count() > 0)
                                        @foreach ($itemsLowStock as $item)
                                            <tr>
                                                @if (in_array('Items', $access))
                                                    <td><a href="{{URL('references/' . $item->item_id)}}">{{ $item->article }}</a></td>
                                                    <td><a href="{{URL('references/' . $item->item_id)}}">{{ $item->description }}</a></td>
                                                    <td><a href="{{URL('references/' . $item->item_id)}}">{{ $item->stock_number }}</a></td>
                                                    <td><a href="{{URL('references/' . $item->item_id)}}">{{ $item->unit }}</a></td>
                                                @else
                                                    <td>{{ $item->article }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->stock_number }}</td>
                                                    <td>{{ $item->unit }}</td>
                                                @endif

                                                
                                                @php
                                                    $class = ''
                                                @endphp
                                                @if ($item->stock == 0)
                                                    <?php $class='bg-danger text-white' ?>
                                                @else
                                                    <?php $class='bg-success  text-white' ?>
                                                @endif

                                                <td class="text-center {{ $class }}">
                                                    @if ($item->stock == 0)
                                                        Out of Stock
                                                    @else
                                                        {{ $item->stock }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="2">All Stock is ok.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
