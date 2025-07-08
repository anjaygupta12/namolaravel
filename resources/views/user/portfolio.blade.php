{{-- resources/views/portfolio.blade.php --}}
@extends('layouts.user') {{-- change to your own layout --}}
@section('title', 'Portfolio')

@push('head')
    {{-- Extra <head> assets can go here --}}
@endpush

@section('content')
    <div id="appCapsule">
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="Active" role="tabpanel">
                        <h3 class="text-center">Portfolio</h3>

                        <ul class="nav nav-tabs lined">
                            <li class="nav-item btn btn-danger" style="border-radius:0;margin:0 2px;height:25px;">
                                <a class="text-white" href="#">Ledger&nbsp;Bal:&nbsp;
                                    {{ Auth::guard('tradeuser')->user()->balance }}</a>
                            </li>
                            <li class="nav-item btn btn-danger" style="border-radius:0;margin:0 2px;height:25px;">
                                <a class="text-white" href="#" data-bs-toggle="modal"
                                    data-bs-target="#CloseBulkSheet">Margin&nbsp;Avail.:&nbsp;{{ $marginAvilabe }}</a>
                            </li>
                            <li class="nav-item btn btn-danger" style="border-radius:0;margin:0 2px;height:25px;">
                                <a class="text-white" href="#" data-bs-toggle="modal"
                                    data-bs-target="#CloseBulkSheet">Margin&nbsp;Used:{{ $usedMargin }}</a>
                            </li>
                        </ul>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="tblactive">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== Single‑Trade Modal ===== --}}
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
                                    {{-- = Overview tab = --}}
                                    <div class="tab-pane fade show active" id="overview2" role="tabpanel">

                                        <ul class="nav nav-tabs lined">
                                            <li class="nav-item" style="background:#b24153;" data-bs-toggle="modal"
                                                data-bs-target="#DialogIconedSuccess">
                                                <a class="nav-link" style="color:#fff;font-size:15px;">Exit Buy in loss of
                                                    -600</a>
                                            </li>
                                        </ul>

                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    {{-- Key‑value rows --}}
                                                    <tr>
                                                        <td>
                                                            <h4 class="comodity">Bid&nbsp;:&nbsp;3082</h4>
                                                        </td>
                                                        <td class="text-end text-primary">
                                                            <h4 class="comodity">Ask&nbsp;:&nbsp;3089</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h4 class="comodity">Last&nbsp;:&nbsp;3082</h4>
                                                        </td>
                                                        <td class="text-end text-primary">
                                                            <h4 class="comodity">Change&nbsp;:&nbsp;3089</h4>
                                                        </td>
                                                    </tr>
                                                    {{-- …remaining rows unchanged… --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- = Cards tab (unchanged) = --}}
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

                                        {{-- table of bid/ask/etc left as‑is --}}
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    {{-- …rows… --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> {{-- /cards2 --}}
                                </div> {{-- /tab‑content --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Success Dialog ===== --}}
        {{-- <div class="modal fade dialogbox" id="DialogIconedSuccess" data-bs-backdrop="static"
         tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon text-success">
                    <ion-icon name="checkmark-circle"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Your Trade has been closed</h5>
                </div>
                <div class="modal-body">
                    Your payment has been sent.
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn" data-bs-dismiss="modal">CLOSE</a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    </div> {{-- /appCapsule --}}

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


    <script>
        $("docuemnt").ready(function() {
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
                            html += `
                        <tr>
                            <td scope="row">
                                <h4 class="comodity mb-1">${trade.Symbol || '-'}</h4>
                                <p class="date">Sold by Trader 
                                    <span class="badge badge-danger">${trade.seller_rating || '6.2'}</span>
                                </p>
                                <p class="detail">${formatDateTime(trade.Timestamp) || '-'}</p>
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

        function CloseStockmodal(tradeType) {
            if (tradeType !== '') {
                Close = tradeType;
                $('#lblBulkCloseName').html(tradeType);
                $('#CloseBulkSheet').modal('show');
            }
        }


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
                            position: "top-center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
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
