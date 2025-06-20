<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Namotrader</title>
    <meta name="description" content="Namotrader Trading Platform">
    <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://kit.fontawesome.com/9701dbec97.js"></script>
      <title>Transaction Page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .badge {
            font-size: 15px;
            line-height: 1em;
            border-radius: 100px;
            letter-spacing: 0;
            height: 22px;
            min-width: 80px;
            width: auto;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 400;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <i class="fas fa-bars"></i>
            </a>
        </div>
        <div class="pageTitle">
            <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="logo">
        </div>
        <div class="right">
            <a href="{{ route('deposit.request.form') }}" class="headerButton icon">
                <i class="fas fa-home"></i>
                <span>Deposit</span>
            </a>
              <a href="{{route('withdrawal.requests.form') }}" class="headerButton icon">
                <i class="fas fa-hand-holding-usd" aria-hidden="true"></i>
                <span>Withdraw</span>
            </a>
            <a href="#" class="headerButton icon">
                <i class="fas fa-share-alt" aria-hidden="true"></i>
                <span>Share</span>
            </a>
        </div>
    </div>

    @yield('content')

    <div class="appBottomMenu">
        <a href="{{ route('home') }}" class="item {{ request()->routeIs('home') ? 'active' : '' }}">
            <div class="col">
                <i class="fas fa-search text-white font-20"></i>
                <strong>Watchlist</strong>
            </div>
        </a>
        <a href="{{ route('trades') }}" class="item {{ request()->routeIs('trades') ? 'active' : '' }}">
            <div class="col">
                <i class="fas fa-book text-white font-20"></i>
                <strong>Trades</strong>
            </div>
        </a>
        <a href="{{ route('deposit.withdraw') }}" class="item {{ request()->routeIs('deposit.withdraw') ? 'active' : '' }}">
            <div class="col">
                <i class="fas fa-hand-holding-usd text-white font-20"></i>
                <strong>Deposit/Withdraw</strong>
            </div>
        </a>
        <a href="{{ route('portfolio') }}" class="item {{ request()->routeIs('portfolio') ? 'active' : '' }}">
            <div class="col">
                <i class="fas fa-briefcase text-white font-20"></i>
                <strong>Portfolio</strong>
            </div>
        </a>
        <a href="{{ route('my.account') }}" class="item {{ request()->routeIs('my.account') ? 'active' : '' }}">
            <div class="col">
                <i class="fas fa-user text-white font-20"></i>
                <strong>Account</strong>
            </div>
        </a>
    </div>

    <!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- profile box -->
                    <div class="profileBox pt-2 pb-2">
                        <div class="image-wrapper">
                            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="imaged w36">
                        </div>
                        <div class="in">
                            <strong>Sebastian Doe</strong>
                            <div class="text-muted">4029209</div>
                        </div>
                        <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                    <!-- * profile box -->
                    <!-- menu -->
                    <div class="listview-title mt-1">Menu</div>
                    <ul class="listview flush transparent no-line image-listview">
                        <li>
                            <a href="{{ route('home') }}" class="item">
                                <div class="icon-box bg-primary">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="in">
                                    Watchlist
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('trades') }}" class="item">
                                <div class="icon-box bg-primary">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="in">
                                    Trades
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('deposit.withdraw') }}" class="item">
                                <div class="icon-box bg-primary">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <div class="in">
                                    Deposit/Withdraw
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('portfolio') }}" class="item">
                                <div class="icon-box bg-primary">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="in">
                                    Portfolio
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('my.account') }}" class="item">
                                <div class="icon-box bg-primary">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="in">
                                    Account
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- * menu -->
                </div>
            </div>
        </div>
    </div>
    <!-- * App Sidebar -->

    <!-- Scripts -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    @yield('scripts')
</body>
</html>
