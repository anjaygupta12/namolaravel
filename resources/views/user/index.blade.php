@extends('layouts.user')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .modal-dialog {
            max-width: 100%;
            margin: 0 auto;
        }

        .modal-content {
            max-height: 90vh;
            overflow: hidden;
            border-radius: 1rem;
        }

        .modal-body {
            overflow-y: auto;
            max-height: 70vh;
            padding-bottom: 0;
        }

        .nav-tabs.lined .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #fff;
        }

        .nav-tabs.lined .nav-link.active {
            border-bottom: 2px solid #0dcaf0;
            font-weight: bold;
        }

        .form-group input {
            font-size: 16px;
        }

        @media (max-width: 576px) {
            .modal-content {
                height: 95vh;
            }

            .modal-body {
                max-height: calc(95vh - 120px);
            }

            .modal-header,
            .modal-footer {
                padding: 10px 15px;
            }

            .nav-tabs {
                flex-direction: row;
                overflow-x: auto;
            }

            .nav-item {
                min-width: 20%;
            }
        }

        .swal2-container {
            z-index: 200000 !important;
            position: fixed !important;
        }

        @media (max-width: 768px) {
            .nav-tabs {
                display: flex;
                flex-wrap: nowrap;
                justify-content: space-between;
            }

            .nav-tabs .nav-item {
                flex: 1 1 auto;
                text-align: center;
            }

            .nav-tabs .nav-link {
                padding: 6px 4px;
                font-size: 11px;
                white-space: normal;
                line-height: 1.2;
            }
        }

        .custom-radio {
            appearance: none;
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            border: 2px solid #000;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            margin-right: 6px;
            background: #fff;
        }

        .custom-radio:checked::before {
            content: "";
            width: 8px;
            height: 8px;
            background: #000;
            border-radius: 50%;
            position: absolute;
            top: 1px;
            left: 1px;
        }

        .form-check-label {
            font-size: 14px;
            margin-bottom: 0;
            cursor: pointer;
        }

        .qty-section {
            gap: 20px;
        }
    </style>
@endsection

