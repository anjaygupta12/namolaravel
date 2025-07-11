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

        /* Make form elements fully responsive */
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
                min-width: 50%;
            }
        }

        .swal2-container {
            z-index: 200000 !important;
            position: fixed !important;
        }
    </style>

    <style>
        .card-block {
            padding: 15px;
        }

        .market-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .search-box {
            margin-bottom: 15px;
        }

        .stock-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .stock-item:last-child {
            border-bottom: none;
        }

        .price-up {
            color: #28a745;
        }

        .price-down {
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
    <div id="appCapsule">
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <label id="lblTransactionMode" style="display:none">MCX</label>
                <ul class="nav nav-tabs lined" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#mcx" onclick="ChangeText('MCX');"
                            role="tab">MCX Futures
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#nse" onclick="ChangeText('NSE');"
                            role="tab">NSE
                            Futures
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#options" onclick="ChangeText('OPTIONS');"
                            role="tab">Options
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#comex" onclick="ChangeText('COMEX');"
                            role="tab">Comex
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#forex" onclick="ChangeText('CRYPTO_FOR');"
                            role="tab">Forex & Crypto
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="mcx" role="tabpanel">

                        <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                            <input type="text" name="mcx_scrips" id="scrips_search_btn" placeholder="Search &amp; Add"
                                onclick="OpenWatchListModal('MCX',this)" onkeyup="OpenWatchListModal('MCX', this)"
                                style="width:100%;">

                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="btlMCX">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nse" role="tabpanel">

                        <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                            <input type="text" name="mcx_scrips" id="scrips_search_btn1" placeholder="Search &amp; Add"
                                onclick="OpenWatchListModal('NSE',this)" onkeyup="OpenWatchListModal('MCX', this)"
                                style="width: 100%;">

                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="tblNSE">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="options" role="tabpanel">

                        <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                            <input type="text" name="mcx_scrips" id="scrips_search_btn51" placeholder="Search &amp; Add"
                                onclick="OpenWatchListModal('OPTIONS',this)" onkeyup="OpenWatchListModal('MCX', this)"
                                style="width: 100%;">

                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="tblOPTIONS">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="comex" role="tabpanel">
                        <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                            <input type="text" name="mcx_scrips" id="scrips_search_btn511" placeholder="Search &amp; Add"
                                onclick="OpenWatchListModal('COMEX',this)" onkeyup="OpenWatchListModal('MCX', this)"
                                style="width: 100%;">

                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="btlCOMEX">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="forex" role="tabpanel">
                        <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                            <input type="text" name="mcx_scrips" id="scrips_search_btn51121"
                                placeholder="Search &amp; Add" onclick="OpenWatchListModal('CRYPTO',this)"
                                onkeyup="OpenWatchListModal('CRYPTO', this)" style="width: 100%;">

                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="tblforex">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        <div class="modal fade action-sheet" id="mcxpop" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color: #1c1c1c;">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal">Close</button>
                        <input type="text" name="mcx_filter" id="mcx_filter" onkeyup="seachFilter()"
                            placeholder="Search &amp; add" class="w-75">

                    </div>
                    <div class="modal-body">
                        <div class="card" style="background-color: #1c1c1c;">
                            <div class="card-body pt-0" style="height: 500px;overflow: scroll;">
                                <div class="mcx_search_modal active" id="mcx_search">
                                    <div class="mcx_search_header">
                                        <div class="icn" onclick="search_modal_hide(); market_watch();"></div>
                                        <div class="mcx_search_input">

                                        </div>
                                    </div>

                                    <div class="mcx_search_middle_cntnt">
                                        <div class="mxc_list">
                                            <table class="table" style="color:#fff">
                                                <tbody id="search_datamain">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade action-sheet" id="nsepop" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color: #311b7f;">
                    <div class="modal-header">
                        <h5 class="modal-title text-white fw-bold"></h5>
                    </div>
                    <div class="modal-body">
                        <div class="card" style="background-color: #311b7f;">
                            <div class="card-body pt-0">
                                <div class="mcx_search_modal active" id="mcx_search1">
                                    <div class="mcx_search_header">
                                        <div class="icn" onclick="search_modal_hide(); market_watch();"><i
                                                class="fas fa-chevron-left"></i></div>
                                        <div class="mcx_search_input">
                                            <input type="text" name="mcx_filter" id="mcx_filter"
                                                onkeyup="fetch_scrips('Future', this.value)"
                                                placeholder="Search &amp; add">
                                            <button type="button" class="clear_btn"
                                                onclick="clear_search('Future', 'mcx_filter')">Clear</button>
                                        </div>
                                    </div>


                                    <div class="mcx_search_middle_cntnt">
                                        <div class="mxc_list">
                                            <table class="table" style="color:#fff">
                                                <tbody id="search_data">
                                                    <tr>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="title">AARTIIND MAR 385 CE</p>
                                                                <p class="id">2025-03-27</p>
                                                                <p class="chg">Lot Size:1000</p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="chg">H:<span
                                                                        id="search_AARTIIND25MAR385CE_High">12.35</span>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="title_number"
                                                                    id="search_AARTIIND25MAR385CE_Bid">10</p>
                                                                <p class="chg">L: <span
                                                                        id="search_AARTIIND25MAR385CE_Low">9</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="title_number"
                                                                    id="search_AARTIIND25MAR385CE_Ask">10.2</p>
                                                                <p class="chg">O: 10</p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <input type="checkbox" name="mcx_search"
                                                                    id="search_AARTIIND25MAR385CE_check" class="check_box"
                                                                    onclick="process_market_watch('AARTIIND25MAR385CE');">
                                                                <div class="check_mark"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="title">AARTIIND MAR 385 PE</p>
                                                                <p class="id">2025-03-27</p>
                                                                <p class="chg">Lot Size:1000</p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="chg">H:<span
                                                                        id="search_AARTIIND25MAR385PE_High">6.85</span>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="title_number"
                                                                    id="search_AARTIIND25MAR385PE_Bid">6.35</p>
                                                                <p class="chg">L: <span
                                                                        id="search_AARTIIND25MAR385PE_Low">4.95</span>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <p class="title_number"
                                                                    id="search_AARTIIND25MAR385PE_Ask">6.65</p>
                                                                <p class="chg">O: 6</p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="list_cntnt">
                                                                <input type="checkbox" name="mcx_search"
                                                                    id="search_AARTIIND25MAR385PE_check" class="check_box"
                                                                    onclick="process_market_watch('AARTIIND25MAR385PE');">
                                                                <div class="check_mark"></div>
                                                            </div>
                                                        </td>
                                                    </tr>





                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade action-sheet" id="withdrawActionSheetForex_Crypto" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="background-color: #311b7f;">
                    <div class="modal-header">
                        <h5 class="modal-title text-white fw-bold"><span id="lblsymbol"></span></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="card" style="background-color: #311b7f;">
                            <div class="card-body pt-0 px-2">
                                <ul class="nav nav-tabs lined flex-nowrap" role="tablist" style="background: #142e46">
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
                                    <!-- Market Tab -->
                                    <div class="tab-pane fade show active" id="overview2" role="tabpanel">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="chkminMarket"
                                                    name="Mode" checked>
                                                <label class="form-check-label text-white" for="chkminMarket">Min</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="chkmegaMarket"
                                                    name="Mode">

                                            </div>
                                        </div>

                                        <div class="form-group basic mb-3">
                                            <label class="label text-white" for="textfclot">Lots</label>
                                            <input type="number" class="form-control" id="textfclot"
                                                placeholder="Enter Lots" value="1">
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <button class="btn btn-danger w-100 py-3" onclick="sellfc();"
                                                    style="background: #b24153;">
                                                    <div class="d-block">Sell </div>
                                                    <div class="fw-bold fs-4" id="tblfcsellprice"> 0</div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success w-100 py-3" onclick="buyfc();"
                                                    style="background: #208549;">
                                                    <div class="d-block">Buy </div>
                                                    <div class="fw-bold fs-4" id="tblfcbuyprice">0</div>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Bid</small>
                                                            <div class="text-white" id="lblBid">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Ask</small>
                                                            <div class="text-white" id="lblAsk">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Last</small>
                                                            <div class="text-white" id="lblLast">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">High</small>
                                                            <div class="text-white" id="lblHigh">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Low</small>
                                                            <div class="text-white" id="lblLow">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Change</small>
                                                            <div class="text-white" id="lblChange">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Open</small>
                                                            <div class="text-white" id="lblOpen">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Volume</small>
                                                            <div class="text-white" id="lblVolume">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Last Traded Qty</small>
                                                            <div class="text-white" id="lblLastTradedQty">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Atp</small>
                                                            <div class="text-white" id="lblAtp">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Lot Size</small>
                                                            <div class="text-white" id="lblLotSize">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Open Interest</small>
                                                            <div class="text-white" id="lblOpenInterest">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Bid Qty</small>
                                                            <div class="text-white" id="lblBidQty">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Ask Qty</small>
                                                            <div class="text-white" id="lblAskQty">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Prev. Close</small>
                                                            <div class="text-white" id="lblPrevClose">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Upper Circuit</small>
                                                            <div class="text-white" id="lblUpperCircuit">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Lower Circuit</small>
                                                            <div class="text-white" id="lblLowerCircuit">0</div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Order Tab -->
                                    <div class="tab-pane fade" id="cards2" role="tabpanel">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="chkminOrder"
                                                    name="type" checked>
                                                <label class="form-check-label text-white" for="chkminOrder">Min</label>
                                            </div>

                                        </div>

                                        <div class="form-group basic mb-2">
                                            <label class="label text-white" for="txtLots">Lots</label>
                                            <input type="number" class="form-control" id="txtLots"
                                                placeholder="Enter Lots" value="1">
                                        </div>

                                        <div class="form-group basic mb-3">
                                            <label class="label text-white" for="txtPriceOrder">Price</label>
                                            <input type="number" class="form-control" id="txtPriceOrder"
                                                placeholder="Enter Price" value="">
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <button class="btn btn-danger w-100 py-2" onclick="sellplacedorder();"
                                                    style="background: #b24153;">
                                                    Place Sell Order
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-success w-100 py-2" onclick="buyplacedorder();"
                                                    style="background: #208549;">
                                                    Place Buy Order
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Bid</small>
                                                            <div class="text-white" id="lblbid">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Ask</small>
                                                            <div class="text-white" id="lblASK">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Last</small>
                                                            <div class="text-white" id="lblLast1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">High</small>
                                                            <div class="text-white" id="lblHigh1">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Low</small>
                                                            <div class="text-white" id="lblLow1">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Change</small>
                                                            <div class="text-white" id="lblChange1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Open</small>
                                                            <div class="text-white" id="lblOpen1">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Volume</small>
                                                            <div class="text-white" id="lblVolume1">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Last Traded Qty</small>
                                                            <div class="text-white" id="lblLastTradedQty1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Atp</small>
                                                            <div class="text-white" id="lblAtp2">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Lot Size</small>
                                                            <div class="text-white" id="lblLotSize2">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Open Interest</small>
                                                            <div class="text-white" id="lblOpenInterest2">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Bid Qty</small>
                                                            <div class="text-white" id="lblBidQty2">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Ask Qty</small>
                                                            <div class="text-white" id="lblAskQty2">0</div>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="text-white-50">Prev. Close</small>
                                                            <div class="text-white" id="lblprev_close_price1">0</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <small class="text-white-50">Upper Circuit</small>
                                                            <div class="text-white" id="lblUpperCircuit1">0</div>
                                                        </td>
                                                        <td>
                                                            <small class="text-white-50">Lower Circuit</small>
                                                            <div class="text-white" id="lblLowerCircuit1">0</div>
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
        $(document).ready(function() {
            // Initialize WebSocket connection
            let socket;
            let marketData = {};
            let intervalId = null;
            let cache = {}; // Simple cache object

            // Connect to WebSocket
            function connect() {
                try {
                    const userId = "{{ Auth::guard('tradeuser')->user()->id }}";
                    const wsUrl = 'ws://namotraders.in:5005/ws?userId=4';
                    //   const wsUrl = 'ws://127.0.0.1:5005';
                    console.log('Connecting to WebSocket at:', wsUrl);

                    if (socket) {
                        socket.close();
                    }

                    socket = new WebSocket(wsUrl);

                    socket.onopen = function() {
                        console.log('WebSocket connected');
                    };

                    socket.onclose = function(event) {
                        console.log('WebSocket disconnected');
                    };

                    socket.onerror = function(error) {
                        console.error('WebSocket error:', error);
                    };

                    socket.onmessage = function(event) {
                        try {
                            const data = JSON.parse(event.data);

                            if (Array.isArray(data)) {
                                data.forEach(function(item) {
                                    
                                    if (item && item.Data) {
                                        const symbol = item.name;
                                        cache[symbol] = item;
                                        var exchange = symbol.split(":")[0];
                                        var symbolFullName = symbol.split(":")[1];
                                        // Update MCX table
                                        if (symbol.slice(-2) === "CE" || symbol.slice(-2) === "PE") {
                                            if ($("#" + CSS.escape(symbol)).length > 0) {

                                                $("#" + CSS.escape(symbol) + " td:eq(0)").html(
                                                    `<h4 class="comodity">${symbolFullName}</h4>
                                                    <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                    <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(1)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">L: <span>${parseFloat(item.Data.low_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(2)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>`
                                                );

                                            } else {

                                                const rowHTML = `
                                                <tr id="${symbol}" data-bs-toggle="modal" onclick="OPENMODALMCXNSE('${symbol}', 0, 0);">
                                                    <td scope="row">
                                                        <h4 class="comodity">${item.Data.symbolShortName}</h4>
                                                        <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                        <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                        <p class="text-white fw-bold">L: <span>${item.Data.low_price || 0}</span></p>
                                                    </td>
                                                    <td class="text-end text-primary">
                                                        <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                        <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>
                                                    </td>
                                                </tr>`;
                                                $("#tblOPTIONS").append(rowHTML);
                                            }
                                        } else if (exchange == 'MCX') {

                                            if ($("#" + CSS.escape(symbol)).length > 0) {

                                                $("#" + CSS.escape(symbol) + " td:eq(0)").html(
                                                    `<h4 class="comodity">${item.Data.symbolShortName}</h4>
                                                    <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                    <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(1)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">L: <span>${parseFloat(item.Data.low_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(2)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>`
                                                );

                                            } else {

                                                const rowHTML = `
                                                <tr id="${symbol}" data-bs-toggle="modal" onclick="OPENMODALMCXNSE('${symbol}', 0, 0);">
                                                    <td scope="row">
                                                        <h4 class="comodity">${item.Data.symbolShortName}</h4>
                                                        <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                        <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                        <p class="text-white fw-bold">L: <span>${item.Data.low_price || 0}</span></p>
                                                    </td>
                                                    <td class="text-end text-primary">
                                                        <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                        <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>
                                                    </td>
                                                </tr>`;
                                                $("#btlMCX").append(rowHTML);
                                                sortTableRowsById("#btlMCX");
                                            }
                                        } else if (exchange == 'NSE') {
                                            if ($("#" + CSS.escape(symbol)).length > 0) {

                                                $("#" + CSS.escape(symbol) + " td:eq(0)").html(
                                                    `<h4 class="comodity">${item.Data.symbolShortName}</h4>
                                                    <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                    <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(1)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">L: <span>${parseFloat(item.Data.low_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(2)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>`
                                                );

                                            } else {
                                                const rowHTML = `
                                                <tr id="${symbol}" data-bs-toggle="modal" onclick="OPENMODALMCXNSE('${symbol}', 0, 0);">
                                                    <td scope="row">
                                                        <h4 class="comodity">${item.Data.symbolShortName}</h4>
                                                        <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                        <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                        <p class="text-white fw-bold">L: <span>${item.Data.low_price || 0}</span></p>
                                                    </td>
                                                    <td class="text-end text-primary">
                                                        <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                        <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>
                                                    </td>
                                                </tr>`;
                                                $("#tblNSE").append(rowHTML);
                                                sortTableRowsById("#tblNSE");
                                            }
                                        } else if (exchange == 'OPTIONS') {
                                            if ($("#" + CSS.escape(symbol)).length > 0) {

                                                $("#" + CSS.escape(symbol) + " td:eq(0)").html(
                                                    `<h4 class="comodity">${symbolFullName}</h4>
                                                    <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                    <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(1)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">L: <span>${parseFloat(item.Data.low_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(2)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>`
                                                );

                                            } else {

                                                const rowHTML = `
                                        <tr id="mcx" data-bs-toggle="modal" onclick="OPENMODALMCXNSE('${symbol}', 0, 0);">
                                            <td scope="row">
                                                <h4 class="comodity">${symbolFullName}</h4>
                                                <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>
                                            </td>
                                            <td>
                                                <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                <p class="text-white fw-bold">L: <span>${item.Data.low_price || 0}</span></p>
                                            </td>
                                            <td class="text-end text-primary">
                                                <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>
                                            </td>
                                        </tr>`;
                                                $("#tblOPTIONS").append(rowHTML);
                                                sortTableRowsById("#tblOPTIONS");
                                            }
                                        } else if (exchange == 'COMEX') {
                                            if ($("#" + CSS.escape(symbol)).length > 0) {

                                                $("#" + CSS.escape(symbol) + " td:eq(0)").html(
                                                    `<h4 class="comodity">${item.Data.symbolShortName}</h4>
                                                    <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                    <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span> H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(1)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">L: <span>${parseFloat(item.Data.low_price || 0).toFixed(2)}</span></p>`
                                                );

                                                $("#" + CSS.escape(symbol) + " td:eq(2)").html(
                                                    `<p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                    <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>`
                                                );

                                            } else {

                                                const rowHTML = `
                                        <tr id="mcx" data-bs-toggle="modal" onclick="OPENMODALMCXNSE('${symbol}', 0, 0);">
                                            <td scope="row">
                                                <h4 class="comodity">${item.Data.symbolShortName}</h4>
                                                <p class="date">${formatDateTime(item.Data.expiryDate) || ''}</p>
                                                <p class="detail">Chg:<span>${parseFloat(item.Data.ch || 0).toFixed(2)}</span>H:<span>${parseFloat(item.Data.high_price || 0).toFixed(2)}</span></p>
                                            </td>
                                            <td>
                                                <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) < parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.bid_price || 0).toFixed(2)}</p>
                                                <p class="text-white fw-bold">L: <span>${item.Data.low_price || 0}</span></p>
                                            </td>
                                            <td class="text-end text-primary">
                                                <p class="text-white fw-bold badge badge-${parseFloat(item.Data.ltp || 0).toFixed(2) > parseFloat(item.Data.ask_price || 0).toFixed(2) ? 'danger' : 'success'}">${parseFloat(item.Data.ask_price || 0).toFixed(2)}</p>
                                                <p class="text-white fw-bold">O: ${parseFloat(item.Data.open_price || 0).toFixed(2)}</p>
                                            </td>
                                        </tr>`;
                                                $("#btlCOMEX").append(rowHTML);
                                                 sortTableRowsById("#btlCOMEX");
                                            }
                                        }

                                        // Store market data
                                        marketData[symbol] = item.Data;
                                    }
                                });
                            }
                        } catch (error) {
                            console.error('Error processing message:', error);
                        }
                    };
                } catch (error) {
                    console.error('WebSocket setup error:', error);
                }
            }

            function sortTableRowsById(containerSelector) {
            $(containerSelector).each(function () {
                var $tbody = $(this);
                var $rows = $tbody.find("tr");

                $rows.sort(function (a, b) {
                    var idA = $(a).attr("id")?.toLowerCase() || "";
                    var idB = $(b).attr("id")?.toLowerCase() || "";
                    return idA.localeCompare(idB);
                });

                $tbody.append($rows); 
            });
        }


            // Original OPENMODALMCXNSE function with minimal changes
            function OPENMODALMCXNSE(symbol, BuyPrice, Sellprice) {
                // Clear previous interval
               
                if (intervalId) {
                    clearInterval(intervalId);
                    intervalId = null;
                }
                
                $("#lblsymbol").html(symbol);
                $("#tblfcsellprice").html('0');
                $("#tblfcbuyprice").html('0');
                $("#lblLotSize").html('1');
                $("#lblVolume1").html('1');

                // Get lot size
                var _data = {
                    Symbol: symbol
                };
                
                _data = JSON.stringify(_data);
                $.ajax({
                    type: "POST",
                    url: "{{ route('get.symbol') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        Symbol: $("#symbolInput").val()
                    },
                    success: function(data) {
                        if (data.segment && data.segment !== '') {
                            $("#lblLotSize").html(data.segment);
                            $("#lblVolume1").html(data.segment);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                    }
                });


                // Show modal
                $("#withdrawActionSheetForex_Crypto").modal('show');

                // Update data when modal is shown
                $('#withdrawActionSheetForex_Crypto').on('shown.bs.modal', function() {
                    
   
                    intervalId = setInterval(function() {
                        const symbolKey = $("#lblsymbol").text()
                       
                        const item = cache[symbolKey] || cache[symbol]; 
                        if (!item || !item.Data) {
                            console.log('No data for symbol:', symbolKey);
                            return;
                        }
                        
                        const data = item.Data;
                      
                        // Update prices
                        $("#tblfcbuyprice").html(data.bid_price || '0');
                        $("#tblfcsellprice").html(data.ask_price || '0');

                        // Update other fields exactly as in your original code
                        $("#lblBid").html(data.ask_price || '0');
                        $("#lblbid").html(data.bid_price || '0');
                        $("#lblLast").html(data.ltp || '0');
                        $("#lblLast1").html(data.ltp || '0');
                        $("#lblHigh1").html(data.high_price || '0');
                        $("#lblHigh").html(data.high_price || '0');
                        $("#lblLow1").html(data.low_price || '0');
                        $("#lblLow").html(data.low_price || '0');
                        $("#lblChange").html(data.ch || '0');
                        $("#lblChange1").html(data.ch || '0');
                        $("#lblBidQty").html(data.ask_size || '0');
                        $("#lblBidQty1").html(data.ask_size || '0');
                        $("#lblBidQty2").html(data.ask_size || '0');
                        $("#lblAskQty").html(data.bid_size || '0');
                        $("#lblAskQt1").html(data.bid_size || '0');
                        $("#lblVolume").html(data.vol_traded_today || '0');
                        $("#lblOpen1").html(data.open_price || '0');
                        $("#lblOpenInterest2").html(data.open_price || '0');
                        $("#lblOpen").html(data.open_price || '0');
                        $("#lblASK").html(data.ask_price || '0');
                        $("#lblAsk").html(data.bid_price || '0');
                        $("#lblLastTradedQty").html(data.last_traded_qty || '0');
                        $("#lblLastTradedQty1").html(data.last_traded_qty || '0');
                        $("#lblUpperCircuit").html(data.upper_ckt || '0');
                        $("#lblLowerCircuit").html(data.lower_ckt || '0');
                        $("#lblUpperCircuit1").html(data.upper_ckt || '0');
                        $("#lblLowerCircuit1").html(data.lower_ckt || '0');
                        $("#lblprev_close_price1").html(data.prev_close_price || '0');
                        $("#lblprev_close_price").html(data.prev_close_price || '0');
                        $("#lblPrevClose").html(data.prev_close_price || '0');
                        $("#lblPrevClose1").html(data.prev_close_price || '0');
                        $("#lblAtp").html(data.avg_trade_price || '0');
                        $("#lblAtp1").html(data.avg_trade_price || '0');
                        $("#lblAtp2").html(data.avg_trade_price || '0');

                    }, 100); 
                });

                // Clean up when modal closes
                $('#withdrawActionSheetForex_Crypto').on('hidden.bs.modal', function() {
                  
                    if (intervalId) {
                        //clearInterval(intervalId);
                        intervalId = null;
                       
                    }
                });
            }

            // Initialize connection
            connect();

            // Make function available globally
            window.OPENMODALMCXNSE = OPENMODALMCXNSE;
        });


        function OpenWatchListModal(Option, textbox) {
            // Set global search option
            OptionSearch = Option;
            ClientId = "{{ Auth::guard('tradeuser')->user()->id }}";


            // Only proceed if we have a valid option
            if (Option != '') {
                // Show loading state
                $('#search_datamain').html(
                    '<tr><td colspan="6"><div class="text-center py-3">Loading instruments...</div></td></tr>');
                $("#mcxpop").modal('show');

                // Make API request
                fetch(`/getdata/?query=${encodeURIComponent(textbox.value)}&type=${Option}&clientid=${ClientId}`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error(`Network error: ${res.status} ${res.statusText}`);
                        }
                        return res.json();
                    })
                    .then(data => {
                        console.log('API Response Data:', data);

                        // Handle empty results
                        if (!data || data.length === 0) {
                            $('#search_datamain').html(
                                '<tr><td colspan="6"><div class="text-center py-3">No matching instruments found</div></td></tr>'
                            );
                            return;
                        }

                        let html = '';

                        // Process each instrument
                        data.forEach(item => {
                          
                            const symbol = item.Symbol || 'N/A';
                            const description = item.Description || symbol;
                            const exchange = item.TERMINAL || item.Exchange || 'N/A';
                            const isActive = Boolean(item.ckecked || item.ckecked);
                            const lotSize = item.LotSize ? parseInt(item.LotSize) : 'N/A';
                            const instrumentType = item.InstrumentType || 'N/A';
                              console.log(isActive,'testing',item.ckecked);
                            // Extract market data
                            const marketData = item.Data || {};
                            const lastUpdate = marketData.last_traded_time ?
                                new Date(marketData.last_traded_time * 1000).toLocaleString() : 'N/A';
                            const expiryDate = marketData.expiry_date ?
                                new Date(marketData.expiry_date).toLocaleDateString() : 'N/A';
                            const strikePrice = marketData.strike_price || 'N/A';
                            const optionType = marketData.option_type || '';

                            // Build instrument info string
                            let infoString = `Lot: ${lotSize}`;
                            if (expiryDate !== 'N/A') infoString += ` | Exp: ${expiryDate}`;
                            if (strikePrice !== 'N/A') infoString += ` | Strike: ${strikePrice}`;
                            if (optionType) infoString += ` (${optionType})`;

                            // Determine price display based on instrument type
                            let priceDisplay = '';
                            if (['CE', 'PE', 'FUT'].includes(instrumentType)) {
                                priceDisplay = `
                            <p class="chg">LTP: <span id="search_${symbol}_Price">${marketData.last_price || 'N/A'}</span></p>
                            <p class="chg">OI: <span id="search_${symbol}_OI">${marketData.open_interest || 'N/A'}</span></p>
                        `;
                            } else {
                                priceDisplay = `
                            <p class="chg">Bid: <span id="search_${symbol}_Bid">${marketData.bid_price || 'N/A'}</span></p>
                            <p class="chg">Ask: <span id="search_${symbol}_Ask">${marketData.ask_price || 'N/A'}</span></p>
                        `;
                            }

                            // Build the table row
                            html += `
                    <tr>
                        <td>
                            <div class="list_cntnt">
                                <p class="title">${symbol}</p>
                                <p class="id">${description}</p>
                                <p class="chg">${infoString}</p>
                            </div>
                        </td>
                        <td>
                            <div class="list_cntnt">
                                ${priceDisplay}
                            </div>
                        </td>
                        <td>
                            <div class="list_cntnt">
                                <p class="title_number">${exchange}</p>
                                <p class="chg">${instrumentType}</p>
                            </div>
                        </td>
                        <td>
                            <div class="list_cntnt">
                                <p class="chg">Updated: ${lastUpdate}</p>
                                <p class="chg">Tick: ${marketData.tick_size || 'N/A'}</p>
                            </div>
                        </td>
                        <td>
                            <div class="list_cntnt">
                                ${isActive ?
                                    `<input type="checkbox" name="market_search" id="search_${symbol}_check" checked 
                                                        class="check_box" onclick="toggleMarketWatch('${symbol}', '${exchange}', false);">` :
                                    `<input type="checkbox" name="market_search" id="search_${symbol}_check" 
                                                        class="check_box" onclick="toggleMarketWatch('${symbol}', '${exchange}', true);">`
                                }
                                <div class="check_mark"></div>
                            </div>
                        </td>
                    </tr>`;
                        });

                        // Update the table and show modal
                        $('#search_datamain').html(html);

                        // Initialize tooltips if using Bootstrap
                        if (typeof $().tooltip === 'function') {
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    })
                    .catch(error => {
                        console.error('Market Data Error:', error);
                        $('#search_datamain').html(`
                    <tr>
                        <td colspan="6">
                            <div class="alert alert-danger m-2">
                                Failed to load instruments: ${error.message}
                                <button onclick="OpenWatchListModal('${Option}', document.getElementById('${textbox.id}'))" 
                                    class="btn btn-sm btn-warning float-right">Retry</button>
                            </div>
                        </td>
                    </tr>
                `);
                    });
            }
        }

        // Helper function for watchlist toggle
        function toggleMarketWatch(symbol, exchange, shouldAdd) {
            const action = shouldAdd ? 'add' : 'remove';
            console.log(`${action.toUpperCase()} ${symbol} from ${exchange} watchlist`);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch("{{ route('watchlist.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        action: action,
                        symbol: symbol,
                        exchange: exchange,
                        clientId: ClientId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const checkbox = document.getElementById(`search_${symbol}_check`);
                        if (checkbox) {
                            checkbox.checked = shouldAdd;
                            checkbox.onclick = function() {
                                toggleMarketWatch(symbol, exchange, !shouldAdd);
                            };
                        }
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: 'Failed to ' + action + 'instrument:' + data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error(`Watchlist ${action} error:`, error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: 'Network error - please try again',
                        timer: 1500,
                        showConfirmButton: false
                    });

                });
        }


        function convertToDate(timestamp) {

            var dateUtc = new Date(timestamp * 1000);

            // Convert to IST
            var dateIst = new Date(dateUtc.getTime() + (5.5 * 60 * 60 * 1000));

            // Format to dd-mm-yyyy
            var day = String(dateIst.getDate()).padStart(2, '0');
            var month = String(dateIst.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            var year = dateIst.getFullYear();

            var formattedDate = `${day}-${month}-${year}`;
            return formattedDate;
        }


        function buyfc() {
            if ($("#textfclot").val() !== '' && $("#textfclot").val() !== '0') {

                let _data = {
                    _token: "{{ csrf_token() }}",
                    Mode: 'BUY',
                    Symbol: $("#lblsymbol").html(),
                    textfclot: $("#textfclot").val(),
                    Min: document.getElementById("chkminMarket").checked,
                    Mega: document.getElementById("chkmegaMarket").checked,
                    Lots: $("#textfclot").val(),
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
                    tblfcbuyprice: $("#tblfcbuyprice").html(),
                    // tblfcsellprice: $("#tblfcsellprice").html(),
                    Price: '0',
                    TransactionMode: $("#lblTransactionMode").html()
                };

                $.ajax({
                    type: "POST",
                    url: "{{ url('/save-transaction') }}",
                    data: _data,
                    success: function(response) {
                        $("#withdrawActionSheetForex_Crypto").modal('hide');

                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.href = '/trades#Active';
                        }, 1700);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: errorMessage,
                            timer: 1500, // 1.5 seconds
                            showConfirmButton: false
                        });

                    }
                });

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Enter Your Values!",
                    timer: 1500, // 1.5 seconds
                    showConfirmButton: false
                });

                return;
            }
        }

        function sellplacedorder() {
            if ($("#txtLots").val() != '' && $("#txtLots").val() != '0' && $("#txtPriceOrder").val() != '' && $(
                    "#txtPriceOrder").val() != '0') {
                // $("#txtplaceamount").val();
                var amount = $("#txtPriceOrder").val();
                var lowValue = $("#lblLow").html()

                if (amount > lowValue) {
                       Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Amount can't be Greater than the low value.",
                        });

                    return false;
                }

                $("#lblBid").html();
                $("#lblAsk").html();
                $("#lblLast").html();
                $("#lblHigh").html();
                $("#lblLow").html();
                $("#lblChange").html();
                $("#lblOpen").html();
                $("#lblVolume").html();
                $("#lblLastTradedQty").html();
                $("#lblAtp").html();
                $("#lblLotSize").html();
                $("#lblOpenInterest").html();
                $("#lblBidQty").html();
                $("#lblAskQty").html();
                $("#lblPrevClose").html();
                $("#lblUpperCircuit").html();
                $("#lblLowerCircuit").html();
                var _dataOrderSell = {

                    _token: "{{ csrf_token() }}",
                    Mode: 'SELL',
                    isOrder: true,
                    Symbol: $("#lblsymbol").html(),
                    textfclot: '0',
                    Min: document.getElementById("chkminMarket").checked,
                    Mega: document.getElementById("chkmegaMarket").checked,
                    Lots: $("#txtLots").val(),
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
                    tblfcbuyprice: $("#tblfcbuyprice").val(),
                    // tblfcsellprice: $("#tblfcsellprice").val(),
                    // tblfcbuyprice: $("#txtPriceOrder").val(),
                    tblfcbuyprice: amount,
                    TransactionMode: $("#lblTransactionMode").html()

                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('save.transaction') }}",
                    data: _dataOrderSell,
                    success: function(response) {
                        $("#withdrawActionSheetForex_Crypto").modal('hide');

                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.href = '/trades';
                        }, 1700);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: 'Something went wrong: ' + xhr.responseText,
                            timer: 1500, // 1.5 seconds
                            showConfirmButton: false
                        });
                    }
                });
            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Enter Your Price!",
                    timer: 1500, // 1.5 seconds
                    showConfirmButton: false
                });
                return false;
            }

        }

        function buyplacedorder() {

            if ($("#txtLots").val() != '' && $("#txtLots").val() != '0' && $("#txtPriceOrder").val() != '' && $(
                    "#txtPriceOrder").val() != '0') {

                var amount = $("#txtPriceOrder").val();
                var lowValue = $("#lblLow").html()

                if (amount > lowValue) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Can't take Gratter then Low value",
                        timer: 1500, // 1.5 seconds
                        showConfirmButton: false
                    });
                    return false;
                }

                $("#lblBid").html();
                $("#lblAsk").html();
                $("#lblLast").html();
                $("#lblHigh").html();
                $("#lblLow").html();
                $("#lblChange").html();
                $("#lblOpen").html();
                $("#lblVolume").html();
                $("#lblLastTradedQty").html();
                $("#lblAtp").html();
                $("#lblLotSize").html();
                $("#lblOpenInterest").html();
                $("#lblBidQty").html();
                $("#lblAskQty").html();
                $("#lblPrevClose").html();
                $("#lblUpperCircuit").html();
                $("#lblLowerCircuit").html();
                var _data = {
                    Mode: 'BUY',
                    _token: "{{ csrf_token() }}",
                    isOrder: true,
                    Symbol: $("#lblsymbol").html(),
                    textfclot: '0',
                    Min: document.getElementById("chkminMarket").checked,
                    Mega: document.getElementById("chkmegaMarket").checked,
                    Lots: $("#txtLots").val(),
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
                    tblfcbuyprice: $("#tblfcbuyprice").val(),
                    // tblfcsellprice: $("#tblfcsellprice").val(),
                    tblfcbuyprice: amount,
                    TransactionMode: $("#lblTransactionMode").html()


                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('save.transaction') }}",
                    data: _data,
                    success: function(response) {
                        $("#withdrawActionSheetForex_Crypto").modal('hide');
                        $("#withdrawActionSheetForex_Crypto").modal('hide');
                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.href = '/trades';
                        }, 1700);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: 'Something went wrong: ' + xhr.responseText,
                            timer: 1500, // 1.5 seconds
                            showConfirmButton: false
                        });

                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Enter Your Price",
                    timer: 1500, // 1.5 seconds
                    showConfirmButton: false
                });
                return;
            }
        }



        function sellfc() {

            if ($("#textfclot").val() !== '' && $("#textfclot").val() !== '0') {

                let _data = {
                    _token: "{{ csrf_token() }}", // Laravel CSRF
                    Mode: 'SELL',
                    Symbol: $("#lblsymbol").html(),
                    textfclot: $("#textfclot").val(),
                    Min: document.getElementById("chkminMarket").checked,
                    Mega: document.getElementById("chkmegaMarket").checked,
                    Lots: $("#textfclot").val(),
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
                    // tblfcbuyprice: $("#tblfcbuyprice").html(),
                    // tblfcsellprice: $("#tblfcsellprice").html(),
                    tblfcbuyprice: $("#tblfcsellprice").html(),
                    Price: '0',
                    TransactionMode: $("#lblTransactionMode").html()
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('save.transaction') }}",
                    data: _data,
                    success: function(response) {

                        $("#withdrawActionSheetForex_Crypto").modal('hide');
                        $("#withdrawActionSheetForex_Crypto").modal('hide');
                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.href = '/trades#Active';
                        }, 1700);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: xhr.responseText,
                            timer: 1500,
                            showConfirmButton: false
                        });

                    }
                });

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Enter Your Value!",
                    timer: 1500, // 1.5 seconds
                    showConfirmButton: false
                });

                return;
            }
        }

        function ChangeText(lblTransactionMode) {
            $("#lblTransactionMode").html(lblTransactionMode);
        }


        document.addEventListener('DOMContentLoaded', function() {
            const modalBody = document.querySelector('#withdrawActionSheetForex_Crypto .modal-body');

            // On tab shown
            document.querySelectorAll('#withdrawActionSheetForex_Crypto a[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function() {
                    if (modalBody) {
                        modalBody.scrollTop = 0; // Scroll to top
                    }
                });
            });
        });

        function formatDateTime(input) {
            const date = new Date(input);

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months start from 0
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }
    </script>
@endsection
