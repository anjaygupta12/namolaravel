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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
 
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #sidebarToggleTop i {
            color: white;
            font-size: 18px;
        }

        /* Toggled Sidebar Styles */
        .sidebar.toggled {
            width: 80px;
            overflow: hidden;
        }
        
        .sidebar.toggled .nav-item p,
        .sidebar.toggled .logo-normal,
        .sidebar.toggled .simple-text {
            display: none;
        }
        
        .sidebar.toggled .nav-item {
            text-align: center;
        }
        
        .sidebar.toggled .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        
        body.sidebar-toggled #content-wrapper {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        /* Container Adjustments */
    

        /* Mobile Responsive Adjustments */
        @media (max-width: 991.98px) {
            /* Make sidebar full width on mobile */
            .sidebar {
                width: 260px;
                left: 0;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            /* Adjust content when sidebar is open */
            #content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            /* Mobile menu button */
            .mobile-menu-btn {
                display: block;
                position: fixed;
                left: 15px;
                top: 15px;
                z-index: 1030;
                background: #28a745;
                color: white;
                border: none;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                text-align: center;
                line-height: 50px;
                font-size: 20px;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }
            
            /* Overlay when sidebar is open */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }
            
            body.sidebar-show .sidebar-overlay {
                display: block;
            }
        }

        @media (min-width: 992px) {
            /* Hide mobile menu button on desktop */
            .mobile-menu-btn {
                display: none;
            }
            
            /* Hide sidebar overlay on desktop */
            .sidebar-overlay {
                display: none !important;
            }
        }

        /* Additional responsive improvements */
        @media (max-width: 767.98px) {
            .container-fluid {
                padding: 1rem;
            }
        }

        /* Make nav items more touch-friendly */
        .nav-item {
            padding: 5px 0;
        }

        .nav-link {
            padding: 10px 15px;
            margin: 2px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Improve logo visibility */
        .logo {
            padding: 15px 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 10px;
        }

        .logo-normal {
            font-weight: 600;
            font-size: 1.1rem;
        }
    </style>

    @yield('styles')
</head>

<body id="page-top">
    <!-- Mobile Menu Button (visible only on mobile) -->
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay for mobile menu -->
    <div class="sidebar-overlay"></div>

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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar content (same as before) -->
                    
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
    <script src="{{ asset('admin-assets/js/material-dashboard.min.js') }}"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function() {
            // Mobile menu toggle
            $('.mobile-menu-btn').on('click', function() {
                $('body').toggleClass('sidebar-show');
                $('.sidebar').toggleClass('show');
            });
            
            // Close sidebar when clicking overlay
            $('.sidebar-overlay').on('click', function() {
                $('body').removeClass('sidebar-show');
                $('.sidebar').removeClass('show');
            });
            
            // Close sidebar when clicking a nav link on mobile
            $('.sidebar .nav-link').on('click', function() {
                if ($(window).width() < 992) {
                    $('body').removeClass('sidebar-show');
                    $('.sidebar').removeClass('show');
                }
            });
            
            // Desktop sidebar toggle
            $('#sidebarToggleTop').on('click', function() {
                $('body').toggleClass('sidebar-toggled');
                $('.sidebar').toggleClass('toggled');
                localStorage.setItem('sidebarToggled', $('body').hasClass('sidebar-toggled'));
            });
            
            // Check for saved sidebar state on page load
            if (localStorage.getItem('sidebarToggled') === 'true') {
                $('body').addClass('sidebar-toggled');
                $('.sidebar').addClass('toggled');
            }
            
            // Auto-hide sidebar on mobile by default
            function handleResponsive() {
                if ($(window).width() < 992) {
                    $('body').removeClass('sidebar-toggled');
                    $('.sidebar').removeClass('toggled');
                } else {
                    // Restore desktop state
                    if (localStorage.getItem('sidebarToggled') === 'true') {
                        $('body').addClass('sidebar-toggled');
                        $('.sidebar').addClass('toggled');
                    }
                }
            }
            
            // Run on load and resize
            handleResponsive();
            $(window).on('resize', handleResponsive);
        });
    </script>

    @yield('scripts')
</body>

</html>