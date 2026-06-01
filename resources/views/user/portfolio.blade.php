{{-- resources/views/portfolio.blade.php --}}
@extends('layouts.user')
@section('title', 'Portfolio')

@push('head')
    {{-- Extra <head> assets can go here --}}
@endpush

<style>
    /* MOBILE SCROLL FIX */
@media only screen and (max-width: 768px) {

    html,
    body {
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        height: 100%;
        position: relative;
    }

    #appCapsule {
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .wallet-card-section {
        padding-bottom: 80px;
    }
}
    .btn-danger {
        background: #e8ecf1 !important;
        border-color: #e8ecf1 !important;
        color: #000000FFF !important
    }
    /* Add this to your CSS */
@media (max-width: 768px) {
    .badge.badge-success {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 2px;
        margin-bottom: 10px!important;
    }
    
    .badge.badge-success .price-break {
        display: block;
        width: 100%;
    }
}
</style>

@section('content')
    <div id="appCapsule">
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="Active" role="tabpanel">
                        <h3 class="text-center">Portfolio</h3>

                        <div class="row">
                            <div class="col-6 text-center">
                                <h3 id="active_profit_loss_total">0.00</h3>
                                <p>Active P&L</p>
                            </div>
                            <div class="col-6 text-center" style="display: none;">
                                <h3 id="net_profit_loss_today">0.00</h3>
                                <p>Today's P&L</p>
                            </div>
                        </div>

                        <ul class="nav nav-tabs lined">
                            <li class="nav-item btn" style="border-radius:0;margin:0 2px;height:40px;">
                                <a class="text-white" href="#">Ledger&nbsp;Bal:&nbsp;
                                    {{ Auth::guard('tradeuser')->user()->balance }}
                                </a>
                            </li>
                            <li class="nav-item" style="border-radius:0;margin:0 2px;height:40px;">
                                <a class="text-white" href="#" data-bs-toggle="modal"
                                    data-bs-target="#CloseBulkSheet">
                                    Margin&nbsp;Avail.:&nbsp;<span id="avilable-margin">{{ $marginAvilabe }}</span>
                                </a>
                            </li>
                            @php
                                use Carbon\Carbon;
                                $now = Carbon::now();
                                $startIntraday = Carbon::today()->setTime(9, 30);
                                $endIntraday = Carbon::today()->setTime(21, 30);
                            @endphp
                            <li class="nav-item" style="border-radius:0;margin:0 2px;height:40px;">
                                <a class="text-white" href="#" data-bs-toggle="modal"
                                    data-bs-target="#CloseBulkSheet">
                                    @if ($now->between($startIntraday, $endIntraday))
                                        Margin&nbsp;Used: <span id="holding-margin">{{ number_format($usedMargin, 3, '.', '') }}</span>
                                    @else
                                        Holding&nbsp;Margin: <span id="holding-margin">{{ number_format($holdingMargin, 3, '.', '') }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>

                        <div class="">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="portfolio_list">
                                                <span class="para">Opening Bal: </span>
                                                <span class="title" id="opening_balance">{{ Auth::guard('tradeuser')->user()->balance }}</span>
                                            </div>
                                            <div class="portfolio_list">
                                                <span class="para">Credits: </span>
                                                <span class="title" >{{ $deposit }}</span>
                                                <span class="title" id="credits" style="display: none;">{{ $deposit }}</span>
                                            </div>
                                            <div class="portfolio_list">
                                                <span class="para">Withdrawals: </span>
                                                <span class="title" id="withdrawals1">{{ $widrow }}</span>
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div class="portfolio_list">
                                                <span class="para">Settled P/L: </span>
                                                <span class="title" id="settled_profit_loss">{{ number_format($totalPLSatteled, 3, '.', '') }}</span>
                                            </div>
                                            <div class="portfolio_list">
                                                <span class="para">Net P/L: </span>
                                                <span class="title" id="net_profit_loss">0.00</span>
                                            </div>
                                            <div class="portfolio_list">
                                                <span class="para">M2M: </span>
                                                <span class="title" id="m2m_balance">0.00</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody id="tblactive">
                                    {{-- Rows injected by AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== Single-Trade Modal ===== --}}
        <div class="modal fade action-sheet" id="CloseSingleTrade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color:#311b7f;">
                    <div class="modal-header">
                        <h5 class="modal-title text-white fw-bold">AMBUJACEM25JAN540PE</h5>
                    </div>
                    <div class="modal-body">
                        <div class="card" style="background-color:#311b7f;">
                            <div class="card-body pt-0">
                                <div class="tab-content mt-2">
                                    <div class="tab-pane fade show active" id="overview2" role="tabpanel">
                                        <ul class="nav nav-tabs lined">
                                            <li class="nav-item" style="background:#b24153;"
                                                data-bs-toggle="modal" data-bs-target="#DialogIconedSuccess">
                                                <a class="nav-link" style="color:#fff;font-size:15px;">
                                                    Exit Buy in loss of -600
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td><h4 class="comodity">Bid&nbsp;:&nbsp;3082</h4></td>
                                                        <td class="text-end text-primary"><h4 class="comodity">Ask&nbsp;:&nbsp;3089</h4></td>
                                                    </tr>
                                                    <tr>
                                                        <td><h4 class="comodity">Last&nbsp;:&nbsp;3082</h4></td>
                                                        <td class="text-end text-primary"><h4 class="comodity">Change&nbsp;:&nbsp;3089</h4></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="cards2" role="tabpanel">
                                        <div class="form-group basic">
                                            <div class="input-wrapper">
                                                <label class="label" for="text11d">To</label>
                                                <input type="email" class="form-control" id="text11d"
                                                    placeholder="Enter IBAN" value="1">
                                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                                            </div>
                                        </div>
                                        <ul class="nav nav-tabs lined">
                                            <li class="nav-item" style="background:#b24153;">
                                                <a class="nav-link" style="color:#fff;font-size:15px;">Place Sell Order</a>
                                            </li>
                                            <li class="nav-item" style="background:#208549!important;">
                                                <a class="nav-link" style="color:#fff;font-size:15px;">Place Buy Order</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- /appCapsule --}}

    {{-- ===== Bulk Close Modal ===== --}}
    <div class="modal fade custom-centered" id="CloseBulkSheet" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div id="closeModalTitle" class="modal-header border-0">
                    <h5 class="modal-title text-white fw-bold">
                        Are you sure to close all Active Trades ?
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="card bg-transparent border-0">
                        <div class="card-body pt-0">
                            <form id="bulkCloseForm">
                                <div class="form-group basic">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="submit" class="btn btn-success"
                                            style="border-radius: 0; min-width: 130px;">
                                            Confirm
                                        </button>
                                        <button type="button" class="" data-bs-dismiss="modal"
                                            style="border-radius: 0; min-width: 130px;">
                                            Don't Close
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        // =========================================================
        //  STEP 1 — Load active trade rows via AJAX
        //  ✅ Fixed typo: "docuemnt" → "document"
        //  ✅ updateLivePL2 starts ONLY after rows are in the DOM
        // =========================================================
        $(document).ready(function () {
            $.ajax({
                type: "GET",
                url: "{{ route('active.portfolio') }}",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        var html = '';

                        response.data.forEach(function (trade) {
                            var actionText = trade.Mode === 'BUY' ? 'Bought' : 'Sold';
                            html += `
                                <tr id="row-${trade.Pk_id}">
                                    <td scope="row">
                                        <h4 class="comodity mb-1">${trade.Symbol || '-'}</h4>
                                        <p class="detail">Margin: ${trade.used_margin_req}</p>
                                        <button class="brn btn-info" onclick="CloseStockmodal('${trade.Symbol}')">Close Trade</button>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="text-warning">${trade.Mode || 'N/A'}</span>
                                    </td>
                                    <td class="text-end text-primary">
                                        <p class="date">
                                            <span class="badge badge-success">
                                                ${actionText} X ${trade.Lots ? parseFloat(trade.Lots).toFixed(3) : 1}<span class="price-break"></span> @ ${trade.BuyPrice || '0.00'}
                                            </span>
                                        </p>
                                        <p class="detail detail1" style="padding-right:30px!important;" id="${trade.Symbol}_total_Pl">
                                            <span class="pl">0.00</span>
                                        </p>
                                        <p class="detail detail1" style="padding-right:20px!important;">
                                            CMP <span class="trade-last">0.00</span>
                                        </p>
                                    </td>
                                </tr>
                            `;
                        });

                        $('#tblactive').html(html);

                        // ✅ KEY FIX: Start updateLivePL2 ONLY after rows exist in DOM
                        updateLivePL2();
                        setInterval(updateLivePL2, 1000);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading active trades:', error);
                }
            });
        });

        // =========================================================
        //  STEP 2 — Helper functions
        // =========================================================
        function format2(num) {
            return Number(num ?? 0).toFixed(2);
        }

        function formatDateTime(input) {
            const date = new Date(input);
            const day    = String(date.getDate()).padStart(2, '0');
            const month  = String(date.getMonth() + 1).padStart(2, '0');
            const year   = date.getFullYear();
            const hours  = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }

        function updateNetPL(value) {
            const el = document.getElementById('net_profit_loss_today');
            if (!el) return;
            el.textContent = parseFloat(value).toFixed(2);
            el.style.color = parseFloat(value) < 0 ? 'red' : 'green';
        }

        function updateNetPL2(value) {
            const el = document.getElementById('active_profit_loss_total');
            if (!el) return;
            el.textContent = parseFloat(value).toFixed(2);
            el.style.color = parseFloat(value) < 0 ? 'red' : 'green';
        }

        function CloseStockmodal(tradeType) {
            if (tradeType !== '') {
                if (!isNaN(tradeType) && Number.isInteger(Number(tradeType))) {
                    $('#closeModalTitle').html('Are you sure you want to close this trades?');
                } else {
                    $('#closeModalTitle').html('Are you sure you want to close all ' + tradeType + ' trades?');
                }
                Close = tradeType;
                $('#lblBulkCloseName').html(tradeType);
                $('#CloseBulkSheet').modal('show');
            }
        }

        // =========================================================
        //  STEP 3 — Bulk close form submit
        // =========================================================
        $('#bulkCloseForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('bulk-close') }}",
                data: {
                    exchange_type: Close,
                    _token: "{{ csrf_token() }}",
                    bulk: true,
                },
                dataType: "json",
                success: function (response) {
                    if (response.success == true) {
                        $('#CloseBulkSheet').modal('hide');
                        $('#successMessage').text(response.message);
                        $('#DialogIconedSuccess').modal('show');
                        window.location.href = '/trades#Closed';
                        window.location.reload(true);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message || 'Error closing trades',
                        });
                        setTimeout(function () {
                            window.location.href = '/portfolio';
                        }, 2500);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseJSON.error).message;
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Can't be Closed because API",
                        text: errorMessage,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        // =========================================================
        //  STEP 4 — updateLivePL (dashboard summary — fixed elements)
        //  ✅ Safe to start immediately on DOMContentLoaded
        // =========================================================
