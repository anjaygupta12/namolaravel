<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Namotrader Admin Panel">
    <meta name="author" content="Namotrader">
    <title>Namotrader Admin</title>

    <!-- Custom fonts -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom styles -->
    <link href="{{ asset('admin-assets/css/material-dashboard.css') }}" rel="stylesheet">

    <!-- Additional CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        /* Basic layout structure */
        #wrapper {
            display: flex;
            position: relative;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar styling */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 260px;
            z-index: 1000;
            overflow-y: auto;
            background-color: #000 !important;
            color: #fff !important;
            transition: all 0.3s ease;
        }

        .sidebar .sidebar-wrapper {
            overflow-x: auto;
            width: 100%;
            padding-right: 15px;
        }

        .sidebar .nav-link,
        .sidebar .nav-link p,
        .sidebar .nav-link i,
        .sidebar .simple-text,
        .sidebar .collapse-item,
        .sidebar .collapse-header {
            color: #fff !important;
        }

        .sidebar .active .nav-link {
            background-color: #28a745 !important;
        }

        .sidebar .collapse-inner {
            background-color: #333 !important;
        }

        /* Content area styling */
        #content-wrapper {
            flex: 1;
            margin-left: 260px;
            width: calc(100% - 260px);
            transition: all 0.3s ease;
        }

        /* Sidebar Toggle Button Styling */
        #sidebarToggleTop {
           background-color: #28a745;
            color: white;
            border-radius: 4px;
            width: 54px;
            height: 55px;
            display: flex
        ;
            align-items: center;
            justify-content: center;
        }

        #sidebarToggleTop i {
            color: white;
            font-size: 18px;
        }

        /* Toggled Sidebar Styles */
        .sidebar.toggled {
            width: 0;
            overflow: hidden;
            margin-left: -260px;
            left: -260px;
        }

        body.sidebar-toggled #content-wrapper {
            margin-left: 0;
            width: 100%;
        }

        /* Container Adjustments */
        .container-fluid {
            padding: 1.5rem;
            width: 100%;
        }
    </style>

    @yield('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <div class="sidebar" data-color="green" data-background-color="black">
            <div class="logo">
                <a href="{{ route('admin.dashboard') }}" class="simple-text logo-normal">
                    DASHBOARD
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="col-12 d-sm-none">
                    <a class="dropdown-item bg-info text-white" href="#">Ledger-Balance: <span
                            id="sidebar_net_ledger_balance">-100004025502.8</span></a>
                </div>
                <ul class="nav">
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fa fa-table-columns"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.bank-details') ? 'active' : '' }}">
                        <a href="{{ route('admin.bank-details') }}" class="nav-link">
                            <i class="fa fa-arrow-trend-up"></i>
                            <p>Bank Details</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.negative-balance') ? 'active' : '' }}">
                        <a href="{{ route('admin.negative-balance') }}" class="nav-link">
                            <i class="fa fa-arrow-trend-up"></i>
                            <p>Negative Balance Transactions</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.market-watch') ? 'active' : '' }}">
                        <a href="{{ route('admin.market-watch') }}" class="nav-link">
                            <i class="fa fa-arrow-trend-up"></i>
                            <p>Market Watch</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                        <a href="{{ route('admin.notifications') }}" class="nav-link">
                            <i class="fa fa-bell"></i>
                            <p>Notifications</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.action-ledger') ? 'active' : '' }}">
                        <a href="{{ route('admin.action-ledger') }}" class="nav-link">
                            <i class="fa fa-podcast"></i>
                            <p>Action Ledger</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.active-positions') ? 'active' : '' }}">
                        <a href="{{ route('admin.active-positions') }}" class="nav-link">
                            <i class="fa fa-certificate"></i>
                            <p>Active Positions</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.closed-positions') ? 'active' : '' }}">
                        <a href="{{ route('admin.closed-positions') }}" class="nav-link">
                            <i class="fa fa-certificate"></i>
                            <p>Closed Positions</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <a href="{{ route('admin.users') }}" class="nav-link">
                            <i class="fa fa-users"></i>
                            <p>Trading Clients</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.trades') ? 'active' : '' }}">
                        <a href="{{ route('admin.trades') }}" class="nav-link">
                            <i class="fa fa-tag"></i>
                            <p>Trades</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.trades-list') ? 'active' : '' }}">
                        <a href="{{ route('admin.trades-list') }}" class="nav-link">
                            <i class="fa fa-tag"></i>
                            <p>Trades List</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.group-trades') ? 'active' : '' }}">
                        <a href="{{ route('admin.group-trades') }}" class="nav-link">
                            <i class="fa fa-tag"></i>
                            <p>Group Trades</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.closed-trades') ? 'active' : '' }}">
                        <a href="{{ route('admin.closed-trades') }}" class="nav-link">
                            <i class="fa fa-tag"></i>
                            <p>Closed Trades</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.deleted-trades') ? 'active' : '' }}">
                        <a href="{{ route('admin.deleted-trades') }}" class="nav-link">
                            <i class="fa fa-tag"></i>
                            <p>Deleted Trades</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.pending-orders') ? 'active' : '' }}">
                        <a href="{{ route('admin.pending-orders') }}" class="nav-link">
                            <i class="fa fa-swatchbook"></i>
                            <p>Pending Orders</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.funds-wds') ? 'active' : '' }}">
                        <a href="{{ route('admin.funds-wds') }}" class="nav-link">
                            <i class="fa fa-circle-dollar-to-slot"></i>
                            <p>Trader Funds</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.brokers') ? 'active' : '' }}">
                        <a href="{{ route('admin.brokers') }}" class="nav-link">
                            <i class="fa fa-user-group"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.scrip-data') ? 'active' : '' }}">
                        <a href="{{ route('admin.scrip-data') }}" class="nav-link">
                            <i class="fa fa-user-group"></i>
                            <p>Scrip Data</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.market-scripts') ? 'active' : '' }}">
                        <a href="{{ route('admin.market-scripts') }}" class="nav-link">
                            <i class="fa fa-chart-line"></i>
                            <p>Market Scripts</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.accounts') ? 'active' : '' }}">
                        <a href="{{ route('admin.accounts') }}" class="nav-link">
                            <i class="fa fa-calculator"></i>
                            <p>Accounts</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.social-links') ? 'active' : '' }}">
                        <a href="{{ route('admin.social-links') }}" class="nav-link">
                            <i class="fa fa-user-group"></i>
                            <p>Social Links</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.change-password') ? 'active' : '' }}">
                        <a href="{{ route('admin.change-password') }}" class="nav-link">
                            <i class="fa fa-user"></i>
                            <p>Change Login Password</p>
                        </a>
                    </li>
                    <li
                        class="nav-item {{ request()->routeIs('admin.change-transaction-password') ? 'active' : '' }}">
                        <a href="{{ route('admin.change-transaction-password') }}" class="nav-link">
                            <i class="fa fa-gear"></i>
                            <p>Change Transaction Password</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.withdrawal-requests') ? 'active' : '' }}">
                        <a href="{{ route('admin.withdrawal-requests') }}" class="nav-link">
                            <i class="fa fa-gear"></i>
                            <p>Withdrawal Requests</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.deposit-requests') ? 'active' : '' }}">
                        <a href="{{ route('admin.deposit-requests') }}" class="nav-link">
                            <i class="fa fa-gear"></i>
                            <p>Deposit Requests</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.logout') }}" class="nav-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out-alt"></i>
                            <p>Log Out</p>
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Heading -->
        <!-- <div class="sidebar-heading">
                Users Management
            </div> -->

        <!-- Nav Item - Users -->
        <!-- <li class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li> -->

        <!-- Divider -->
        <!-- <hr class="sidebar-divider">

            

            
        <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTrades"
                    aria-expanded="true" aria-controls="collapseTrades">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Trades</span>
                </a>
                <div id="collapseTrades" class="collapse" aria-labelledby="headingTrades" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Trade Options:</h6>
                        <a class="collapse-item" href="{{ route('admin.trades') }}">All Trades</a>
                        <a class="collapse-item" href="{{ route('admin.trades-list') }}">Trades List</a>
                        <a class="collapse-item" href="{{ route('admin.closed-trades') }}">Closed Trades</a>
                        <a class="collapse-item" href="{{ route('admin.deleted-trades') }}">Deleted Trades</a>
                        <a class="collapse-item" href="{{ route('admin.pending-orders') }}">Pending Orders</a>
                    </div>
                </div>
            </li> -->



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->

                    <!-- Topbar Search -->
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <form class="d-none d-sm-inline-block form-inline ml-auto my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small"
                                placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('admin-assets/img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
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
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Namotrader 2023</span>
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
                    <a class="btn btn-primary" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Material Dashboard Core JS -->
    <script src="{{ asset('admin-assets/js/material-dashboard.js') }}"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function() {
            // Toggle sidebar when the top button is clicked
            $('#sidebarToggleTop').on('click', function() {
                $('body').toggleClass('sidebar-toggled');
                $('.sidebar').toggleClass('toggled');

                if ($('.sidebar').hasClass('toggled')) {
                    $('.sidebar .collapse').collapse('hide');
                    // Store sidebar state in localStorage
                    localStorage.setItem('sidebarToggled', 'true');
                    // Adjust content wrapper
                    $('#content-wrapper').css({
                        'margin-left': '0',
                        'width': '100%'
                    });
                } else {
                    // Store sidebar state in localStorage
                    localStorage.setItem('sidebarToggled', 'false');
                    // Adjust content wrapper
                    $('#content-wrapper').css({
                        'margin-left': '260px',
                        'width': 'calc(100% - 260px)'
                    });
                }

                // Force a resize event to adjust any responsive elements
                $(window).trigger('resize');
            });

            // Check for saved sidebar state on page load
            if (localStorage.getItem('sidebarToggled') === 'true') {
                $('body').addClass('sidebar-toggled');
                $('.sidebar').addClass('toggled');
                $('#content-wrapper').css({
                    'margin-left': '0',
                    'width': '100%'
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
