@extends('layouts.user')

@section('title', 'Trades')

@section('head')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')

    <div id="appCapsule">
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
                            var badgeClass = trade.Mode === 'BUY ORDER' ? 'badge-success' :
                                'badge-danger';
                            var actionText = trade.Mode === 'BUY ORDER' ? 'Bought' : 'Sold';
                             let fullSymbol = trade.Symbol || '-';
                            let cleanSymbol = fullSymbol.split(':')[1] || fullSymbol;

                            html += `
                            <tr>
                                <td scope="row">
                                    <p class="date">
                                        <span class="badge ${badgeClass}">Bought X1</span>&nbsp;&nbsp;
                                        <span class="badge badge-success">${trade.BuyPrice}</span>
                                    </p>
                                    <h4 class="comodity mt-1">${cleanSymbol}</h4>
                                    <p class="date mt-1">${actionText} by Trader</p>
                                    <p class="date" >Margin used <b>${trade.used_margin_req }</b></p>

                                </td>
                                <td></td>
                                <td class="text-end text-primary">
                                    <p class="date mb-1">${formatDateTime(trade.Timestamp)}</p>
                                    <p class="text-white fw-bold mb-0">8.3</p>
                                    <button class="badge badge-danger" onclick="CloseStockmodal(${trade.Pk_id})"> Close Trade</button>
                                    <p class="detail">Holding margin Req <b>${trade.holding_margin_req }</b></p>                                </td>
                            </tr>
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
                            console.log(trade);
                            let fullSymbol = trade.Symbol || '-';
                            let cleanSymbol = fullSymbol.split(':')[1] || fullSymbol;
                            html += `
                        <tr>
                            <td scope="row">
                               <p class="date">
                                        <span class="badge badge-danger">Bought X${parseInt(trade.Lots) || 1}</span>&nbsp;&nbsp;
                                        <span class="badge badge-success">Market</span>
                                    </p>

                               <h4 class="comodity mb-1">${cleanSymbol}</h4>
                                <p class="date">Sold by Trader </p>
                                <p class="detail">${formatDateTime(trade.Timestamp) || '-'}</p>
                                <p class="date" >Margin used <b>${trade.used_margin_req }</b></p>
                            </td>
                            <td class="text-center align-middle">
                                <span class="text-warning">${trade.Mode || 'N/A'}</span>
                            </td>
                            <td class="text-end text-primary">
                                <p class="date">Bought by Admin </span>
                                </p>
                                 <p class="date">
                                    <span class="badge badge-success">${trade.BuyPrice || '0.00'}</span>
                                </p>
                                <button class="badge badge-danger" onclick="CloseStockmodal(${trade.Pk_id})"> Close Trade</button>
                               
                                <p class="detail">${formatDateTime(trade.Timestamp) || '-'}</p>
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

function formatDateTime(input) {
    const date = new Date(input);

    const day = String(date.getDate()).padStart(2, '0');
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
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

                            html += `
                            <tr>
                                <td scope="row">
                                    <h4 class="comodity mb-1">${cleanSymbol}</h4>
                                   
                                    <p class="detail">${formatDateTime(trade.Timestamp)}</p>
                                </td>
                                <td></td>
                                <td class="text-end text-primary">
                                    <p class="text-white fw-bold mb-1">
                                        <span class="badge badge-danger">QTY:${trade.Lots }</span>
                                    </p>
                                    <p class="date">Bought by Trader <span class="badge badge-success">6.2</span></p>
                                    <p class="detail">${formatDateTime(trade.Timestamp)}</p>
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
                        timer: 1500, // 1.5 seconds
                        showConfirmButton: false
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

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message || 'Error closing trades',
                            timer: 1500, // 1.5 seconds
                            showConfirmButton: false
                        });
                        setTimeout(function() {
                            window.location.href = '/trades';
                        }, 1700);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'Error closing trades';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
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
        });
    </script>
@endsection