// ============================================================
// GUARD FLAGS
// ============================================================
let isFetching   = false;
let isFetchingPL = false;
let interval1    = null;
let interval2    = null;

// ============================================================
// FUNCTION 1 — Dashboard totals
// ============================================================
function updateLivePL() {
    if (isFetchingPL) return;
    isFetchingPL = true;

    const id      = "{{ Auth::guard('tradeuser')->user()->id ?? 'null' }}";
    const segment = "admin";
    const url     = `/admin/dashboard-live-pl/${segment}/${id}`;

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('dashboardLivePl', data);
            if (!Array.isArray(data) || data.length === 0) {
                console.warn("No live price data received");
                return;
            }

            const brokerPLMap = {};
            let today        = new Date().toISOString().split('T')[0];
            let totalPL      = 0;
            let totalPLToday = 0;
            let balance      = 0;

            data.forEach(trade => {
                document.getElementById('opening_balance').textContent = format2(trade.balance);
                document.getElementById('credits').textContent         = format2(trade.cradit);

                totalPL += parseFloat(format2(trade.pl)) || 0;

                if (trade.date === today) {
                    totalPLToday += parseFloat(trade.pl) || 0;
                }

                updateNetPL(trade.pl);
                balance = trade.balance;
            });

            updateNetPL2(totalPL);
const sattledPl = parseFloat(document.getElementById('settled_profit_loss').textContent) || 0;

document.getElementById('net_profit_loss').textContent =
    (sattledPl + (parseFloat(totalPL) || 0)).toFixed(3);

            document.getElementById('active_profit_loss_total').textContent = format2(totalPL.toFixed(2));
            document.getElementById('net_profit_loss_today').textContent    = format2(totalPLToday.toFixed(2));

            const usedmargin      = parseFloat(document.getElementById('holding-margin').textContent) || 0;
            const total           = parseFloat(totalPL) || 0;
            const availableMargin = ((balance - usedmargin) + total);

            document.getElementById('avilable-margin').textContent = format2(availableMargin.toFixed(2));
            document.getElementById('m2m_balance').textContent     = ((parseFloat(balance) || 0) + (parseFloat(totalPL) || 0)).toFixed(2);

            Object.entries(brokerPLMap).forEach(([brokerId, plVal]) => {
                const el = document.getElementById(`pl_${brokerId}`);
                if (el) {
                    el.textContent = plVal.toFixed(2);
                    el.style.color = plVal > 0 ? 'green' : (plVal < 0 ? 'red' : 'black');
                }
            });

            const totalPlEl = document.getElementById('total-pl');
            if (totalPlEl) {
                totalPlEl.textContent = totalPL.toFixed(2);
                totalPlEl.style.color = totalPL > 0 ? 'green' : (totalPL < 0 ? 'red' : 'black');
            }
        })
        .catch(err => console.error('❌ updateLivePL error:', err))
        .finally(() => { isFetchingPL = false; });
}

