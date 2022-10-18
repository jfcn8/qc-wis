<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notifications - {{ Auth()->user()->name }}</h6>
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

        @if (Auth()->user()->unreadNotifications->count() > 0)
            <button class="mb-2 btn btn-primary btn-sm" wire:click="markAllAsRead()">Mark all as Read</button>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Timestamp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($notifications->count() > 0)
                        @foreach ($notifications as $notification)
                            <tr>
                                <td>
                                    @foreach ($notification->data as $key => $value)
                                        {!!  ($key == "ris_id" || $key == "model_id") ? "" : ucwords(str_replace('_', ' ',$key)). ' : '. $value . '<br>' !!}
                                    @endforeach
                                </td>
                                <td><a href="{{url('ris/item/' . ($notification->model_id))}}">{{ $notification->created_at->format('M. d, Y h:m:s A') }}</a></td>
                                <td>
                                    @if($notification->read_at == null)
                                        <button class="btn btn-sm btn-primary" wire:click="markAsRead({{$notification->id}})">Mark as Read</button>
                                    @else
                                        Read at {{ $notification->read_at->format('M. d, Y h:m:s A') }}
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No Notification</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
 