@section('content')
    <div id="appCapsule">
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <label id="lblTransactionMode" style="display:none">MCX</label>

                @php
                    $activeTab = null;
                    if ($user->MCXEnabled == 1) {
                        $activeTab = 'mcx';
                    } elseif ($user->NSEFuturesEnabled == 1) {
                        $activeTab = 'nse';
                    } elseif ($user->NSEOptionsEnabled == 1) {
                        $activeTab = 'options';
                    } elseif ($user->comex_trading_enabled == 1) {
                        $activeTab = 'comex';
                    } elseif ($user->forex_trading_enabled == 1) {
                        $activeTab = 'forex';
                    }
                @endphp

                <ul class="nav nav-tabs lined" role="tablist">
                    @if ($user->MCXEnabled == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'mcx' ? 'active' : '' }}" data-bs-toggle="tab" href="#mcx"
                                onclick="ChangeText('MCX');" role="tab">MCX Futures</a>
                        </li>
                    @endif
                    @if ($user->NSEFuturesEnabled == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'nse' ? 'active' : '' }}" data-bs-toggle="tab" href="#nse"
                                onclick="ChangeText('NSE');" role="tab">NSE Futures</a>
                        </li>
                    @endif
                    @if ($user->NSEOptionsEnabled == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'options' ? 'active' : '' }}" data-bs-toggle="tab"
                                href="#options" onclick="ChangeText('OPTIONS');" role="tab">Options</a>
                        </li>
                    @endif
                    @if ($user->comex_trading_enabled == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'comex' ? 'active' : '' }}" data-bs-toggle="tab"
                                href="#comex" onclick="ChangeText('COMEX');" role="tab">Comex</a>
                        </li>
                    @endif
                    @if ($user->forex_trading_enabled == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'forex' ? 'active' : '' }}" data-bs-toggle="tab"
                                href="#forex" onclick="ChangeText('CRYPTO_FOR');" role="tab">Forex &amp; Crypto</a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    @if ($user->MCXEnabled == 1)
                        <div class="tab-pane fade show {{ $activeTab === 'mcx' ? 'active' : '' }}" id="mcx"
                            role="tabpanel">
                            <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                                <input type="text" name="mcx_scrips" id="scrips_search_btn"
                                    placeholder="Search &amp; Add" onclick="OpenWatchListModal('MCX',this)"
                                    onkeyup="OpenWatchListModal('MCX',this)" style="width:100%;">
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="btlMCX"></tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($user->NSEFuturesEnabled == 1)
                        <div class="tab-pane fade show {{ $activeTab === 'nse' ? 'active' : '' }}" id="nse"
                            role="tabpanel">
                            <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                                <input type="text" name="mcx_scrips" id="scrips_search_btn1"
                                    placeholder="Search &amp; Add" onclick="OpenWatchListModal('NSE',this)"
                                    onkeyup="OpenWatchListModal('NSE',this)" style="width:100%;">
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="tblNSE"></tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($user->NSEOptionsEnabled == 1)
                        <div class="tab-pane fade show {{ $activeTab === 'options' ? 'active' : '' }}" id="options"
                            role="tabpanel">
                            <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                                <input type="text" name="mcx_scrips" id="scrips_search_btn51"
                                    placeholder="Search &amp; Add" onclick="OpenWatchListModal('OPTIONS',this)"
                                    onkeyup="OpenWatchListModal('OPTIONS',this)" style="width:100%;">
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="tblOPTIONS"></tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($user->comex_trading_enabled == 1)
                        <div class="tab-pane fade show {{ $activeTab === 'comex' ? 'active' : '' }}" id="comex"
                            role="tabpanel">
                            <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                                <input type="text" name="mcx_scrips" id="scrips_search_btn511"
                                    placeholder="Search &amp; Add" onclick="OpenWatchListModal('COMEX',this)"
                                    onkeyup="OpenWatchListModal('COMEX',this)" style="width:100%;">
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="btlCOMEX"></tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($user->forex_trading_enabled == 1)
                        <div class="tab-pane fade show {{ $activeTab === 'forex' ? 'active' : '' }}" id="forex"
                            role="tabpanel">
                            <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                                <input type="text" name="mcx_scrips" id="scrips_search_btn51121"
                                    placeholder="Search &amp; Add" onclick="OpenWatchListModal('CRYPTO',this)"
                                    onkeyup="OpenWatchListModal('CRYPTO',this)" style="width:100%;">
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="tblforex"></tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Search Modal --}}
        <div class="modal fade action-sheet" id="mcxpop" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal">Close</button>
                        <input type="text" name="mcx_filter" class="search-input w-75" id="mcx_filter"
                            oninput="seachFilter()" placeholder="Search &amp; add">
                    </div>
                    <div class="modal-body serach-models">
                        <div class="card">
                            <div class="card-body pt-0">
                                <div class="mcx_search_modal active" id="mcx_search">
                                    <div class="mcx_search_middle_cntnt">
                                        <div class="mxc_list">
                                            <table class="table" style="color:#fff">
                                                <tbody id="search_datamain"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #333;">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Trade Modal --}}
        <div class="modal fade action-sheet" id="withdrawActionSheetForex_Crypto" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><span id="lblsymbol"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card">
                            <div class="card-body pt-0 px-2">
                                <ul class="nav nav-tabs lined flex-nowrap" role="tablist">
                                    <li class="nav-item flex-grow-1 text-center">
                                        <a class="nav-link active py-2" data-bs-toggle="tab" href="#overview2"
                                            role="tab">Market</a>
                                    </li>
                                    <li class="nav-item flex-grow-1 text-center">
                                        <a class="nav-link py-2" data-bs-toggle="tab" href="#cards2"
                                            role="tab">Order</a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-2">
                                    {{-- Market Tab --}}
                                    <div class="tab-pane fade show active" id="overview2" role="tabpanel">
                                        <div class="min-mega" style="display: none;">

                                            <div class="d-flex align-items-center">

                                                <div class="d-flex align-items-center mr-3">
                                                    <input type="radio" name="qty_type" id="min" value="0"
                                                        class="custom-radio">

                                                    <label for="min" class="form-check-label mb-0 ml-1">
                                                        Min
                                                    </label>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <input type="radio" name="qty_type" id="mega" value="0"
                                                        class="custom-radio" checked>

                                                    <label for="mega" class="form-check-label mb-0 ml-1">
                                                        Mega
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="qty-section">
                                            <div class="form-group basic mb-3">
                                                <label class="label" for="nse-qty">Quantity</label>
                                                <input type="text" name="qty" class="form-control" id="nse-qty"
                                                    placeholder="Enter Qty">
                                            </div>
                                        </div>
                                        <div class="lot-section">
                                            <div class="form-group basic mb-3">
                                                <label class="label" for="textfclot">Lots</label>
                                                <input type="number" class="form-control" id="textfclot"
                                                    placeholder="Enter Lots" value="1">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <button class="btn btn-danger w-100 py-3" id="sellBtn"
                                                    onclick="sellfc();" style="background:#b24153;">
                                                    <div class="d-block">Sell</div>
                                                    <div class="fw-bold fs-4" id="tblfcsellprice">0</div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success w-100 py-3" id="buyBtn"
                                                    onclick="buyfc();" style="background:#208549;">
                                                    <div class="d-block">Buy</div>
                                                    <div class="fw-bold fs-4" id="tblfcbuyprice">0</div>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td><small class="text-white-50">Bid</small>
                                                            <div id="lblBid">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Ask</small>
                                                            <div id="lblAsk">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Last</small>
                                                            <div id="lblLast">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">High</small>
                                                            <div id="lblHigh">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Low</small>
                                                            <div id="lblLow">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Change</small>
                                                            <div id="lblChange">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Open</small>
                                                            <div id="lblOpen">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Volume</small>
                                                            <div id="lblVolume">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Last Traded
                                                                Qty</small>
                                                            <div id="lblLastTradedQty">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Atp</small>
                                                            <div id="lblAtp">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Lot Size</small>
                                                            <div id="lblLotSize">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Open
                                                                Interest</small>
                                                            <div id="lblOpenInterest">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Bid Qty</small>
                                                            <div id="lblBidQty">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Ask Qty</small>
                                                            <div id="lblAskQty">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Prev.
                                                                Close</small>
                                                            <div id="lblPrevClose">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Upper Circuit</small>
                                                            <div id="lblUpperCircuit">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Lower Circuit</small>
                                                            <div id="lblLowerCircuit">0</div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Order Tab --}}
                                    <div class="tab-pane fade" id="cards2" role="tabpanel">
                                        <div class="min-mega" style="display: none;">

                                            <div class=" d-flex align-items-center">

                                                <div class="d-flex align-items-center mr-3">
                                                    <input type="radio" name="qty_type_order" id="min-order"
                                                        value="1" class="custom-radio">

                                                    <label for="min-order" class="form-check-label mb-0 ml-1">
                                                        Min
                                                    </label>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <input type="radio" name="qty_type_order" id="mega-order"
                                                        value="0" class="custom-radio" checked>

                                                    <label for="mega-order" class="form-check-label mb-0 ml-1">
                                                        Mega
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="qty-section">
                                            <div class="form-group basic mb-3">
                                                <label class="label" for="nse-qty">Quantity</label>
                                                <input type="text" name="qty" class="form-control" id="nse-qty-2"
                                                    placeholder="Enter Qty">
                                            </div>
                                        </div>
                                        <div class="lot-section">
                                            <div class="form-group basic mb-3">
                                                <label class="label" for="textfclot">Lots</label>
                                                <input type="number" class="form-control" id="textfclot-order"
                                                    placeholder="Enter Lots" value="1">
                                            </div>
                                        </div>
                                        <div class="form-group basic mb-3">
                                            <label class="label" for="txtPriceOrder">Price</label>
                                            <input type="number" class="form-control" id="txtPriceOrder"
                                                placeholder="Enter Price">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <button class="btn btn-danger w-100 py-2" id="orderSellBtn"
                                                    onclick="sellplacedorder();" style="background:#b24153;">Place Sell
                                                    Order</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success w-100 py-2" id="orderBuyBtn"
                                                    onclick="buyplacedorder();" style="background:#208549;">Place Buy
                                                    Order</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td><small class="text-white-50">Bid</small>
                                                            <div id="lblbid">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Ask</small>
                                                            <div id="lblASK">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Last</small>
                                                            <div id="lblLast1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">High</small>
                                                            <div id="lblHigh1">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Low</small>
                                                            <div id="lblLow1">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Change</small>
                                                            <div id="lblChange1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Open</small>
                                                            <div id="lblOpen1">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Volume</small>
                                                            <div id="lblVolume1">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Last Traded
                                                                Qty</small>
                                                            <div id="lblLastTradedQty1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Atp</small>
                                                            <div id="lblAtp2">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Lot Size</small>
                                                            <div id="lblLotSize2">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Open
                                                                Interest</small>
                                                            <div id="lblOpenInterest2">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Bid Qty</small>
                                                            <div id="lblBidQty2">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Ask Qty</small>
                                                            <div id="lblAskQty2">0</div>
                                                        </td>
                                                        <td class="text-end"><small class="text-white-50">Prev.
                                                                Close</small>
                                                            <div id="lblprev_close_price1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small class="text-white-50">Upper Circuit</small>
                                                            <div id="lblUpperCircuit1">0</div>
                                                        </td>
                                                        <td><small class="text-white-50">Lower Circuit</small>
                                                            <div id="lblLowerCircuit1">0</div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            "use strict";

            /* ── CONFIG ── */
            const USER_ID = "{{ Auth::guard('tradeuser')->user()->id }}";
            const WS_URL = 'wss://namotraders.in:5005/ws?userId=' + USER_ID;
            const MCX_ON = "{{ $user->MCXEnabled ?? 0 }}";
            const NSE_ON = "{{ $user->NSEFuturesEnabled ?? 0 }}";
            const OPT_ON = "{{ $user->NSEOptionsEnabled ?? 0 }}";
            const COMEX_ON = "{{ $user->comex_trading_enabled ?? 0 }}";
            const FOREX_ON = "{{ $user->forex_trading_enabled ?? 0 }}";
            const QTY_MODE = "{{ $user->TradeEquityAsUnits ?? 0 }}";
            const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            /* ── STATE ── */
            let socket = null;
            let cache = {};
            let dirty = {};
            let rafPending = false;
            let modalRafId = null;
            let activeSymbol = '';
            let circuitCache = {};
            let firstBatch = true; // hide loader after first painted batch
            const domCache = {};

            /* ── DOM helpers ── */
            function el(id) {
                return domCache[id] || (domCache[id] = document.getElementById(id));
            }

            function els(className) {
                return document.querySelectorAll('.' + className);
            }

            function setText(id, v) {
                const n = el(id);
                if (n) n.textContent = (v != null && v !== '') ? v : '0';
            }

            /* ── Table bodies (grabbed once) ── */
            const TB = {
                MCX: document.getElementById('btlMCX'),
                NSE: document.getElementById('tblNSE'),
                OPTIONS: document.getElementById('tblOPTIONS'),
                COMEX: document.getElementById('btlCOMEX'),
                FOREX: document.getElementById('tblforex'),
            };

            /* ════════════════════════════════
               WEBSOCKET
            ════════════════════════════════ */
            let reconnectTimer = null;
            let isIntentionalClose = false;

            function connect() {
                if (socket) {
                    isIntentionalClose = true;
                    socket.onclose = null;
                    socket.close();
                    socket = null;
                }
                
                isIntentionalClose = false;
                clearTimeout(reconnectTimer);

                socket = new WebSocket(WS_URL);
                socket.onopen = () => console.log('[WS] connected');
                socket.onerror = (e) => {
                    console.error('[WS] error', e);
                };
                socket.onclose = (e) => {
                    console.log('[WS] closed', e ? e.reason : '');
                    if (!isIntentionalClose) {
                        reconnectTimer = setTimeout(connect, 3000);
                    }
                };
                socket.onmessage = onMessage;
            }

            function handleAppResume() {
                if (!socket || socket.readyState !== WebSocket.OPEN) {
                    console.log('[WS] Resumed / Online - Reconnecting...');
                    connect();
                }
            }

            function handleAppPause() {
                console.log('[WS] Paused / Hidden - Closing socket...');
                isIntentionalClose = true;
                if (socket) socket.close();
            }

            // Web Network & Visibility Events
            window.addEventListener('online', handleAppResume);
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') {
                    handleAppResume();
                } else {
                    handleAppPause();
                }
            });

            // Cordova Lifecycle Events
            document.addEventListener('resume', handleAppResume, false);
            document.addEventListener('pause', handleAppPause, false);
            document.addEventListener('online', handleAppResume, false);

            function onMessage(event) {
                let data;
                try {
                    data = JSON.parse(event.data);
                    console.log('checked', data);
                } catch {
                    return;
                }
                console.log(!Array.isArray(data) || !data.length, data.length);
                if (!Array.isArray(data) || !data.length) return;

                for (let i = 0, len = data.length; i < len; i++) {
                    console.log('inner', data);
                    const item = data[i];
                    if (!item || !item.Data || !item.name) continue;
                    const sym = item.name;
                    const nd = item.Data;
                    const prev = cache[sym];
                    console.log(item.Data.lower_circuit);
                    //  upper_circuit
                    const lowerCktM = item.Data.lower_circuit;
                    const upperCktM = item.Data.upper_circuit;
                    const sellNew = (lowerCktM > 0 && nd.bid_price <= lowerCktM) ? 0 : nd.bid_price;
                    const buyNew = (upperCktM > 0 && nd.ask_price >= upperCktM) ? 0 : nd.ask_price;

                    if (prev && prev.ltp === nd.ltp && prev.bid === nd.bid_price && prev.ask === nd.ask_price) continue;
                    cache[sym] = {
                        ltp: '' + nd.ltp,
                        bid: '' + sellNew,
                        ask: '' + buyNew,
                        high: nd.high_price,
                        low: nd.low_price,
                        ch: nd.ch,
                        open: nd.open_price,
                        vol: nd.vol_traded_today,
                        ltq: nd.last_traded_qty,
                        atp: nd.avg_trade_price,
                        prev_close: nd.prev_close_price,
                        upper_ckt: nd.upper_circuit,
                        lower_ckt: nd.lower_circuit,
                        shortName: nd.symbolShortName,
                        expiry: nd.expiryDate,
                        bid_size: nd.bid_size,
                        ask_size: nd.ask_size,
                    };
                    dirty[sym] = true;
                }

                if (!rafPending) {
                    rafPending = true;
                    requestAnimationFrame(flushRows);
                }
            }

            /* ── Batch DOM flush ── */
            function flushRows() {
                rafPending = false;

                const frags = {
                    MCX: (MCX_ON && TB.MCX) ? document.createDocumentFragment() : null,
                    NSE: (NSE_ON && TB.NSE) ? document.createDocumentFragment() : null,
                    OPTIONS: (OPT_ON && TB.OPTIONS) ? document.createDocumentFragment() : null,
                    COMEX: (COMEX_ON && TB.COMEX) ? document.createDocumentFragment() : null,
                    FOREX: (FOREX_ON && TB.FOREX) ? document.createDocumentFragment() : null,
                };

                for (const sym in dirty) {
                    delete dirty[sym];
                    const d = cache[sym];
                    if (!d) continue;

                    const parts = sym.split(':');
                    const exchange = parts[0];
                    const rawSym = parts[1] || sym;
                    const isOption = rawSym.includes('CE') || rawSym.includes('PE');

                    const bid = parseFloat(d.bid || 0).toFixed(2);
                    const ask = parseFloat(d.ask || 0).toFixed(2);
                    const ch = parseFloat(d.ch || 0).toFixed(2);
                    const high = parseFloat(d.high || 0).toFixed(2);
                    const low = parseFloat(d.low || 0).toFixed(2);
                    const open = parseFloat(d.open || 0).toFixed(2);
                    const ltpN = parseFloat(d.ltp || 0);
                    const askN = parseFloat(d.ask || 0);

                    const infoHTML = `<h4 class="comodity">${d.shortName||rawSym}</h4>
                <p class="comodity">${formatDate(d.expiry)}</p>
                <p class="comodity">Chg:<span>${ch}</span> H:<span>${high}</span></p>`;
                    const bidHTML = `<p class="text-white fw-bold badge badge-${ltpN<askN?'danger':'success'}">${bid}</p>
                <p class="text-white fw-bold">L: <span>${low}</span></p>`;
                    const askHTML = `<p class="text-white fw-bold badge badge-${ltpN>askN?'danger':'success'}">${ask}</p>
                <p class="text-white fw-bold">O: ${open}</p>`;

                    const existing = document.getElementById(sym);
                    if (existing) {
                        const tds = existing.children;
                        if (tds[0]) tds[0].innerHTML = infoHTML;
                        if (tds[1]) tds[1].innerHTML = bidHTML;
                        if (tds[2]) tds[2].innerHTML = askHTML;
                    } else {
                        const frag = getFrag(exchange, isOption, frags, rawSym);
                        if (!frag) continue;
                        const tr = document.createElement('tr');
                        tr.id = sym;
                        tr.setAttribute('onclick', `OPENMODALMCXNSE('${sym}',0,0);`);
                        tr.innerHTML =
                            `<td>${infoHTML}</td><td>${bidHTML}</td><td class="text-end text-primary">${askHTML}</td>`;
                        frag.appendChild(tr);
                    }
                }

                for (const key in frags) {
                    if (frags[key] && frags[key].childNodes.length && TB[key])
                        TB[key].appendChild(frags[key]);
                }

                /* Hide loader only after first batch is fully painted */
                if (firstBatch) {
                    firstBatch = false;
                    hideLoader();
                }
            }

            // function getFrag(exchange, isOption, frags) {

            //     if (exchange === 'MCX') return frags.MCX;
            //     if (exchange === 'COMEX') return frags.COMEX;
            //     if (exchange === 'NSE') return isOption ? frags.OPTIONS : frags.NSE;
            //     if (exchange === 'FOREX') return frags.FOREX;
            //     return null;
            // }
            function getFrag(exchange, isOption, frags, rawSym) {

    const isFut = rawSym.includes('FUT');
    const isCEPE = rawSym.includes('CE') || rawSym.includes('PE');

    // MCX
    if (exchange === 'MCX') {

        // MCX FUT only in MCX tab
        if (isFut) {
            return frags.MCX;
        }

        // MCX CE/PE only in OPTIONS tab
        if (isCEPE) {
            return frags.OPTIONS;
        }
    }

    // NSE
    if (exchange === 'NSE') {

        // NSE FUT only in NSE tab
        if (isFut) {
            return frags.NSE;
        }

        // NSE CE/PE only in OPTIONS tab
        if (isCEPE) {
            return frags.OPTIONS;
        }
    }

    // COMEX
    if (exchange === 'COMEX') {
        return frags.COMEX;
    }

    // FOREX
    if (exchange === 'FOREX') {
        return frags.FOREX;
    }

    return null;
}

            /* ════════════════════════════════
               TRADE MODAL
            ════════════════════════════════ */
            function OPENMODALMCXNSE(symbol) {
                activeSymbol = symbol;
                const exchange = symbol.split(':')[0];
                let futSymbol = symbol.match(/FUT$/)?.[0] || '';
                if (QTY_MODE == 1 && exchange === 'NSE' && futSymbol=='FUT') {

                    els('lot-section').forEach(e => e.style.display = 'none');
                    els('qty-section').forEach(e => e.style.display = 'block');
                } else {
                    els('lot-section').forEach(e => e.style.display = 'block');
                    els('qty-section').forEach(e => e.style.display = 'none');
                    els('nse-qty').forEach(e => e.value = '');
                }

                setText('lblsymbol', symbol);
                setText('tblfcsellprice', '0');
                setText('tblfcbuyprice', '0');
                setText('lblLotSize', '');
                setText('lblVolume1', '');

                if (circuitCache[symbol]) {
                    applyCircuit(symbol);
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('get.symbol') }}",
                        data: {
                            _token: CSRF,
                            Symbol: symbol
                        },
                        success(data) {
                            circuitCache[symbol] = {
                                upper: data.upper_ckt || '0',
                                lower: data.lower_ckt || '0',
                                lotSize: data.lotSize || '0'
                            };
                            applyCircuit(symbol);
                        },
                        error(xhr) {
                            console.error('Circuit fetch', xhr.responseText);
                        }
                    });
                }

                const $modal = $('#withdrawActionSheetForex_Crypto');
                $modal.off('shown.bs.modal hidden.bs.modal');

                $modal.on('show.bs.modal', function() {
                    // Reset all input fields
                    $('#nse-qty, #nse-qty-2').val('');
                    $('#textfclot, #textfclot-order').val('1');
                    $('#txtPriceOrder').val('');

                    // Reset Market tab display values
                    $('#lblBid, #lblAsk, #lblLast').text('0');
                    $('#lblHigh, #lblLow, #lblChange').text('0');
                    $('#lblOpen, #lblVolume, #lblLastTradedQty').text('0');
                    $('#lblAtp, #lblLotSize, #lblOpenInterest').text('0');
                    $('#lblBidQty, #lblAskQty, #lblPrevClose').text('0');
                    $('#lblUpperCircuit, #lblLowerCircuit').text('0');
                    $('#tblfcsellprice, #tblfcbuyprice').text('0');

                    // Reset Order tab display values
                    $('#lblbid, #lblASK, #lblLast1').text('0');
                    $('#lblHigh1, #lblLow1, #lblChange1').text('0');
                    $('#lblOpen1, #lblVolume1, #lblLastTradedQty1').text('0');
                    $('#lblAtp2, #lblLotSize2, #lblOpenInterest2').text('0');
                    $('#lblBidQty2, #lblAskQty2, #lblprev_close_price1').text('0');
                    $('#lblUpperCircuit1, #lblLowerCircuit1').text('0');

                    // Reset to Market tab by default
                    $('#withdrawActionSheetForex_Crypto .nav-link').removeClass('active');
                    $('#withdrawActionSheetForex_Crypto .tab-pane').removeClass('show active');
                    $('#withdrawActionSheetForex_Crypto .nav-link[href="#overview2"]').addClass('active');
                    $('#overview2').addClass('show active');

                    let form = $(this).find('form');

                    if (form.length) {
                        form[0].reset();
                    }

                    $('#mega').prop('checked', true);
                    $('#min').prop('checked', false);

                    $('.min-mega').hide();

                    const cacheData = cache[activeSymbol];
                    const shortName_d = cacheData.shortName;

                    if (shortName_d == 'GOLD' || shortName_d == 'COPPER' || shortName_d == 'CRUDEOIL' ||
                        shortName_d == 'SILVER') {
                        $('.min-mega').css('display', 'block');
                    } else {
                        $('.min-mega').css('display', 'none');
                    }

                });

                $modal.on('shown.bs.modal', function() {
                    stopTicker();
                    startTicker();
                });

                $modal.on('hidden.bs.modal', function() {
                    stopTicker();
                    activeSymbol = '';
                });

                $modal.modal('show');
            }

            function applyCircuit(symbol) {
                const c = circuitCache[symbol] || {};
                setText('lblUpperCircuit', c.upper || '0');
                setText('lblUpperCircuit1', c.upper || '0');
                setText('lblLowerCircuit', c.lower || '0');
                setText('lblLowerCircuit1', c.lower || '0');
                setText('lblLotSize', c.lotSize || '0');
                setText('lblLotSize2', c.lotSize || '0');
                setText('lblVolume1', c.lotSize || '0');
            }

            let lastTick = 0;

            function startTicker() {
                function tick(ts) {
                    if (!activeSymbol) return;
                    if (ts - lastTick >= 150) {
                        lastTick = ts;
                        refreshModal();
                    }
                    modalRafId = requestAnimationFrame(tick);
                }
                modalRafId = requestAnimationFrame(tick);
            }

            function stopTicker() {
                if (modalRafId) {
                    cancelAnimationFrame(modalRafId);
                    modalRafId = null;
                }
            }

            function refreshModal() {
                const d = cache[activeSymbol];
                if (!d) return;
                const c = circuitCache[activeSymbol] || {};

                const lowerCkt = parseFloat(c.lower || 0);
                const upperCkt = parseFloat(c.upper || 0);
                const bidP = parseFloat(d.bid || 0);
                const askP = parseFloat(d.ask || 0);
                const sellNew = (lowerCkt > 0 && bidP <= lowerCkt) ? 0 : bidP;
                const buyNew = (upperCkt > 0 && askP >= upperCkt) ? 0 : askP;

                setText('tblfcbuyprice', buyNew || '0');
                setText('tblfcsellprice', sellNew || '0');
                setText('lblBid', d.bid || '0');
                setText('lblbid', d.ask || '0');
                setText('lblASK', d.ask || '0');
                setText('lblAsk', d.ask || '0');
                setText('lblLast', d.ltp || '0');
                setText('lblLast1', d.ltp || '0');
                setText('lblHigh', d.high || '0');
                setText('lblHigh1', d.high || '0');
                setText('lblLow', d.low || '0');
                setText('lblLow1', d.low || '0');
                setText('lblChange', d.ch || '0');
                setText('lblChange1', d.ch || '0');
                setText('lblBidQty', d.ask_size || '0');
                setText('lblBidQty2', d.ask_size || '0');
                setText('lblAskQty', d.bid_size || '0');
                setText('lblAskQty2', d.bid_size || '0');
                setText('lblVolume', d.vol || '0');
                setText('lblOpen', d.open || '0');
                setText('lblOpen1', d.open || '0');
                setText('lblOpenInterest2', d.open || '0');
                setText('lblLastTradedQty', d.ltq || '0');
                setText('lblLastTradedQty1', d.ltq || '0');
                setText('lblPrevClose', d.prev_close || '0');
                setText('lblprev_close_price1', d.prev_close || '0');
                setText('lblAtp', d.atp || '0');
                setText('lblAtp2', d.atp || '0');
                setText('lblUpperCircuit1', d.upper_ckt || c.upper || '0');
                setText('lblLowerCircuit1', d.lower_ckt || c.lower || '0');
            }

            /* ════════════════════════════════
               BUY / SELL
            ════════════════════════════════ */
            function baseData(mode) {
                return {
                    _token: '{{ csrf_token() }}',
                    Mode: mode,
                    Symbol: el('lblsymbol').textContent,
                    textfclot: el('textfclot').value,
                    Min: el('min').checked ? 1 : 0,
                    Mega: el('mega').checked ? 1 : 0,
                    Lots: el('textfclot').value,
                    lblBid: el('lblBid').textContent,
                    lblAsk: el('lblAsk').textContent,
                    lblLast: el('lblLast').textContent,
                    lblHigh: el('lblHigh').textContent,
                    lblLow: el('lblLow').textContent,
                    lblChange: el('lblChange').textContent,
                    lblOpen: el('lblOpen').textContent,
                    lblVolume: el('lblVolume').textContent,
                    lblLastTradedQty: el('lblLastTradedQty').textContent,
                    lblAtp: el('lblAtp').textContent,
                    lblLotSize: el('lblLotSize').textContent,
                    lblOpenInterest: el('lblOpenInterest').textContent,
                    lblBidQty: el('lblBidQty').textContent,
                    lblAskQty: el('lblAskQty').textContent,
                    lblPrevClose: el('lblPrevClose').textContent,
                    lblUpperCircuit: el('lblUpperCircuit').textContent,
                    lblLowerCircuit: el('lblLowerCircuit').textContent,
                    Price: '0',
                    TransactionMode: el('lblTransactionMode').textContent,
                };
            }

            // function buyfc() {
            //      let btn = document.getElementById('buyBtn');

            //     const lotVal = el('textfclot').value;
            //     if (!lotVal || lotVal === '0') {
            //         showErr('Enter Your Values!');
            //         return;
            //     }
            //     const _data = baseData('BUY');
            //     const symbol = _data.Symbol,
            //         exch = symbol.split(':')[0];
            //     let lot = lotVal;
            //     _data.tblfcbuyprice = el('tblfcbuyprice').textContent;
            //     if (QTY_MODE == 1 && exch === 'NSE') {
            //         const q = el('nse-qty').value;
            //         if (!q) {
            //             showErr('Enter Your Quantity!');
            //             return;
            //         }
            //         _data.qty = q;
            //         lot = (parseFloat(q) / parseFloat(el('lblLotSize').textContent)).toFixed(2);
            //     }
            //      btn.disabled = true;
            //     $.ajax({
            //         type: 'POST',
            //         url: "{{ url('/save-transaction') }}",
            //         data: _data,
            //         success(r) {
            //             $('#withdrawActionSheetForex_Crypto').modal('hide');
            //             Swal.fire({
            //                 position: 'top-center',
            //                 icon: 'success',
            //                 text: `Bought ${lot} Lots of ${symbol} at ${el('tblfcbuyprice').textContent}`,
            //                 title: r.message,
            //                 showConfirmButton: true,
            //                 confirmButtonText: 'OK',
            //                 allowOutsideClick: false,
            //                 allowEscapeKey: false
            //             }).then(res => {
            //                 if (res.isConfirmed) location.href = '/trades#Active';
            //             });
            //         },
            //         error(xhr) {
            //             showErr(xhr.responseJSON?.error || 'Something went wrong.');
            //              btn.disabled = false;
            //         }
            //     });
            // }

            function buyfc() {
                let btn = document.getElementById('buyBtn');

                const lotVal = el('textfclot').value;
                if (!lotVal || lotVal === '0') {
                    showErr('Enter Your Values!');
                    return;
                }

                const _data = baseData('BUY');
                const symbol = _data.Symbol,
                    exch = symbol.split(':')[0];
                     let futSymbol = symbol.match(/FUT$/)?.[0] || '';
                let lot = lotVal;
                const price = el('tblfcbuyprice').textContent;
                _data.tblfcbuyprice = price;

                if (QTY_MODE == 1 && exch === 'NSE' && futSymbol=='FUT') {
                    const q = el('nse-qty').value;
                    if (!q) {
                        showErr('Enter Your Quantity!');
                        return;
                    }
                    _data.qty = q;
                    lot = (parseFloat(q) / parseFloat(el('lblLotSize').textContent)).toFixed(2);
                }

                const cacheData1 = cache[activeSymbol];
                const shortName_d1 = cacheData1.shortName;
                
                if (_data.Min == 1) {
                    if (shortName_d1 == 'SILVER') {
                        lot = ((lot * 5) / 30).toFixed(4);
                    } else {
                        lot = ((lot * 10) / 100).toFixed(4);
                    }
                }

                btn.disabled = true;
                const originalBtnText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                $.ajax({
                    type: 'POST',
                    url: "{{ url('/save-transaction') }}",
                    data: _data,
                    success(r) {
                        $('#withdrawActionSheetForex_Crypto').modal('hide');
                        btn.innerHTML = originalBtnText;
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            text: `Bought ${lot} Lots of ${symbol} at ${price}`,
                            title: r.message || 'Order Placed',
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(res => {
                            if (res.isConfirmed) {
                                location.href = '/trades#Active';
                            }
                        });
                    },
                    error(xhr) {
                        showErr(xhr.responseJSON?.error || 'Order failed! Please retry.');
                        btn.disabled = false;
                        btn.innerHTML = originalBtnText;
                    }
                });
            }

            // function sellfc___() {
            //  let btn = document.getElementById('sellBtn');

            //     const lotVal = el('textfclot').value;
            //     if (!lotVal || lotVal === '0') {
            //         showErr('Enter Your Value!');
            //         return;
            //     }
            //     const _data = baseData('SELL');
            //     const symbol = _data.Symbol,
            //         exch = symbol.split(':')[0];
            //     let lot = lotVal;
            //     _data.tblfcbuyprice = el('tblfcsellprice').textContent;
            //     if (QTY_MODE == 1 && exch === 'NSE') {
            //         const q = el('nse-qty').value;
            //         if (!q) {
            //             showErr('Enter Your Quantity!');
            //             return;
            //         }
            //         _data.qty = q;
            //         lot = (parseFloat(q) / parseFloat(el('lblLotSize').textContent)).toFixed(2);
            //     }
            //      btn.disabled = true;
            //     $.ajax({
            //         type: 'POST',
            //         url: "{{ route('save.transaction') }}",
            //         data: _data,
            //         success(r) {
            //             $('#withdrawActionSheetForex_Crypto').modal('hide');
            //             Swal.fire({
            //                 position: 'top-center',
            //                 icon: 'success',
            //                 text: `Sold ${lot} Lots of ${symbol} at ${el('tblfcsellprice').textContent}`,
            //                 title: r.message,
            //                 showConfirmButton: true,
            //                 confirmButtonText: 'OK',
            //                 allowOutsideClick: false,
            //                 allowEscapeKey: false
            //             }).then(res => {
            //                 if (res.isConfirmed) location.href = '/trades#Active';
            //             });
            //         },
            //         error(xhr) {
            //             showErr(xhr.responseJSON?.error || 'Something went wrong...');
            //             btn.disabled = false;
            //         }
            //     });
            // }
            function sellfc() {
                let btn = document.getElementById('sellBtn');

                const lotVal = el('textfclot').value;
                if (!lotVal || lotVal === '0') {
                    showErr('Enter Your Value!');
                    return;
                }

                const _data = baseData('SELL');
                const symbol = _data.Symbol,
                    exch = symbol.split(':')[0];
                     let futSymbol = symbol.match(/FUT$/)?.[0] || '';
                let lot = lotVal;
                const price = el('tblfcsellprice').textContent;
                _data.tblfcbuyprice = price;

                if (QTY_MODE == 1 && exch === 'NSE' && futSymbol=='FUT') {
                    const q = el('nse-qty').value;
                    if (!q) {
                        showErr('Enter Your Quantity!');
                        return;
                    }
                    _data.qty = q;
                    lot = (parseFloat(q) / parseFloat(el('lblLotSize').textContent)).toFixed(2);
                }
                const cacheData1 = cache[activeSymbol];
                const shortName_d1 = cacheData1.shortName;

                if (_data.Min == 1) {
                    if (shortName_d1 == 'SILVER') {
                        lot = ((lot * 5) / 30).toFixed(4);
                    } else {
                        lot = ((lot * 10) / 100).toFixed(4);
                    }
                }

                btn.disabled = true;
                const originalBtnText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                $.ajax({
                    type: 'POST',
                    url: "{{ route('save.transaction') }}",
                    data: _data,
                    success(r) {
                        $('#withdrawActionSheetForex_Crypto').modal('hide');
                        btn.innerHTML = originalBtnText;
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            text: `Sold ${lot} Lots of ${symbol} at ${price}`,
                            title: r.message || 'Order Placed',
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(res => {
                            if (res.isConfirmed) {
                                location.href = '/trades#Active';
                            }
                        });
                    },
                    error(xhr) {
                        showErr(xhr.responseJSON?.error || 'Something went wrong...');
                        btn.disabled = false;
                        btn.innerHTML = originalBtnText;
                    }
                });
            }

            function validateOrder() {
                const amount = Number(el('txtPriceOrder').value),
                    lots = 1;
                const lowerCkt = Number(el('lblLowerCircuit').textContent);
                const upperCkt = Number(el('lblUpperCircuit').textContent);
                const lowVal = Number(el('lblLow').textContent);

                if (!lots || lots === '0' || !el('txtPriceOrder').value || el('txtPriceOrder').value === '0') {
                    showErr('Enter Your Price!');
                    return null;
                }
                if (isNaN(amount)) {
                    showErr('Invalid number detected');
                    return null;
                }
                if (lowerCkt && amount < lowerCkt) {
                    showErr('You cannot take less than ' + lowerCkt);
                    return null;
                }
                if (upperCkt && amount > upperCkt) {
                    showErr('You cannot take greater than ' + upperCkt);
                    return null;
                }
                return {
                    amount,
                    lots,
                    activeConditions: amount < lowVal ? 'Lower' : 'Above'
                };
            }

            function orderData(mode, v) {
                let exch = $('#lblsymbol').html().split(':')[0];

                const currentPrice = mode == 'SELL' ? $("#lblBid").html() : $("#lblAsk").html();
                console.log(currentPrice, v.amount);

                let data = {
                    _token: '{{ csrf_token() }}',
                    Mode: mode,
                    isOrder: true,
                    activeConditions: v.amount < currentPrice ? 'Lower' : 'Above',
                    Symbol: el('lblsymbol').textContent,
                    textfclot: '0',
                    Min: el('min-order').checked ? 1 : 0,
                    Mega: el('mega-order').checked ? 1 : 0,
                    Lots: $("#textfclot-order").val(),
                    lblBid: $("#lblBid").html(),
                    lblAsk: $("#lblAsk").html(),
                    lblLast: $("#lblLast").html(),
                    lblHigh: $("#lblHigh").html(),
                    lblLow: $("#lblLow").html(),
                    lblChange: $("#lblChange").html(),
                    lblOpen: $("#lblOpen").html(),
                    lblVolume: $("#lblVolume").html(),
                    lblLastTradedQty: $("#lblLastTradedQty").html(),
                    lblAtp: $("#lblAtp").html(),
                    lblLotSize: $("#lblLotSize").html(),
                    lblOpenInterest: $("#lblOpenInterest").html(),
                    lblBidQty: $("#lblBidQty").html(),
                    lblAskQty: $("#lblAskQty").html(),
                    lblPrevClose: $("#lblPrevClose").html(),
                    lblUpperCircuit: $("#lblUpperCircuit").html(),
                    lblLowerCircuit: $("#lblLowerCircuit").html(),

                    tblfcbuyprice: v.amount,
                    TransactionMode: el('lblTransactionMode').textContent,
                };

                // Conditionally add qty based on QTY_MODE and exchange
                if (QTY_MODE == 1 && exch === 'NSE') {
                    data.qty = $("#nse-qty-2").val();
                }

                return data;
            }

            function sellplacedorder() {
                let btn = document.getElementById('orderSellBtn');

                let lot = $("#textfclot-order").val();
                let exch = $('#lblsymbol').html().split(':')[0];
                let lotSize = $("#lblLotSize").html();

                const v = validateOrder();
                if (!v) return;

                if (v.lots > 3) {
                    showErr('Max no. lots 2');
                    return;
                }

                if (QTY_MODE == 1 && exch === 'NSE') {
                    const q = $("#nse-qty-2").val();
                    if (!q) {
                        showErr('Enter Your Quantity!');
                        return;
                    }
                    lot = (parseFloat(q) / parseFloat(lotSize)).toFixed(2);
                }

                const cacheDataOrderse = cache[activeSymbol];
                const shortName_dorser_sell = cacheDataOrderse.shortName;
                 const order_dataMin = orderData('SELL', v).Min;
                if (order_dataMin == 1) {
                    if (shortName_dorser_sell == 'SILVER') {
                        lot = ((lot * 5) / 30).toFixed(4);
                    } else {
                        lot = ((lot * 10) / 100).toFixed(4);
                    }
                }

                const symbol = el('lblsymbol').textContent;

                btn.disabled = true;
                const originalBtnText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                $.ajax({
                    type: 'POST',
                    url: "{{ route('save.transaction') }}",
                    data: orderData('SELL', v),
                    success() {
                        $('#withdrawActionSheetForex_Crypto').modal('hide');
                        btn.innerHTML = originalBtnText;
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            text: `SELL order of ${lot} Lots of ${symbol} scheduled to execute ${v.activeConditions} Rs.${v.amount}`,
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(r => {
                            if (r.isConfirmed) location.href = '/trades';
                        });
                    },
                    error(xhr) {
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        showErr(xhr.responseJSON?.error || 'Order failed! Please retry.');
                    }
                });
            }

            // function sellplacedorder() {
            // var lot = $("#textfclot-order").val();
            //  let btn = document.getElementById('orderSellBtn');

            // let exch = $('#lblsymbol').html().split(':')[0];
            // let lotSize = $("#lblLotSize").html();
            // var message = ''
            //     const v = validateOrder();
            //     if (!v) return;
            //     if (v.lots > 3) {
            //         showErr('Max no. lots 2');
            //         return;
            //     }
            //     if (QTY_MODE == 1 && exch === 'NSE') {
            //     const q = $("#nse-qty-2").val();
            //         if (!q) {
            //             showErr('Enter Your Quantity!');
            //             return;
            //         }

            //         lot = (parseFloat(q) / parseFloat(lotSize)).toFixed(2);
            //     }
            //     // alert(lot);

            //     // return false;
            //     const symbol = el('lblsymbol').textContent;
            //      btn.disabled = true;
            //     $.ajax({
            //         type: 'POST',
            //         url: "{{ route('save.transaction') }}",
            //         data: orderData('SELL', v),
            //         success() {
            //             $('#withdrawActionSheetForex_Crypto').modal('hide');
            //             Swal.fire({
            //                 position: 'top-center',
            //                 icon: 'success',
            //                 text: `SELL order of ${lot} Lots of ${symbol} scheduled to execute ${v.activeConditions} Rs.${v.amount}`,
            //                 showConfirmButton: true,
            //                 confirmButtonText: 'OK',
            //                 allowOutsideClick: false,
            //                 allowEscapeKey: false
            //             }).then(r => {
            //                 if (r.isConfirmed) location.href = '/trades';
            //             });
            //         },
            //         error(xhr) {
            //              btn.disabled = false;
            //             showErr(xhr.responseJSON?.error || 'Something went wrong.');
            //         }
            //     });
            // }

            // function buyplacedorder() {

            // var lot = $("#textfclot-order").val();
            //  let btn = document.getElementById('orderBuyBtn');

            // let exch = $('#lblsymbol').html().split(':')[0];
            // let lotSize = $("#lblLotSize").html();
            // var message = ''
            //     const v = validateOrder();
            //     if (!v) return;
            //     if (v.lots > 3) {
            //         showErr('Max no. lots 2');
            //         return;
            //     }


            //     if (QTY_MODE == 1 && exch === 'NSE') {
            //     const q = $("#nse-qty-2").val();
            //         if (!q) {
            //             showErr('Enter Your Quantity!');
            //             return;
            //         }

            //         lot = (parseFloat(q) / parseFloat(lotSize)).toFixed(2);
            //     }
            // //    alert(lot);
            // // return false;
            //      console.log(QTY_MODE, exch,lot);
            //     // return false;

            //     if (!v) return;
            //     const symbol = el('lblsymbol').textContent;
            //      btn.disabled = true;
            //     $.ajax({
            //         type: 'POST',
            //         url: "{{ route('save.transaction') }}",
            //         data: orderData('BUY', v),
            //         success() {
            //             $('#withdrawActionSheetForex_Crypto').modal('hide');
            //             Swal.fire({
            //                 position: 'top-center',
            //                 icon: 'success',
            //                 text: `BUY order of ${lot} Lots of ${symbol} scheduled to execute ${v.activeConditions} Rs.${v.amount}`,
            //                 showConfirmButton: true,
            //                 confirmButtonText: 'OK',
            //                 allowOutsideClick: false,
            //                 allowEscapeKey: false
            //             }).then(r => {
            //                 if (r.isConfirmed) location.href = '/trades';
            //             });
            //         },
            //         error(xhr) {
            //             showErr(xhr.responseJSON?.error || 'Something went wrong.');
            //              btn.disabled = false;
            //         }
            //     });
            // }

            function buyplacedorder() {

                let btn = document.getElementById('orderBuyBtn');

                let lot = $("#textfclot-order").val();
                let exch = $('#lblsymbol').html().split(':')[0];
                let lotSize = $("#lblLotSize").html();

                const v = validateOrder();
                if (!v) return;

                if (v.lots > 3) {
                    showErr('Max no. lots 2');
                    return;
                }

                if (QTY_MODE == 1 && exch === 'NSE') {
                    const q = $("#nse-qty-2").val();
                    if (!q) {
                        showErr('Enter Your Quantity!');
                        return;
                    }
                    lot = (parseFloat(q) / parseFloat(lotSize)).toFixed(2);
                }

                const cacheDataOrderse = cache[activeSymbol];
                const shortName_dorser_sell = cacheDataOrderse.shortName;
                const order_dataMin = orderData('BUY', v).Min;
                if (order_dataMin == 1) {
                    if (shortName_dorser_sell == 'SILVER') {
                        lot = ((lot * 5) / 30).toFixed(4);
                    } else {
                        lot = ((lot * 10) / 100).toFixed(4);
                    }
                }

                const symbol = el('lblsymbol').textContent;

                btn.disabled = true;
                const originalBtnText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                $.ajax({
                    type: 'POST',
                    url: "{{ route('save.transaction') }}",
                    data: orderData('BUY', v),
                    success() {
                        $('#withdrawActionSheetForex_Crypto').modal('hide');
                        btn.innerHTML = originalBtnText;
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            text: `BUY order of ${lot} Lots of ${symbol} scheduled to execute ${v.activeConditions} Rs.${v.amount}`,
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(r => {
                            if (r.isConfirmed) location.href = '/trades';
                        });
                    },
                    error(xhr) {
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        showErr(xhr.responseJSON?.error || 'Order failed! Please retry.');
                    }
                });
            }

            /* ════════════════════════════════
               WATCHLIST SEARCH
            ════════════════════════════════ */
            let OptionSearch = '',
                searchTimer = null;

            function OpenWatchListModal(option, textbox) {
                OptionSearch = option;
                clearTimeout(searchTimer);
                $('#mcxpop').modal('show');
                searchTimer = setTimeout(() => _doSearch(option, textbox.value), 250);
            }

            function _doSearch(option, query) {
                const tbody = document.getElementById('search_datamain');
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3">Loading…</td></tr>';
                fetch(`/getdata/?query=${encodeURIComponent(query)}&type=${option}&clientid=${USER_ID}`)
                    .then(r => {
                        if (!r.ok) throw new Error(r.statusText);
                        return r.json();
                    })
                    .then(data => {
                        if (!data || !data.length) {
                            tbody.innerHTML =
                                '<tr><td colspan="5" class="text-center py-3">No instruments found</td></tr>';
                            return;
                        }
                        const prefix = option === 'MCX' ? 'MCX:' : option === 'NSE' ? 'NSE:' : null;
                        const list = prefix ? data.filter(i => (i.Symbol || '').startsWith(prefix)) : data;
                        if (!list.length) {
                            tbody.innerHTML =
                                '<tr><td colspan="5" class="text-center py-3">No instruments found for selected market</td></tr>';
                            return;
                        }
                        tbody.innerHTML = list.map(item => {
                            const sym = item.Symbol || 'N/A',
                                desc = item.Description || sym;
                            const exch = item.TERMINAL || item.Exchange || 'N/A';
                            const isActive = !!item.ckecked;
                            const lotSize = item.LotSize ? parseInt(item.LotSize) : 'N/A';
                            const iType = item.InstrumentType || 'N/A';
                            const md = item.Data || {};
                            const expiry = md.expiry_date ? new Date(md.expiry_date).toLocaleDateString() :
                                'N/A';
                            const info = `Lot: ${lotSize}${expiry!=='N/A'?' | Exp: '+expiry:''}`;
                            const price = ['CE', 'PE', 'FUT'].includes(iType) ?
                                `<p class="chg">LTP: <span>${md.last_price||'N/A'}</span></p>` :
                                `<p class="chg">Bid: <span>${md.bid_price||'N/A'}</span></p><p class="chg">Ask: <span>${md.ask_price||'N/A'}</span></p>`;
                            const chk = isActive ?
                                `checked onclick="toggleMarketWatch('${sym}','${exch}',false);"` :
                                `onclick="toggleMarketWatch('${sym}','${exch}',true);"`;
                            return `<tr>
                        <td><div class="list_cntnt"><p class="title">${item.SymbolShortName||sym}</p>
                            <p class="id">${desc}</p><p class="chg">${info}</p></div></td>
                        <td><div class="list_cntnt">${price}</div></td>
                        <td><div class="list_cntnt"><p class="title_number">${exch}</p><p class="chg">${iType}</p></div></td>
                        <td><div class="list_cntnt"><p class="chg">Tick: ${md.tick_size||'N/A'}</p></div></td>
                        <td><div class="list_cntnt">
                            <input type="checkbox" name="market_search" id="search_${sym}_check" class="check_box" ${chk}>
                            <div class="check_mark"></div>
                        </div></td></tr>`;
                        }).join('');
                    })
                    .catch(err => {
                        tbody.innerHTML = `<tr><td colspan="5"><div class="alert alert-danger m-2">Failed: ${err.message}
                    <button onclick="_doSearch('${option}','')" class="btn btn-sm btn-warning float-end">Retry</button>
                    </div></td></tr>`;
                    });
            }

            function seachFilter() {
                const f = document.getElementById('mcx_filter').value.toLowerCase();
                document.querySelectorAll('#search_datamain tr').forEach(r => {
                    r.style.display = r.textContent.toLowerCase().includes(f) ? '' : 'none';
                });
            }

            function toggleMarketWatch(symbol, exchange, shouldAdd) {
                fetch("{{ route('watchlist.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            action: shouldAdd ? 'add' : 'remove',
                            symbol,
                            exchange,
                            clientId: USER_ID
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const cb = document.getElementById(`search_${symbol}_check`);
                            if (cb) {
                                cb.checked = shouldAdd;
                                cb.onclick = () => toggleMarketWatch(symbol, exchange, !shouldAdd);
                            }
                        } else {
                            showErr('Failed: ' + data.message);
                        }
                    })
                    .catch(() => showErr('Network error — please try again'));
            }

            /* ── Helpers ── */
            function showErr(msg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed…',
                    text: msg
                });
            }

            function formatDate(input) {
                if (!input) return '';
                const d = new Date(input);
                if (isNaN(d)) return '';
                return `${String(d.getDate()).padStart(2,'0')}-${String(d.getMonth()+1).padStart(2,'0')}-${d.getFullYear()}`;
            }

            function ChangeText(mode) {
                el('lblTransactionMode').textContent = mode;
            }

            /* ── Init ── */
            connect();

            document.getElementById('mcxpop').addEventListener('hidden.bs.modal', function() {
                firstBatch = true;
                connect();
            });
            document.getElementById('mcxpop').addEventListener('shown.bs.modal', function() {
                const inp = document.getElementById('mcx_filter');
                if (inp) {
                    inp.value = '';
                    inp.focus();
                }
            });
            document.querySelectorAll('#withdrawActionSheetForex_Crypto a[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function() {
                    const body = document.querySelector('#withdrawActionSheetForex_Crypto .modal-body');
                    if (body) body.scrollTop = 0;
                });
            });

            /* Expose globals */
            window.OPENMODALMCXNSE = OPENMODALMCXNSE;
            window.ChangeText = ChangeText;
            window.OpenWatchListModal = OpenWatchListModal;
            window._doSearch = _doSearch;
            window.seachFilter = seachFilter;
            window.toggleMarketWatch = toggleMarketWatch;
            window.buyfc = buyfc;
            window.sellfc = sellfc;
            window.buyplacedorder = buyplacedorder;
            window.sellplacedorder = sellplacedorder;
        })();
    </script>

    <script>
        (function() {
            "use strict";
            window.history.pushState({
                page: 1
            }, "", "");
            window.addEventListener("popstate", function() {
                window.location.reload();
                window.history.pushState({
                    page: 1
                }, "", "");
            });
        })();
    </script>

    <script>
        (function() {

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
@endsection
