@extends('layouts.user')

@section('title', 'Trades')

@section('head')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')

<div id="appCapsule">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <ul class="nav nav-tabs lined" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#Pending" role="tab">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#Active" role="tab">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#Closed" role="tab">Closed</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Pending Trades Tab -->
                <div class="tab-pane fade show active" id="Pending" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody id="tblpending">
                                <!-- Pending trades will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Active Trades Tab -->
                <div class="tab-pane fade" id="Active" role="tabpanel">
                    <ul class="nav nav-tabs lined">
                        <li class="nav-item btn btn-danger" style="border-radius:0;margin: 0px 2px 0px 2px;height:25px">
                            <a class="text-white" href="#" data-bs-toggle="modal"
                                onclick="CloseStockmodal('MCX');">Close MCX Trades</a>
                        </li>
                        <li class="nav-item btn btn-danger" style="border-radius:0;margin: 0px 2px 0px 2px;height:25px">
                            <a class="text-white" href="#" data-bs-toggle="modal"
                                onclick="CloseStockmodal('NSE');">Close NSE Trades</a>
                        </li>
                        <li class="nav-item btn btn-danger" style="border-radius:0;margin: 0px 2px 0px 2px;height:25px">
                            <a class="text-white" href="#" data-bs-toggle="modal"
                                onclick="CloseStockmodal('COMEX');">Close COMEX Trades</a>
                        </li>
                    </ul>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody id="tblactive">
                                <!-- Active trades will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Closed Trades Tab -->
                <div class="tab-pane fade" id="Closed" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody id="TBLCLOSED">
                                <!-- Closed trades will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Close Single Trade Modal -->
    <div class="modal fade action-sheet" id="CloseSingleTrade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" id="dvModal">
            <!-- Modal content will be loaded dynamically -->
        </div>
    </div>

    <!-- Close Bulk Trades Modal -->

    <div class="modal fade custom-centered" id="CloseBulkSheet" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white fw-bold">
                        Are you sure to close all Active Trades ?
                        {{-- <label id="lblBulkCloseName" class="fw-bold text-warning"></label> ? --}}
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
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
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


    <!-- Success Dialog -->
    <div class="modal fade dialogbox" id="DialogIconedSuccess" data-bs-backdrop="static" tabindex="-1"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon text-success">
                    <ion-icon name="checkmark-circle"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Your Trade has been closed</h5>
                </div>
                <div class="modal-body">
                    <span id="successMessage">Your trade has been successfully closed.</span>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn" data-bs-dismiss="modal">CLOSE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa;">

            <form id="editTradeForm" method="POST" action="/update-trade">
                @csrf

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title text-white">Edit Trade: <span id="title_symbol"></span> </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <input type="hidden" name="trade_id" id="edit_trade_id">

                    <div class="mb-3">
                        <label class="form-label text-white">Lots</label>
                        <input type="number" class="form-control" name="lots" id="edit_lots" min="1" required>
                         <input type="hidden" class="form-control" name="symbol" id="edit_symbol" >
                         <input type="hidden" class="form-control" name="transactionmode" id="edit_transaction_mode" >
                          <input type="hidden" class="form-control" name="high_price" id="high_price" >
                           <input type="hidden" class="form-control" name="low_price" id="low_price" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Buy Price</label>
                        <input type="text" class="form-control" name="buy_price" id="edit_buy_price" required>
                    </div>


                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Trade</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </form>

        </div>
    </div>
</div>




