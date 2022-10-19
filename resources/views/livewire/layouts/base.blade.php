<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'WIS') }}</title>
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">

    <!-- Custom fonts for this template-->
    
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script type="text/javascript" src="{{ asset('js/sb-admin-2.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>

    

    @livewireStyles
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard')}}">
                <div class="sidebar-brand-icon">

                    <img src="{{ asset('img/wis.png') }}" alt="">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            
            <?php
               $access = explode(',', Auth()->user()->access);
               $permissions = explode(',', Auth()->user()->permissions);
            ?>

            @if (in_array('Dashboard', $access))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard')}}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span></a>
                </li>
            @endif

            @if (in_array('Items', $access))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('items') }}">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Items</span></a>
                </li>
            @endif
            

            

            @if (in_array('Deliveries', $access))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('deliveries') }}">
                        <i class="fas fa-truck"></i>
                        <span>Delivery/Restock</span></a>
                </li>
            @endif
            

            @if (in_array('RIS', $access))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('ris')}}">
                        <i class="fas fa-list"></i>
                        <span>RIS</span></a>
                </li>
            @endif

            @php
                $withMisc = 0;
                $miscList = "Offices,Units,Classifications,Articles,Suppliers,Price List,Signatories";
                $misc = explode(',', $miscList);

                foreach ($misc as $key => $value) {
                    if (in_array($value, $access)) {
                        $withMisc = 1;
                        break;
                    }
                }
            @endphp

            <!-- Nav Item - Pages Collapse Menu -->
            @if($withMisc == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Misc</span>
                </a>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">

                        @if (in_array('Offices', $access))
                            <a class="collapse-item" href="{{route('offices')}}">Office</a>
                        @endif
                        @if (in_array('Units', $access))
                            <a class="collapse-item" href="{{route('units')}}">Unit</a>
                        @endif
                        @if (in_array('Classifications', $access))
                            <a class="collapse-item" href="{{route('classifications')}}">Classification</a>
                        @endif
                        @if (in_array('Articles', $access))
                            <a class="collapse-item" href="{{route('articles')}}">Article</a>
                        @endif
                        @if (in_array('Suppliers', $access))
                            <a class="collapse-item" href="{{route('suppliers')}}">Supplier</a>
                        @endif
                        @if (in_array('Price Lists', $access))
                            <a class="collapse-item" href="{{route('dbms')}}">DBM/GSD Pricelist</a>
                        @endif
                        @if (in_array('Signatories', $access))
                            <a class="collapse-item" href="{{route('signatories')}}">Signatory</a>
                        @endif
                        
                    </div>
                </div>
            </li>
            @endif
            
            

            <!-- Nav Item - Pages Collapse Menu -->
            @if (in_array('Generate', $permissions))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#report"
                    aria-expanded="true" aria-controls="report">
                    <i class="fas fa-book"></i>
                    <span>Reports</span>
                </a>
                <div id="report" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{url('items/stock-card')}}">Stock Card</a>
                        <a class="collapse-item" href="{{url('items/mism')}}">MISM</a>
                        <a class="collapse-item" href="{{url('items/ssmi')}}">SSMI</a>
                    </div>
                </div>
            </li>
            @endif

        @if (in_array('Activities', $access))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('activities')}}">
                        <i class="fas fa-shoe-prints"></i>
                        <span>Activities</span></a>
                </li>
            @endif

            @if (in_array('Accounts', $access))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('accounts')}}">
                        <i class="fas fa-users"></i>
                        <span>Accounts</span></a>
                </li>
            @endif
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <?php $notif_count = Auth()->user()->unreadNotifications->count(); ?>
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">{{ ($notif_count > 0) ? $notif_count : "" }}</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Notifications
                                </h6>

                                @foreach(Auth()->user()->unreadNotifications()->take(5)->latest()->get()  as $notification)
                                <a class="dropdown-item align-items-center" href="{{ url('ris/item/' . $notification->data['model_id']) }}">
                                    <div>
                                        <div class="small text-black-100">{{ $notification->created_at->format('M. d, Y h:m:s A') }}</div>
                                        <span class="font-weight-bold">

                                            {!! ($notification->data['date_request'] != "") ? date('M. d, Y', strtotime($notification->data['date_request'])) . '<br>' : "" !!}
                                            {!! ($notification->data['action'] != "") ? $notification->data['action'] . '<br>' : "" !!}
                                            {!! ($notification->data['purpose'] != "") ? $notification->data['purpose'] . ' of ' . $notification->data['office']  . '<br>' : "" !!}
                                            
                                        </span>
                                        
                                    </div>
                                </a>
                                @endforeach
                                
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications') }}">Show All Notifications</a>
                            </div>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth()->user()->name }}</span>
                                <i class="fas fa-solid fa-user"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    {{ $slot }}
                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; QC Warehouse Inventory System {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form action="{{ url('logout')}}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    @livewireScripts
    
</body>
</html>