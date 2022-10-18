{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Activities</h6>
    </div>
    <div class="card-body">
        @include('livewire.layouts.loading')
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Description</th>
                        <th>By</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($activities->count() > 0)
                        @foreach ($activities as $activity)
                            <tr>
                                <td>{{ ucwords($activity->description) . ' ' . $activity->log_name   }}</td>
                                <td>
                                    @foreach ($activity->properties as $property => $value)
                                        
                                        @if ($property != 'attributes')
                                            {{ucwords($property) . ': '}}
                                            <br>
                                        @endif
                                        
                                
                                        @foreach ($value as $key => $value_)

                                            {{-- {{ ($activity->log_name == "DBM Price") ? "<a>Click me to open the file.</a>" : ucwords((str_contains($key, '.') ? substr($key, 0, strpos($key, '.')) : $key)) . ' - ' . $value_ }}  --}}
                                            {{ ucwords((str_contains($key, '.') ? substr($key, 0, strpos($key, '.')) : $key)) . ' - ' . $value_ }}
                                            <br>
                                        @endforeach

                                        <br>
                                    @endforeach
                                </td>
                                <td>{{ $activity->User->name }}</td>
                                <td>{{ ($activity->created_at == null) ? "" : $activity->created_at->format('M. d, Y h:m:s A') }}</td>

                                
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
            {{ $activities->links() }}
        </div>

    </div>
</div>