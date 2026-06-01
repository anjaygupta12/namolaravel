<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Namotrader</title>
    <meta name="description" content="Namotrader Trading Platform">
    <meta name="keywords"
        content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
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


        /* Force modal width and center it */
        .modal.custom-centered .modal-dialog {
            width: 380px;
            max-width: 100%;
            margin: auto;
        }

        .modal.custom-centered .modal-content {
            text-align: center;
            background-color: #311b7f;
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
            <a href="{{ route('withdrawal.requests.form') }}" class="headerButton icon">
                <i class="fas fa-hand-holding-usd" aria-hidden="true"></i>
                <span>Withdraw</span>
            </a>
            <a href="#" class="headerButton icon">
                <i class="fas fa-share-alt" aria-hidden="true"></i>
                <span>Share</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a type="button" class="headerButton icon" data-bs-toggle="modal" data-bs-target="#logoutConfirmation">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                <span>Logout</span> </a>

            {{-- <a href="#" class="headerButton icon" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
            <span>Logout</span>
        </a> --}}

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
        <a href="{{ route('deposit.withdraw') }}"
            class="item {{ request()->routeIs('deposit.withdraw') ? 'active' : '' }}">
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
                            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image"
                                class="imaged w36">
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

    <!-- Logout Confirmation Modal -->
    <div class="modal fade custom-centered" id="logoutConfirmation" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 bg-danger">
                    <h5 class="modal-title text-white fw-bold">
                        Are you sure you want to logout?
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="card bg-transparent border-0">
                        <div class="card-body pt-0">
                            <div class="form-group basic">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-success"
                                        onclick="document.getElementById('logout-form').submit();"
                                        style="border-radius: 0; min-width: 130px;">
                                        Confirm
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                        style="border-radius: 0; min-width: 130px;">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
    <script>
    
        (function() {
            let deviceCheckInterval;
            const POLL_INTERVAL = 1000; // Check every 1 seconds

            function checkDeviceSession() {
                const loader = document.getElementById('loader');
                const loginCard = document.querySelector('.login-card');
                let deviceId = localStorage.getItem('device_id');

                if (!deviceId) {
                    deviceId = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                        const r = Math.random() * 16 | 0;
                        const v = c === 'x' ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
                    localStorage.setItem('device_id', deviceId);
                }

                fetch('/set-device-id?device_id=' + encodeURIComponent(deviceId) + '&logiedIn=1')
                    .then(response => {
                        if (response.status === 401) {
                            window.location.reload();
                        }
                        return response.json();
                    })
                    .then(data => {
                        const deviceInput = document.getElementById('device_id');
                        if (deviceInput) {
                            deviceInput.value = data.device_id || deviceId;
                        }

                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (loginCard) {
                            loginCard.style.display = 'block';
                        }
                    })
                    .catch(err => console.error(err));
            }

            function startPolling() {
                if (!deviceCheckInterval) {
                    checkDeviceSession();
                    deviceCheckInterval = setInterval(checkDeviceSession, POLL_INTERVAL);
                }
            }

            function stopPolling() {
                if (deviceCheckInterval) {
                    clearInterval(deviceCheckInterval);
                    deviceCheckInterval = null;
                }
            }

            startPolling();

            // Cordova lifecycle events
            document.addEventListener('pause', stopPolling, false);
            document.addEventListener('resume', startPolling, false);

            // Web visibility events
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') {
                    startPolling();
                } else {
                    stopPolling();
                }
            });
        })();



        // (function() {

        //     "use strict";

        //     /* ------------------ CSS ------------------ */

        //     const style = document.createElement("style");
        //     style.innerHTML = `

    //         #globalLoader{
    //         position:fixed;
    //         top:0;
    //         left:0;
    //         width:100%;
    //         height:100%;
    //         background:rgba(255,255,255,0.6);
    //         backdrop-filter: blur(4px);
    //         -webkit-backdrop-filter: blur(4px);
    //         display:flex;
    //         align-items:center;
    //         justify-content:center;
    //         z-index:999999;
    //         transition:0.3s ease;
    //         }

    //         #loaderSpinner{
    //         width:55px;
    //         height:55px;
    //         border:5px solid #e5e5e5;
    //         border-top:5px solid #007bff;
    //         border-radius:50%;
    //         animation:spinLoader .8s linear infinite;
    //         }

    //         #pullDownLoader{
    //         position:fixed;
    //         top:-70px;
    //         left:0;
    //         width:100%;
    //         text-align:center;
    //         background:#fff;
    //         padding:12px;
    //         z-index:999998;
    //         transition:0.3s;
    //         box-shadow:0 2px 5px rgba(0,0,0,0.1);
    //         font-weight:600;
    //         }

    //         @keyframes spinLoader{
    //         0%{transform:rotate(0deg);}
    //         100%{transform:rotate(360deg);}
    //         }

    //         `;
        //     document.head.appendChild(style);


        //     /* ------------------ PAGE LOAD LOADER ------------------ */

        //     const pageLoader = document.createElement("div");
        //     pageLoader.id = "globalLoader";
        //     pageLoader.innerHTML = `<div id="loaderSpinner"></div>`;

        //     document.body.appendChild(pageLoader);


        //     /* Hide Loader After Full Load */

        //     window.addEventListener("load", function() {

        //         pageLoader.style.opacity = "0";

        //         setTimeout(() => {
        //             pageLoader.remove();
        //         }, 400);

        //     });


        //     /* ------------------ MODAL CHECK FUNCTION ------------------ */

        //     function isModalOpen() {
        //         return document.querySelector('.modal.show') !== null ||
        //             document.body.classList.contains('modal-open');
        //     }


        //     /* ------------------ PULL DOWN REFRESH ------------------ */

        //     let startY = 0;
        //     let pullDistance = 0;
        //     let isPulling = false;
        //     const refreshDistance = 120;


        //     /* Pull Down Element */

        //     const pullDiv = document.createElement("div");
        //     pullDiv.id = "pullDownLoader";
        //     pullDiv.innerHTML = "⬇️ Pull down to refresh";

        //     document.body.appendChild(pullDiv);


        //     /* ------------------ TOUCH START ------------------ */

        //     document.addEventListener("touchstart", function(e) {

        //         if (window.scrollY === 0 && !isModalOpen()) {
        //             startY = e.touches[0].pageY;
        //             isPulling = true;
        //         }

        //     });


        //     /* ------------------ TOUCH MOVE ------------------ */

        //     document.addEventListener("touchmove", function(e) {

        //         if (!isPulling || isModalOpen()) return;

        //         let touchY = e.touches[0].pageY;
        //         pullDistance = touchY - startY;

        //         if (pullDistance > 0) {

        //             e.preventDefault();

        //             pullDiv.style.top = Math.min(pullDistance - 70, 20) + "px";

        //             if (pullDistance > refreshDistance) {
        //                 pullDiv.innerHTML = "🔄 Release to refresh";
        //             } else {
        //                 pullDiv.innerHTML = "⬇️ Pull down to refresh";
        //             }

        //         }

        //     }, {
        //         passive: false
        //     });


        //     /* ------------------ TOUCH END ------------------ */

        //     document.addEventListener("touchend", function() {

        //         if (!isPulling || isModalOpen()) return;

        //         pullDiv.style.top = "-70px";

        //         if (pullDistance > refreshDistance) {

        //             /* Show Loader */
        //             document.body.appendChild(pageLoader);
        //             pageLoader.style.opacity = "1";

        //             setTimeout(() => {
        //                 location.reload();
        //             }, 50);

        //         }

        //         isPulling = false;
        //         pullDistance = 0;

        //     });

        // })();

        (function() {
        if (window.location.pathname.includes('/portfolio')) {
            return;
        }
                
            "use strict";

            /* ------------------ CSS ------------------ */

            const style = document.createElement("style");
            style.innerHTML = `
        #globalLoader{
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(255,255,255,0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:999999;
            transition:0.3s ease;
        }

        #loaderSpinner{
            width:55px;
            height:55px;
            border:5px solid #e5e5e5;
            border-top:5px solid #007bff;
            border-radius:50%;
            animation:spinLoader .8s linear infinite;
        }

        #pullDownLoader{
            position:fixed;
            top:-70px;
            left:0;
            width:100%;
            text-align:center;
            background:#fff;
            padding:12px;
            z-index:999998;
            transition:0.3s;
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
            font-weight:600;
        }

        @keyframes spinLoader{
            0%{transform:rotate(0deg);}
            100%{transform:rotate(360deg);}
        }
    `;
            document.head.appendChild(style);


            /* ------------------ PAGE LOAD LOADER ------------------ */

            const pageLoader = document.createElement("div");
            pageLoader.id = "globalLoader";
            pageLoader.innerHTML = `<div id="loaderSpinner"></div>`;

            document.body.appendChild(pageLoader);

            window.addEventListener("load", function() {
                pageLoader.style.opacity = "0";
                setTimeout(() => {
                    pageLoader.remove();
                }, 400);
            });


            /* ------------------ MODAL DETECTION (FIXED) ------------------ */

            let modalOpen = false;

            document.addEventListener('shown.bs.modal', function() {
                modalOpen = true;
            });

            document.addEventListener('hidden.bs.modal', function() {
                modalOpen = false;
            });

            function isModalOpen() {
                return modalOpen;
            }


            /* ------------------ PULL DOWN REFRESH ------------------ */

            let startY = 0;
            let pullDistance = 0;
            let isPulling = false;
            const refreshDistance = 120;


            /* Pull Down Element */

            const pullDiv = document.createElement("div");
            pullDiv.id = "pullDownLoader";
            pullDiv.innerHTML = "⬇️ Pull down to refresh";

            document.body.appendChild(pullDiv);


            /* ------------------ TOUCH START ------------------ */

            document.addEventListener("touchstart", function(e) {

                if (
                    window.scrollY === 0 &&
                    !isModalOpen() &&
                    !e.target.closest('.modal')
                ) {
                    startY = e.touches[0].pageY;
                    isPulling = true;
                } else {
                    isPulling = false;
                }

            });


            /* ------------------ TOUCH MOVE ------------------ */

            document.addEventListener("touchmove", function(e) {

                if (!isPulling || isModalOpen()) return;

                let touchY = e.touches[0].pageY;
                pullDistance = touchY - startY;

                if (pullDistance > 0) {

                    e.preventDefault();

                    pullDiv.style.top = Math.min(pullDistance - 70, 20) + "px";

                    if (pullDistance > refreshDistance) {
                        pullDiv.innerHTML = "🔄 Release to refresh";
                    } else {
                        pullDiv.innerHTML = "⬇️ Pull down to refresh";
                    }

                }

            }, {
                passive: false
            });


            /* ------------------ TOUCH END ------------------ */

            document.addEventListener("touchend", function() {

                if (!isPulling || isModalOpen()) return;

                pullDiv.style.top = "-70px";

                if (pullDistance > refreshDistance) {

                    document.body.appendChild(pageLoader);
                    pageLoader.style.opacity = "1";

                    setTimeout(() => {
                        location.reload();
                    }, 50);

                }

                isPulling = false;
                pullDistance = 0;

            });

        })();

    </script>


</body>

</html>
