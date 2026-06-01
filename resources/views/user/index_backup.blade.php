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
    /* .serach-models{
          max-height: 9000px!important;
    } */

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
        flex-wrap: nowrap;      /* force one line */
        justify-content: space-between;
    }

    .nav-tabs .nav-item {
        flex: 1 1 auto;         /* equal width tabs */
        text-align: center;
    }

    .nav-tabs .nav-link {
        padding: 6px 4px;       /* 🔽 reduced padding */
        font-size: 11px;        /* 🔽 smaller text */
        white-space: normal;    /* allow wrap inside tab */
        line-height: 1.2;
    }
}

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
            {{-- Determine which tab should be active first --}}
                @php
                    $activeTab = null;
                    if ($user->MCXEnabled == 1) $activeTab = 'mcx';
                    elseif ($user->NSEFuturesEnabled == 1) $activeTab = 'nse';
                    elseif ($user->NSEOptionsEnabled == 1) $activeTab = 'options';
                    elseif ($user->comex_trading_enabled == 1) $activeTab = 'comex';
                    elseif ($user->forex_trading_enabled == 1) $activeTab = 'forex';
                @endphp

                <ul class="nav nav-tabs lined" role="tablist">

                    @if($user->MCXEnabled == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'mcx' ? 'active' : '' }}" 
                        data-bs-toggle="tab" href="#mcx" onclick="ChangeText('MCX');" role="tab">
                            MCX Futures
                        </a>
                    </li>
                    @endif

                    @if($user->NSEFuturesEnabled == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'nse' ? 'active' : '' }}" 
                        data-bs-toggle="tab" href="#nse" onclick="ChangeText('NSE');" role="tab">
                            NSE Futures
                        </a>
                    </li>
                    @endif

                    @if($user->NSEOptionsEnabled == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'options' ? 'active' : '' }}" 
                        data-bs-toggle="tab" href="#options" onclick="ChangeText('OPTIONS');" role="tab">
                            Options
                        </a>
                    </li>
                    @endif

                    @if($user->comex_trading_enabled == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'comex' ? 'active' : '' }}" 
                        data-bs-toggle="tab" href="#comex" onclick="ChangeText('COMEX');" role="tab">
                            Comex
                        </a>
                    </li>
                    @endif

                    @if($user->forex_trading_enabled == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'forex' ? 'active' : '' }}" 
                        data-bs-toggle="tab" href="#forex" onclick="ChangeText('CRYPTO_FOR');" role="tab">
                            Forex & Crypto
                        </a>
                    </li>
                    @endif

                </ul>
            <div class="tab-content">
                 @if($user->MCXEnabled==1)
                <div class="tab-pane fade show {{ $activeTab === 'mcx' ? 'active' : '' }}" id="mcx" role="tabpanel">

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
                @endif
                  @if($user->NSEFuturesEnabled==1)
                <div class="tab-pane fade show {{ $activeTab === 'nse' ? 'active' : '' }} " id="nse" role="tabpanel">

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
                @endif
                 @if($user->NSEOptionsEnabled==1)
                <div class="tab-pane fade show {{ $activeTab === 'options' ? 'active' : '' }}" id="options" role="tabpanel">

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
                @endif
                  @if($user->comex_trading_enabled == 1)
                <div class="tab-pane fade show {{ $activeTab === 'comex' ? 'active' : '' }} " id="comex" role="tabpanel">
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
                @endif
                 @if($user->forex_trading_enabled == 1)
                <div class="tab-pane fade show {{ $activeTab === 'forex' ? 'active' : '' }} " id="forex" role="tabpanel">
                    <div class="future_search" data-bs-toggle="modal" data-bs-target="#mcxpop">
                        <input type="text" name="mcx_scrips" id="scrips_search_btn51121" placeholder="Search &amp; Add"
                            onclick="OpenWatchListModal('CRYPTO',this)" onkeyup="OpenWatchListModal('CRYPTO', this)"
                            style="width: 100%;">

                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody id="tblforex">

                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade action-sheet" id="mcxpop" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal">Close</button>
                   <input type="text" name="mcx_filter" class="search-input w-75" id="mcx_filter" 
       onkeyup="seachFilter()" placeholder="Search &amp; add">

                </div>
                <div class="modal-body serach-models">
                    <div class="card" style="">
                        <div class="card-body pt-0">
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
                 <!-- ✅ Added Footer Close Button -->
            <div class="modal-footer" style="border-top: 1px solid #333;">
                <button type="button" class="btn btn-danger w-10" data-bs-dismiss="modal">
                    Close
                </button>
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
                                            onkeyup="fetch_scrips('Future', this.value)" placeholder="Search &amp; add">
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
                                                            <p class="title_number" id="search_AARTIIND25MAR385CE_Bid">
                                                                10</p>
                                                            <p class="chg">L: <span
                                                                    id="search_AARTIIND25MAR385CE_Low">9</span></p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="list_cntnt">
                                                            <p class="title_number" id="search_AARTIIND25MAR385CE_Ask">
                                                                10.2</p>
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
                                                            <p class="title_number" id="search_AARTIIND25MAR385PE_Bid">
                                                                6.35</p>
                                                            <p class="chg">L: <span
                                                                    id="search_AARTIIND25MAR385PE_Low">4.95</span>
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="list_cntnt">
                                                            <p class="title_number" id="search_AARTIIND25MAR385PE_Ask">
                                                                6.65</p>
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
            <div class="modal-content" style="background-color: #ffffff;">
                <div class="modal-header">
                    <h5 class="modal-title text-white fw-bold"><span id="lblsymbol"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <div class="card" style="background-color: #ffffff;">
                        <div class="card-body pt-0 px-2">
                            <ul class="nav nav-tabs lined flex-nowrap" role="tablist" style="background: #ffffff">
                                <li class="nav-item flex-grow-1 text-center">
                                    <a class="nav-link active py-2" data-bs-toggle="tab" href="#overview2"
                                        role="tab">Market</a>
                                </li>
                                <li class="nav-item flex-grow-1 text-center">
                                    <a class="nav-link py-2" data-bs-toggle="tab" href="#cards2" role="tab">Order</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-2">
                                <!-- Market Tab -->
                                <div class="tab-pane fade show active" id="overview2" role="tabpanel">
                                    <div class="d-flex justify-content-between mb-2">
                                        <!-- <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="chkminMarket" name="Mode"
                                                checked>
                                            <label class="form-check-label text-white" for="chkminMarket">Min</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="chkmegaMarket" name="Mode">

                                        </div> -->
                                    </div>
                                   
                                    <div id="qty-section">
                                    <div class="form-group basic mb-3">
                                        <label class="label text-white" for="textfclot">Quentity</label>
                                        <input type="text" name="qty" class="form-control" id="nse-qty"
                                            placeholder="Enter Qty">
                                            <input type="hidden" class="form-control" value="1">
                                    </div>
                                    </div>
                                   
                                    <div id="lot-section">
                                        <div class="form-group basic mb-3">
                                            <label class="label text-white" for="textfclot">Lots</label>
                                            <input type="number" class="form-control" id="textfclot"
                                                placeholder="Enter Lots" value="1">
                                        </div>
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
                                       <!--  <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="chkminOrder" name="type"
                                                checked>
                                            <label class="form-check-label text-white" for="chkminOrder">Min</label>
                                        </div>-->

                                    </div>

                                    <div class="form-group basic mb-2">
                                        <label class="label text-white" for="txtLots">Lots</label>
                                        <input type="number" class="form-control" id="txtLots" placeholder="Enter Lots"
                                            value="1">
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
    $(document).ready(function () {

                // Add modal close event listener

        // Initialize WebSocket connection
        let socket;
        let marketData = {};
        let intervalId = null;
        let cache = {}; // Simple cache object

        // Connect to WebSocket

function connect(isRefresh = false) {
    try {
        const userId = "{{ Auth::guard('tradeuser')->user()->id }}";
        const wsUrl = 'wss://namotraders.in:5005/ws?userId=' + userId;
        // console.log('Connecting to WebSocket at:', wsUrl);
        const mcx_tab = "{{ $user->MCXEnabled }}";
        const nse_tab = "{{ $user->NSEFuturesEnabled }}";
        const option_tab = "{{ $user->NSEOptionsEnabled }}";
        // Reconnect cleanly if existing socket
        if (socket) {
            socket.onclose = null;
            socket.close();
        }

        socket = new WebSocket(wsUrl);

        socket.onopen = function () {
            console.log('WebSocket connected');
            if (!isRefresh) {
                // Clear only once (not on every reconnect)
                $("#btlMCX, #tblNSE, #btlCOMEX, #tblOPTIONS").empty();
            }
        };

        socket.onclose = function () {
            console.log('WebSocket disconnected');
        };

        socket.onerror = function (error) {
            console.error('WebSocket error:', error);
        };

socket.onmessage = function (event) {
    try {
        const data = JSON.parse(event.data);
        
        if (!Array.isArray(data)) return;

        // Use fragments for efficient DOM updates
        const fragments = {
            MCX: document.createDocumentFragment(),
            NSE: document.createDocumentFragment(),
            COMEX: document.createDocumentFragment(),
            OPTIONS: document.createDocumentFragment(),
        };

        data.forEach(function (item) {
            if (!item || !item.Data) return;
          
            const symbol = item.name;
            const exchange = symbol.split(":")[0];
            const symbolFullName = symbol.split(":")[1];
            const oldData = cache[symbol]?.Data || {};
            const newData = item.Data;

            // Skip if data hasn't changed
            if (JSON.stringify(oldData) === JSON.stringify(newData)) return;

            cache[symbol] = item;
            marketData[symbol] = newData;

            const isOption = symbol.includes('CE') || symbol.includes('PE');

            const $existingRow = $("#" + CSS.escape(symbol));
           
            const dataHtml = `
                <h4 class="comodity">${newData.symbolShortName || symbolFullName}</h4>
                <p class="comodity">${formatDateTime(newData.expiryDate) || ''}</p>
                <p class="comodity">
                    Chg:<span>${parseFloat(newData.ch || 0).toFixed(2)}</span>
                    H:<span>${parseFloat(newData.high_price || 0).toFixed(2)}</span>
                </p>`;

            const bidHtml = `
                <p class="text-white fw-bold badge badge-${parseFloat(newData.ltp || 0) < parseFloat(newData.ask_price || 0) ? 'danger' : 'success'}">
                    ${parseFloat(newData.bid_price || 0).toFixed(2)}
                </p>
                <p class="text-white fw-bold">L: <span>${parseFloat(newData.low_price || 0).toFixed(2)}</span></p>`;

            const askHtml = `
                <p class="text-white fw-bold badge badge-${parseFloat(newData.ltp || 0) > parseFloat(newData.ask_price || 0) ? 'danger' : 'success'}">
                    ${parseFloat(newData.ask_price || 0).toFixed(2)}
                </p>
                <p class="text-white fw-bold">O: ${parseFloat(newData.open_price || 0).toFixed(2)}</p>`;

            if ($existingRow.length) {
                // Update only changed parts
                const $tds = $existingRow.find('td');
                $tds.eq(0).html(dataHtml);
                $tds.eq(1).html(bidHtml);
                $tds.eq(2).html(askHtml);
            } else {
                // Add new row
                const row = document.createElement("tr");
                row.id = symbol;
                row.setAttribute("data-bs-toggle", "modal");
                row.setAttribute("onclick", `OPENMODALMCXNSE('${symbol}', 0, 0);`);
                row.innerHTML = `
                    <td scope="row">${dataHtml}</td>
                    <td>${bidHtml}</td>
                    <td class="text-end text-primary">${askHtml}</td>`;

                // ✅ Fixed: use isOption flag to route NSE options to correct fragment
                if (exchange === "MCX") {
                    fragments.MCX.appendChild(row);
                } else if (exchange === "COMEX") {
                    fragments.COMEX.appendChild(row);
                } else if (exchange === "NSE" && isOption) {
                    fragments.OPTIONS.appendChild(row);
                } else if (exchange === "NSE" && !isOption) {
                    fragments.NSE.appendChild(row);
                }
            }
        });
        const mcx_tab = "{{ $user->MCXEnabled }}";
        const nse_tab = "{{ $user->NSEFuturesEnabled }}";
        const option_tab = "{{ $user->NSEOptionsEnabled }}";
        if(mcx_tab==1){
            if (fragments.MCX.childNodes.length) $("#btlMCX")[0].appendChild(fragments.MCX);
        }
        if(nse_tab==1){
            if (fragments.NSE.childNodes.length) $("#tblNSE")[0].appendChild(fragments.NSE);
        }
        if(option_tab==1){
            if (fragments.OPTIONS.childNodes.length) $("#tblOPTIONS")[0].appendChild(fragments.OPTIONS);
        }
        // Append all fragments at once
        // if (fragments.COMEX.childNodes.length) $("#btlCOMEX")[0].appendChild(fragments.COMEX);

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
    var checkQty = "{{ $user->TradeEquityAsUnits}}";
    let exchange = symbol.split(':')[0];


    if (checkQty == 1 && exchange == 'NSE') {
        document.getElementById("lot-section").style.display = "none";
        document.getElementById("qty-section").style.display = "block";
        $('#lot-section :input').prop('disabled', false);
        
    } else {
        document.getElementById("lot-section").style.display = "block";
        document.getElementById("qty-section").style.display = "none";
        $("#nse-qty").val(''),
        $('#qty-section :input').prop('disabled', false);
        
    }

    // ✅ Clear any existing interval immediately
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }

    $("#lblsymbol").html(symbol);
    $("#tblfcsellprice").html('0');
    $("#tblfcbuyprice").html('0');
    $("#lblLotSize").html('');
    $("#lblVolume1").html('');

    const symbolKey = $("#lblsymbol").text();

    $.ajax({
        type: "POST",
        url: "{{ route('get.symbol') }}",
        data: {
            _token: "{{ csrf_token() }}",
            Symbol: symbolKey
        },
        success: function (data) {
            console.log('testing get surcit API',data);
            $("#lblUpperCircuit").html(data.upper_ckt || '0');
            $("#lblLowerCircuit").html(data.lower_ckt || '0');
            $("#lblLotSize").html(data.lotSize);
            $("#lblVolume1").html(data.lotSize);
        },
        error: function (xhr) {
            alert(xhr.responseText);
            console.error("Error:", xhr.responseText);
        }
    });

    // ✅ Remove OLD event listeners before adding new ones (prevents stacking)
    $('#withdrawActionSheetForex_Crypto').off('shown.bs.modal').off('hidden.bs.modal');

    // Show modal
    $("#withdrawActionSheetForex_Crypto").modal('show');

    // ✅ Fresh listener — fires only once per open
    $('#withdrawActionSheetForex_Crypto').on('shown.bs.modal', function () {
        // Clear again just in case
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }

        intervalId = setInterval(function () {
            const symbolKey = $("#lblsymbol").text();
            const item = cache[symbolKey] || cache[symbol];

            if (!item || !item.Data) return;

        const data = item.Data;
          
        const lowerCircuit = Number($("#lblLowerCircuit").text()) || 0;
        const sellPrice    = Number(data.bid_price) || 0;

        const sellPriceNew = (sellPrice <= lowerCircuit && lowerCircuit > 0) ? 0 : sellPrice;
        const upperCircuit = Number($("#lblUpperCircuit").text());
        const buyPrice = Number(data.ask_price);

       const buyPriceNew = upperCircuit > 0
            ? (buyPrice >= upperCircuit ? 0 : buyPrice)
            : buyPrice;

            $("#tblfcbuyprice").html(buyPriceNew || '0');
            $("#tblfcsellprice").html(sellPriceNew || '0');
            $("#lblBid").html(data.bid_price || '0');
            $("#lblbid").html(data.ask_price || '0');
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
            $("#lblAsk").html(data.ask_price || '0');
            $("#lblLastTradedQty").html(data.last_traded_qty || '0');
            $("#lblLastTradedQty1").html(data.last_traded_qty || '0');
            $("#lblUpperCircuit1").html(data.upper_circuit || '0');
            $("#lblLowerCircuit1").html(data.lower_circuit || '0');
            $("#lblprev_close_price1").html(data.prev_close_price || '0');
            $("#lblprev_close_price").html(data.prev_close_price || '0');
            $("#lblPrevClose").html(data.prev_close_price || '0');
            $("#lblPrevClose1").html(data.prev_close_price || '0');
            $("#lblAtp").html(data.avg_trade_price || '0');
            $("#lblAtp1").html(data.avg_trade_price || '0');
            $("#lblAtp2").html(data.avg_trade_price || '0');

        }, 100);
    });

    // ✅ Properly clear interval on close
    $('#withdrawActionSheetForex_Crypto').on('hidden.bs.modal', function () {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    });
}

        // Initialize connection
        connect();
    
        // Make function available globally
        window.OPENMODALMCXNSE = OPENMODALMCXNSE;
            $('#mcxpop').on('hidden.bs.modal', function() {
               connect();
            });
    });


    function OpenWatchListModal(Option, textbox) {
        // Set global search option
        OptionSearch = Option;

        ClientId = "{{ Auth::guard('tradeuser')->user()->id }}";

        // Only proceed if we have a valid option
        if (Option != '') {
            // Show loading state
            $('#search_datamain').html(
                '<tr><td colspan="6"><div class="text-center py-3">Loading instruments...</div></td></tr>'
            );
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
                   // console.log('API Response Data:', data);

                    // Handle empty results
                    if (!data || data.length === 0) {
                        $('#search_datamain').html(
                            '<tr><td colspan="6"><div class="text-center py-3">No matching instruments found</div></td></tr>'
                        );
                        return;
                    }

                    // ✅ Filter data by OptionSearch (MCX/NSE)
                    data = data.filter(item => {
                        const symbol = item.Symbol || '';
                        if (OptionSearch === 'MCX') {
                            return symbol.startsWith('MCX:');
                        } else if (OptionSearch === 'NSE') {
                            return symbol.startsWith('NSE:');
                        }
                        return true;
                    });

                    // Handle case where nothing matched after filtering
                    if (data.length === 0) {
                        $('#search_datamain').html(
                            '<tr><td colspan="6"><div class="text-center py-3">No instruments found for selected market</div></td></tr>'
                        );
                        return;
                    }

                    let html = '';

                    // Process each instrument
                    data.forEach(item => {
                        const symbol = item.Symbol || 'N/A';
                      
                        //const symbol = item.SymbolShortName || 'N/A';
                        const description = item.Description || symbol;
                        const exchange = item.TERMINAL || item.Exchange || 'N/A';
                        const isActive = Boolean(item.ckecked || item.ckecked);
                        const lotSize = item.LotSize ? parseInt(item.LotSize) : 'N/A';
                        const instrumentType = item.InstrumentType || 'N/A';

                        // Extract market data
                        const marketData = item.Data || {};
                        const lastUpdate = marketData.last_traded_time
                            ? new Date(marketData.last_traded_time * 1000).toLocaleString()
                            : 'N/A';
                        const expiryDate = marketData.expiry_date
                            ? new Date(marketData.expiry_date).toLocaleDateString()
                            : 'N/A';
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
                                        <p class="title">${item.SymbolShortName}</p>
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
                                        ${isActive
                                ? `<input type="checkbox" name="market_search" id="search_${symbol}_check" checked
                                                class="check_box" onclick="toggleMarketWatch('${symbol}', '${exchange}', false);">`
                                : `<input type="checkbox" name="market_search" id="search_${symbol}_check"
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
       // console.log(`${action.toUpperCase()} ${symbol} from ${exchange} watchlist`);
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
                        checkbox.onclick = function () {
                            toggleMarketWatch(symbol, exchange, !shouldAdd);
                        };
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed...",
                        text: 'Failed to ' + action + 'instrument:' + data.message,
                    });

                }
            })
            .catch(error => {
                console.error(`Watchlist ${action} error:`, error);
                Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: 'Network error - please try again',
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
                Min: 1,
                Mega: 1,
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

                var checkQty = "{{ $user->TradeEquityAsUnits}}";
                
                    const symbol =$("#lblsymbol").html();
                    const exchange = symbol.split(":")[0];
                     var lot = $("#textfclot").val();

                    if (checkQty == 1 && exchange == 'NSE') {
                    if($("#nse-qty").val()==''){
                          Swal.fire({
                            icon: "error",
                            title: "Failed...",
                            text: "Enter Your Quentity!",
                        });
                        return false;
                    }
                    
                    Object.assign(_data, {  
                    qty:$("#nse-qty").val(),
                    });
              var lot = (parseFloat($("#nse-qty").val()) / parseFloat($("#lblLotSize").html())).toFixed(2);
                }
            
         
            $.ajax({
                type: "POST",
                url: "{{ url('/save-transaction') }}",
                data: _data,
                success: function (response) {
                    $("#withdrawActionSheetForex_Crypto").modal('hide');

                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            text: "Bought   " + lot + " Lots of " + symbol + " at " + $("#tblfcbuyprice").html(),
                            title: response.message,
                            draggable: true,
                            showConfirmButton: true,
                            confirmButtonText: "OK",
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "/trades#Active";
                            }
                        });

                },
                error: function (xhr) {
                    let errorMessage = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Failed...",
                        text: errorMessage,
                    });

                }
            });

        } else {

            Swal.fire({
                icon: "error",
                title: "Failed...",
                text: "Enter Your Values!",
            });
            return;
        }
    }

    function sellplacedorder() {
        if ($("#txtLots").val() != '' && $("#txtLots").val() != '0' && $("#txtPriceOrder").val() != '' && $(
            "#txtPriceOrder").val() != '0') {
            // $("#txtplaceamount").val();
            var amount     = Number($("#txtPriceOrder").val());
            var lowValue   = Number($("#lblLow").text());
            var highValue  = Number($("#lblHigh").text());
            var lot = $("#txtLots").val();
             var lowerCircuit = Number($("#lblLowerCircuit").text());
            var upperCircuit = Number($("#lblUpperCircuit").text());
            var symbol = $("#lblsymbol").html();

            if (isNaN(amount) || isNaN(lowValue) || isNaN(highValue)) {
               
                 Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: "Invalid number detected",
                });
                return false;
            }

             if (amount < lowerCircuit ) {
                 Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: "You cannot take less than "+lowerCircuit,
                });
                return false;
            }
                if (amount > upperCircuit) {
                 Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: "You cannot take Gratter then "+upperCircuit,
                });
                return false;
            }

    
            // if (amount > lowValue && amount < highValue) {
            //       Swal.fire({
            //         icon: "error",
            //         title: "Failed...",
            //         text: "Order price must be beyond Height and Low",
            //     });
            //     return false;
            // }
            if(lot > 3){
                 Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: "Max no. lots 2",
                });
                return false;
            }
            var activeConditions = 'Above';
            if(amount < lowValue){
            activeConditions = 'Lower';
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
                activeConditions: activeConditions,
                Symbol: $("#lblsymbol").html(),
                textfclot: '0',
                //Min: document.getElementById("chkminMarket").checked,
                //Mega: document.getElementById("chkmegaMarket").checked,
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
                success: function (response) {
                    $("#withdrawActionSheetForex_Crypto").modal('hide');

                        Swal.fire({
                        position: "top-center",
                        icon: "success",
                        text: "SELL order of " + lot + " Lots of " + symbol + " scheduled to execute " + activeConditions + " Rs." + amount,
                        draggable: true,
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "/trades";
                        }
                    });
                },
                error: function (xhr) {

                    let errorMessage = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Failed...",
                        text: errorMessage,
                    });
                }
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed...",
                text: "Enter Your Price!",

            });

            return false;
        }

    }

    function buyplacedorder() {

        if ($("#txtLots").val() != '' && $("#txtLots").val() != '0' && $("#txtPriceOrder").val() != '' && $(
            "#txtPriceOrder").val() != '0') {

             var amount     = Number($("#txtPriceOrder").val());
            var lowValue   = Number($("#lblLow").text());
            var highValue  = Number($("#lblHigh").text());
            var lot = $("#txtLots").val();
            var lowerCircuit = Number($("#lblLowerCircuit").text());
            var upperCircuit = Number($("#lblUpperCircuit").text());
             var symbol = $("#lblsymbol").html();
            if (isNaN(amount) || isNaN(lowValue) || isNaN(highValue)) {
               
                 Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: "Invalid number detected",
                });
                return false;
            }
            // if(lot > 3){
            //      Swal.fire({
            //         icon: "error",
            //         title: "Failed...",
            //         text: "Max no. lots 2",
            //     });
            //     return false;
            // }

                if (amount < lowerCircuit ) {
                 Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: "You cannot take less than "+lowerCircuit,
                });
                return false;
            }
                if (amount > upperCircuit) {
                 Swal.fire({
                    icon: "error",
                    title: "Failed...",
                    text: "You cannot take Gratter then "+upperCircuit,
                });
                return false;
            }


            var activeConditions = 'Above';
            if(amount < lowValue){
            activeConditions = 'Lower';
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
                activeConditions: activeConditions,
                Symbol: $("#lblsymbol").html(),
                textfclot: '0',
                //Min: document.getElementById("chkminMarket").checked,
                //Mega: document.getElementById("chkmegaMarket").checked,
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
                success: function (response) {
                    $("#withdrawActionSheetForex_Crypto").modal('hide');
                    $("#withdrawActionSheetForex_Crypto").modal('hide');
                     Swal.fire({
                        position: "top-center",
                        icon: "success",
                            text: "BUY order of " + lot + " Lots of " + symbol + " scheduled to execute " + activeConditions + " Rs." + amount,
                        draggable: true,
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "/trades";
                        }
                    });
                    
                },
                error: function (xhr) {

                    let errorMessage = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Failed...",
                        text: errorMessage,
                    });
                }
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed...",
                text: "Enter Your Price",
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
                Min: 1,
                Mega: 1,
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

            var lot = $("#textfclot").val();
             var symbol = $("#lblsymbol").html();
              var checkQty = "{{ $user->TradeEquityAsUnits}}";
                const exchange = symbol.split(":")[0];
                   
                if (checkQty == 1 && exchange == 'NSE') {
                    if($("#nse-qty").val()==''){
                          Swal.fire({
                            icon: "error",
                            title: "Failed...",
                            text: "Enter Your Quentity!",
                        });
                        return false;
                    }

                    Object.assign(_data, {  
                    qty:$("#nse-qty").val(),
                    });
                    
              var lot = (parseFloat($("#nse-qty").val()) / parseFloat($("#lblLotSize").html())).toFixed(2);

                }

            $.ajax({
                type: "POST",
                url: "{{ route('save.transaction') }}",
                data: _data,
                success: function (response) {

                    $("#withdrawActionSheetForex_Crypto").modal('hide');
                    $("#withdrawActionSheetForex_Crypto").modal('hide');
                                    
                        Swal.fire({
                        position: "top-center",
                        icon: "success",
                        text: "Sold " + lot + " Lots of " + symbol + " at " + $("#tblfcsellprice").html(),
                        title: response.message,
                        draggable: true,
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "/trades#Active";
                        }
                    });

                },
                error: function (xhr) {

                    let errorMessage = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Failed...",
                        text: errorMessage,
                    });
                }
            });

        } else {
            Swal.fire({
                icon: "error",
                title: "Failed...",
                text: "Enter Your Value!",
            });

            return;
        }
    }

    function ChangeText(lblTransactionMode) {
        $("#lblTransactionMode").html(lblTransactionMode);
    }


    document.addEventListener('DOMContentLoaded', function () {
        const modalBody = document.querySelector('#withdrawActionSheetForex_Crypto .modal-body');

        // On tab shown
        document.querySelectorAll('#withdrawActionSheetForex_Crypto a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function () {
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

        return `${day}-${month}-${year}`;
    }

    function seachFilter() {
        let input = document.getElementById("mcx_filter");
        let filter = input.value.toLowerCase();
        let rows = document.querySelectorAll("#search_datamain tr");

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.indexOf(filter) > -1) {
                row.style.display = ""; // show
            } else {
                row.style.display = "none"; // hide
            }
        });
    }


document.getElementById('mcxpop').addEventListener('shown.bs.modal', function () {
  console.log('Modal opened');
  console.log(document.getElementById('mcx_filter')); // check if input found
  document.getElementById('mcx_filter').focus();
});
</script>
@endsection