// ============================================================
// FUNCTION 2 — Per-row CMP & P&L updates
// ============================================================
async function updateLivePL2() {
    if (isFetching) return;
    isFetching = true;

    const id = "{{ Auth::guard('tradeuser')->user()->id ?? 'null' }}";

    if (!id || id === 'null') {
        console.error('❌ User ID not found');
        isFetching = false;
        return;
    }

    try {
        console.log('urls admin',`/admin/live-prices/${id}`);
        const res = await fetch(`/admin/live-prices/${id}`);

        if (!res.ok) throw new Error(`HTTP error: ${res.status}`);

        const data = await res.json();

        if (!Array.isArray(data)) throw new Error('Invalid response: expected array');

        let symbolTotals = {};

        data.forEach(trade => {
            const row = document.getElementById(`row-${trade.Pk_id}`);

            symbolTotals[trade.Symbol] = (symbolTotals[trade.Symbol] ?? 0) + Number(trade.pl);

            if (!row) return;

            const lastCell = row.querySelector('.trade-last');
            if (lastCell && trade.TradeLast != null) {
                lastCell.textContent = trade.TradeLast;
            }

            const plCell = row.querySelector('.pl');
            if (plCell && trade.pl != null) {
                plCell.textContent = Number(trade.pl).toFixed(2);
                plCell.style.color = trade.pl > 0 ? 'green' : trade.pl < 0 ? 'red' : 'black';
            }
        });

        Object.keys(symbolTotals).forEach(symbol => {
            const el = document.getElementById(`${symbol}_total_Pl`);
            if (el) {
                const plSpan = el.querySelector('.pl');
                const target = plSpan || el;
                target.innerText   = Number(symbolTotals[symbol]).toFixed(2);
                target.style.color = symbolTotals[symbol] < 0 ? 'red' : 'green';
            }
        });

    } catch (err) {
        console.error('❌ updateLivePL2 error:', err.message);
    } finally {
        isFetching = false;
    }
}

// ============================================================
// ✅ BULLETPROOF STARTER — handles all mobile WebView cases
// ============================================================
function startIntervals() {
    // prevent duplicate intervals if called more than once
    if (interval1 || interval2) return;

    console.log('✅ Starting both live PL intervals');

    // updateLivePL();
    updateLivePL2();

    interval1 = setInterval(updateLivePL,  1000);
    interval2 = setInterval(updateLivePL2, 1000);
}

// ✅ Try 1 — DOMContentLoaded (works on desktop & most browsers)
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(startIntervals, 500);
});

// ✅ Try 2 — window.onload (fires after full render, catches slow mobiles)
window.addEventListener("load", function () {
    setTimeout(startIntervals, 500);
});

// ✅ Try 3 — Fallback for Android/iOS WebView that miss both events
setTimeout(startIntervals, 3000);

    </script>
@endsection