<script>
    var Close = '';

    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {

        var hash = window.location.hash;

        if (hash) {
            // Trigger tab by matching the href
            $('a.nav-link[href="' + hash + '"]').tab('show');
        }

        // Optional: change the hash in URL when user clicks other tabs
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            history.replaceState(null, null, e.target.hash);
        });

        LoadPendingTrades();
        LoadActiveTrades();
        LoadClosedTrades();
    });

    function LoadPendingTrades() {
        $.ajax({
            type: "GET",
            url: "{{ route('pending') }}",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('#tblpending').html('');
                    var html = '';

                    response.data.forEach(function(trade) {
                        var badgeClass = trade.Mode === 'BUY' ? 'badge-success' :
                            'badge-danger';
                        var actionText = trade.Mode === 'BUY' ? 'Bought' : 'Sold';
                        let fullSymbol = trade.Symbol || '-';
                        let cleanSymbol = fullSymbol.split(':')[1] || fullSymbol;
                        if(trade.is_qty==1){
                        var newLots = trade.quentity;
                        }else{
                             var newLots = trade.Lots;
                        }

                        html += `
                            <tr>
                                <td scope="row">
                                    <p class="date">
                                        <span class="badge ${badgeClass}">${actionText} X ${parseFloat(newLots)} </span>&nbsp;&nbsp;
                                        <span style="color:green;">
                                            ${trade.Isactive === 0 ? 'Pending' : 'Executed'}
                                        </span>

                                    </p>
                                    <h4 class="comodity mt-1">${cleanSymbol}</h4>
                                    
                                    <p class="date" >Margin used <b>${trade.used_margin_req }</b></p>

                                </td>
                                <td></td>
                                <td class="text-end text-primary">
                                
                                <p class="detail">${formatDateTime(trade.created_at) || '-'}</p>
                                 <p class="date">
                                    <span class="badge badge-success">${trade.BuyPrice || '0.00'}</span>
                                    
                                </p>
                                
                               <p class="date mt-1">
                                               ${
                                                    trade.deleted_at !== null 
                                                    ? `<span style="color:red;">Cancelled</span>`
                                                    
                                                    : trade.Isactive != 0
                                                    ? `<span style="color:green;">Executed</span>`
                                                    
                                                    : `
                                                        <a href="javascript:void(0)" 
                                                        style="color:blue; margin-right:10px;" 
                                                        data-id="${trade.id}"
                                                        data-price="${trade.BuyPrice || '0.00'}"
                                                        data-lots="${newLots || '1'}"
                                                        data-istimeup="${trade.isTimeUp}"
                                                        data-symbol="${fullSymbol}"
                                                        data-transactionMode="${trade.TransactionMode}"
                                                        onclick="handleEditClick(this)">
                                                            <i class="fa fa-pencil"></i> Edit
                                                        </a>

                                                        <a href="/delete-trade/${trade.id}" 
                                                        style="color:red;" 
                                                        title="Delete"
                                                        onclick="
                                                            if (${trade.isTimeUp} === true) {
                                                                alert('You cannot delete this trade because the market has been closed.');
                                                                return false;
                                                            }
                                                            return confirm('Are you sure you want to cancel this trade?');
                                                        ">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                    `

                                                }
                                            </p>

                                <p class="detail">Holding margin Req <b>${trade.holding_margin_req }</b></p>
                            </td>
                        `;
                    });

                    $('#tblpending').html(html);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading pending trades:', error);
            }
        });
    }

    function LoadActiveTrades() {
        $.ajax({
            type: "GET",
            url: "{{ route('active') }}",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('#tblactive').html('');
                    var html = '';

                    response.data.forEach(function(trade) {

                        let fullSymbol = trade.Symbol || '-';
                        let cleanSymbol = fullSymbol.split(':')[1] || fullSymbol;
                         var soldBy = trade.sold_by; 
                        var boughtBy = trade.Bought_by; 
                        if(trade.is_qty==1){
                        var newLots = trade.quentity;
                        }else{
                             var newLots = trade.Lots;
                        }
                        
                        html += `
                        <tr>
                            <td scope="row">
                               <p class="date">
                                        <span class="badge badge-danger">${trade.Mode=='SELL' ? 'Sold':  'Bought'} X ${parseFloat(newLots) || 1}</span>&nbsp;&nbsp;
                                        <span class="badge badge-success">Market</span>
                                    </p>

                               <h4 class="comodity mb-1">${cleanSymbol}</h4>
                                <p class="date">${trade.Mode=='SELL' ? 'Sold':  'Bought'} by ${boughtBy} </p>
                               
                                <p class="date" >Margin used <b>${trade.used_margin_req }</b></p>
                            </td>
                            <td class="text-center align-middle">
                                <span class="text-warning">${trade.Mode || 'N/A'}</span>
                            </td>
                            <td class="text-end text-primary">
                                
                                <p class="detail">${formatDateTime(trade.created_at) || '-'}</p>
                                 <p class="date">
                                    <span class="badge badge-success">${trade.BuyPrice || '0.00'}</span>
                                </p>
                                <button class="badge badge-danger" onclick="CloseStockmodal('${trade.Symbol}')"> Close Trade</button>
                                <p class="detail">Holding margin Req <b>${trade.holding_margin_req }</b></p>
                            </td>
                        </tr>
                    `;

                    });

                    $('#tblactive').html(html);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading active trades:', error);
            }
        });
    }


    function openEditModal(el) {

        let tradeId = el.getAttribute('data-id');
        let price = el.getAttribute('data-price');
        let lots = el.getAttribute('data-lots');
        let symbol = el.getAttribute('data-symbol');
        let transactionMode = el.getAttribute('data-transactionMode');

        document.getElementById('edit_trade_id').value = tradeId;
        document.getElementById('edit_buy_price').value = parseFloat(price).toFixed(2);
        document.getElementById('edit_lots').value = lots;
        document.getElementById('edit_symbol').value = symbol;
       document.getElementById('title_symbol').textContent = symbol;
        document.getElementById('edit_transaction_mode').value = transactionMode;

        $('#editTradeModal').modal('show');
    }

    function formatDateTime(input) {
        const date = new Date(input);

        const day = String(date.getDate()).padStart(2, '0');
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear();

        let hours = date.getHours();
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;

        return `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${ampm}`;
    }


    function LoadClosedTrades() {
        $.ajax({
            type: "GET",
            url: "{{ route('closed') }}",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('#TBLCLOSED').html('');
                    var html = '';

                    response.data.forEach(function(trade) {
                        
                        let fullSymbol = trade.Symbol || '-';
                        let cleanSymbol = fullSymbol.split(':')[1] || fullSymbol;
                        var actionText = trade.Mode === 'BUY' ? 'Bought' : 'Sold';
                        var soldBy = trade.sold_by; 
                        var boughtBy = trade.Bought_by; 
                        var lotSize = parseFloat(trade.Lots) * parseFloat(trade.LotSize);
                        var buyRate = parseFloat(trade.BuyPrice);
                        var sellRate = parseFloat(trade.SalePrice);
                        var buyTurnover = buyRate * lotSize;
                        console.log('hello testing',fullSymbol,trade.Lots,trade.LotSize,lotSize,trade.BuyPrice,buyTurnover);
                        var sellTurnover = sellRate * lotSize;
                        var buyPrice = trade.BuyPrice;
                        var sellPrive = trade.SalePrice;

                        var profitLoss = sellTurnover - buyTurnover;
                        var soldDate = trade.updated_at;
                        var boughtDate = trade.created_at;
                        if(trade.Mode === 'SELL'){
                        var profitLoss =  buyTurnover-sellTurnover;
                        var buyPrice = trade.SalePrice;
                        var sellPrive = trade.BuyPrice;

                         var soldDate = trade.created_at;
                        var boughtDate = trade.updated_at;
                        }
                        if(trade.is_qty==1){
                        var newLots = trade.quentity;
                        }else{
                             var newLots = trade.Lots;
                        }
                        const pl = Number(profitLoss) || 0;
                        const brokrage = parseFloat(trade.brokrage) || '0.00';
                        html += `
                            <tr>
                                <td scope="row">
                                    <h4 class="comodity mb-1">${cleanSymbol}</h4>
                                    <p class="date">Sold by ${soldBy } <span class="badge badge-danger">${ sellPrive }</span></p>
                                    <p class="detail">${formatDateTime(soldDate)}</p>
                                </td>
                                <td></td>
                                <td class="text-end text-primary">
                                    <p class="text-white fw-bold mb-1">
                                            <span class="badge ${pl >= 0 ? 'badge-success' : 'badge-danger'}">
                                                P/L: ${pl.toFixed(2)} / ${brokrage}
                                            </span>
                                        <span class="badge badge-danger">QTY:${parseFloat(newLots || 1)}</span>
                                    </p>
                                    <p class="date">Bought by ${boughtBy} <span class="badge badge-success">${ buyPrice }</span></p>
                                    <p class="detail">${formatDateTime(boughtDate)}</p>
                                </td>
                                
                            </tr>
                        `;
                    });

                    $('#TBLCLOSED').html(html);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading closed trades:', error);
            }
        });
    }

    function OpenCloseModal(tradeId) {
        $.ajax({
            type: "POST",
            url: "{{ route('details') }}",
            data: {
                ID: tradeId
            },
            dataType: "json",
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    var trade = response.data[0];
                    var modalHtml = generateModalContent(trade);
                    $('#dvModal').html(modalHtml);
                    $('#CloseSingleTrade').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading trade details:', error);
            }
        });
    }

    function generateModalContent(trade) {
        return `
            <div class="modal-content" style="background-color: #311b7f;">
                <div class="modal-header">
                    <h5 class="modal-title text-white fw-bold">${trade.Symbol}</h5>
                </div>
                <div class="modal-body">
                    <div class="card" style="background-color: #311b7f;">
                        <div class="card-body pt-0">
                            <div class="tab-content mt-2">
                                <div class="tab-pane fade show active">
                                    <ul class="nav nav-tabs lined">
                                        <button class="nav-item" style="background: #b24153;" onclick="SAVEEXIT(${trade.Pk_id});">
                                            <a class="nav-link" style="color: #fff; font-size: 15px;">Exit Buy in loss of -600</a>
                                        </button>
                                    </ul>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr><td><h4 class="comodity">Bid: ${trade.Bid}</h4></td><td class="text-end text-primary"><h4 class="comodity">Ask: ${trade.Ask}</h4></td></tr>
                                                <tr><td><h4 class="comodity">Last: ${trade.TradeLast}</h4></td><td class="text-end text-primary"><h4 class="comodity">Change: ${trade.Change}</h4></td></tr>
                                                <tr><td><h4 class="comodity">High: ${trade.High}</h4></td><td class="text-end text-primary"><h4 class="comodity">Low: ${trade.Low}</h4></td></tr>
                                                <tr><td><h4 class="comodity">Open: ${trade.TradeOpen}</h4></td><td class="text-end text-primary"><h4 class="comodity">Bid Qty: ${trade.BidQty}</h4></td></tr>
                                                <tr><td><h4 class="comodity">Close: ${trade.PrevClose}</h4></td><td class="text-end text-primary"><h4 class="comodity">Ask Qty: ${trade.AskQty}</h4></td></tr>
                                                <tr><td><h4 class="comodity">Volume: ${trade.Volume}</h4></td><td class="text-end text-primary"><h4 class="comodity">Last Traded Qty: ${trade.LastTradeQty}</h4></td></tr>
                                                <tr><td><h4 class="comodity">Upper ckt: ${trade.UpperCircuit}</h4></td><td class="text-end text-primary"><h4 class="comodity">Open Interest: ${trade.OpenInterest}</h4></td></tr>
                                                <tr><td><h4 class="comodity">Atp: ${trade.Atp}</h4></td><td class="text-end text-primary"><h4 class="comodity">Lower ckt: ${trade.LowerCircuit}</h4></td></tr>
                                                <tr><td></td><td class="text-end text-primary"><h4 class="comodity">Lot Size: ${trade.LotSize}</h4></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function CloseStockmodal(tradeType) {

        if (tradeType !== '') {
            Close = tradeType;
            $('#lblBulkCloseName').html(tradeType);
            $('#CloseBulkSheet').modal('show');
        }
    }

    function SAVEEXIT(tradeId) {
        $.ajax({
            type: "POST",
            url: "{{ route('exit') }}",
            data: {
                ID: tradeId
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('#CloseSingleTrade').modal('hide');
                    $('#successMessage').text('Trade closed successfully');
                    $('#DialogIconedSuccess').modal('show');
                    LoadPendingTrades();
                    LoadActiveTrades();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error closing trade:', error);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error closing trade. Please try again.",
                });

            }
        });
    }

    // Handle bulk close form submission
    $('#bulkCloseForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ route('bulk-close') }}",
            data: {
                exchange_type: Close,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {

                if (response.success) {
                    $('#CloseBulkSheet').modal('hide');
                    $('#successMessage').text(response.message);
                    $('#DialogIconedSuccess').modal('show');
                    LoadActiveTrades();
                    LoadPendingTrades();
                    window.location.href = '/trades#Closed';
                    window.location.reload(true);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message || 'Error closing trades',
                    });

                    setTimeout(function() {
                        window.location.href = '/trades#Active';
                    }, 2500);
                }
            },
            // error: function(xhr, status, error) {
            //     console.log('hello testing1',status);
            //     var errorMessage = JSON.parse(xhr.responseJSON.error).message;
            //     if (xhr.responseJSON && xhr.responseJSON.message) {
            //         errorMessage = xhr.responseJSON.message;
            //     }
            //     Swal.fire({
            //         icon: "error",
            //          title: "Can’t be Closed becouse API",
            //         text: errorMessage
            //     });

            // }
        });
    });

    function handleEditClick(element) {

    const isTimeUp = element.getAttribute('data-istimeup') === 'true';

    if (isTimeUp) {
        alert('You cannot edit this trade because the market has been closed.');
        return false;
    }

    const tradeId = element.getAttribute('data-id');
    const price   = element.getAttribute('data-price');
    const lots    = element.getAttribute('data-lots');
    const sybmol  = element.getAttribute('data-symbol');
    const mode  = element.getAttribute('data-symbol');

    // Optional: Open modal first
    openEditModal(element);

    // 🔥 Fire AJAX Request
    $.ajax({
        url: "{{ route('edit.trade') }}",
        type: 'POST',
        data: {
            id: tradeId,
             _token: "{{ csrf_token() }}",
             sybmol:sybmol
        },
        success: function(response) {

            // Example: Fill modal fields
            $('#low_price').val(response.low_price);
            $('#high_price').val(response.high_price);
            console.log('Trade data loaded successfully');

        },
        error: function(xhr) {
            alert('Something went wrong while fetching trade data.');
            console.log(xhr.responseText);
        }
    });

}



</script>
@endsection