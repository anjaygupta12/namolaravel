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
                            <a class="text-white" href="#" data-bs-toggle="modal" onclick="CloseStockmodal('MCX');">Close MCX Trades</a>
                        </li>
                        <li class="nav-item btn btn-danger" style="border-radius:0;margin: 0px 2px 0px 2px;height:25px">
                            <a class="text-white" href="#" data-bs-toggle="modal" onclick="CloseStockmodal('NSE');">Close NSE Trades</a>
                        </li>
                        <li class="nav-item btn btn-danger" style="border-radius:0;margin: 0px 2px 0px 2px;height:25px">
                            <a class="text-white" href="#" data-bs-toggle="modal" onclick="CloseStockmodal('COMEX');">Close COMEX Trades</a>
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
    <div class="modal fade action-sheet" id="CloseBulkSheet" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="padding:350px">
            <div class="modal-content" style="background-color: #311b7f;">
                <div class="modal-header">
                    <h5 class="modal-title text-white fw-bold">
                        Please enter your password to close all active trades in <label id="lblBulkCloseName"></label>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="card" style="background-color: #311b7f;">
                        <div class="card-body pt-0">
                            <div class="tab-content mt-2">
                                <div class="tab-pane fade show active">
                                    <form id="bulkCloseForm">
                                        <div class="form-group basic">
                                            <div class="input-wrapper">
                                                <label class="label" for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                            </div>
                                        </div>

                                        <div class="form-group basic">
                                            <div class="input-wrapper text-center">
                                                <button type="submit" class="btn btn-success" style="border-radius:0">Submit</button>
                                            </div>
                                        </div>

                                        <div class="form-group basic">
                                            <div class="input-wrapper text-center">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="border-radius:0">Don't Close My Trades</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Dialog -->
    <div class="modal fade dialogbox" id="DialogIconedSuccess" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" style="display: none;">
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
                        var badgeClass = trade.Mode === 'BUY ORDER' ? 'badge-success' : 'badge-danger';
                        var actionText = trade.Mode === 'BUY ORDER' ? 'Bought' : 'Sold';
                        
                        html += `
                            <tr>
                                <td scope="row">
                                    <p class="date">
                                        <span class="badge ${badgeClass}">Bought X1</span>&nbsp;&nbsp;
                                        <span class="badge badge-success">${trade.Status_Exec}</span>
                                    </p>
                                    <h4 class="comodity mt-1">${trade.Symbol}</h4>
                                    <p class="date mt-1">${actionText} by Trader</p>
                                    <p class="detail"><b>Margin used</b> ${trade.margin_used || '1494'}</p>
                                </td>
                                <td></td>
                                <td class="text-end text-primary">
                                    <p class="date mb-1">${trade.Timestamp}</p>
                                    <p class="text-white fw-bold mb-0">8.3</p>
                                    <button class="btn btn-danger mb-1" onclick="OpenCloseModal(${trade.Pk_id});" style="border-radius:0">Close Trade</button>
                                    <p class="text-white fw-bold">Holding margin Req: ${trade.holding_margin_req || '3735'}</p>
                                </td>
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
                        html += `
                            <tr>
                                <td scope="row">
                                    <h4 class="comodity mb-1">${trade.Symbol}</h4>
                                    <p class="date">Sold by Trader <span class="badge badge-danger">6.2</span></p>
                                    <p class="detail">${trade.Timestamp}</p>
                                </td>
                                <td></td>
                                <td class="text-end text-primary">
                                    <p class="text-white fw-bold mb-1">
                                        <span class="badge badge-danger">-1890 / -40</span> 
                                        <span class="badge badge-danger">QTY:${trade.quantity || '900'}</span>
                                    </p>
                                    <p class="date">Bought by Trader <span class="badge badge-success">6.2</span></p>
                                    <p class="detail">${trade.Timestamp}</p>
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
                        html += `
                            <tr>
                                <td scope="row">
                                    <h4 class="comodity mb-1">${trade.Symbol}</h4>
                                    <p class="date">Sold by Trader <span class="badge badge-danger">6.2</span></p>
                                    <p class="detail">${trade.Timestamp}</p>
                                </td>
                                <td></td>
                                <td class="text-end text-primary">
                                    <p class="text-white fw-bold mb-1">
                                        <span class="badge badge-danger">-1890 / -40</span> 
                                        <span class="badge badge-danger">QTY:${trade.quantity || '900'}</span>
                                    </p>
                                    <p class="date">Bought by Trader <span class="badge badge-success">6.2</span></p>
                                    <p class="detail">${trade.Timestamp}</p>
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
            data: { ID: tradeId },
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
            data: { ID: tradeId },
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
                alert('Error closing trade. Please try again.');
            }
        });
    }

    // Handle bulk close form submission
    $('#bulkCloseForm').on('submit', function(e) {
        e.preventDefault();
        
        var password = $('#password').val();
        if (!password) {
            alert('Please enter your password');
            return;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('bulk-close') }}",
            data: {
                exchange_type: Close,
                password: password
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('#CloseBulkSheet').modal('hide');
                    $('#successMessage').text(response.message);
                    $('#DialogIconedSuccess').modal('show');
                    LoadActiveTrades();
                    LoadPendingTrades();
                } else {
                    alert(response.message || 'Error closing trades');
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = 'Error closing trades';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    });
</script>
@endsection