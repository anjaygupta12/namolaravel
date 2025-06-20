{{-- resources/views/portfolio.blade.php --}}
@extends('layouts.user')   {{-- change to your own layout --}}
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
                        <li class="nav-item btn btn-danger"
                            style="border-radius:0;margin:0 2px;height:25px;">
                            <a class="text-white" href="#">Ledger&nbsp;Bal:&nbsp;100001358300</a>
                        </li>
                        <li class="nav-item btn btn-danger"
                            style="border-radius:0;margin:0 2px;height:25px;">
                            <a class="text-white" href="#" data-bs-toggle="modal"
                               data-bs-target="#CloseBulkSheet">Margin&nbsp;Avail.:&nbsp;100001224358</a>
                        </li>
                        <li class="nav-item btn btn-danger"
                            style="border-radius:0;margin:0 2px;height:25px;">
                            <a class="text-white" href="#" data-bs-toggle="modal"
                               data-bs-target="#CloseBulkSheet">Margin&nbsp;Used:</a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                {{-- Repeat <tr> blocks as needed --}}
                                <tr data-bs-toggle="modal" data-bs-target="#CloseSingleTrade">
                                    <td>
                                        <h4 class="comodity mt-1">COPPER</h4>
                                        <p class="date mt-1">Margin&nbsp;:&nbsp;83629</p>
                                        <a href="#" class="btn btn-danger mb-1" style="border-radius:0;">Close Trade</a>
                                    </td>
                                    <td></td>
                                    <td class="text-end text-primary">
                                        <p class="date mt-1">Bought&nbsp;:&nbsp;<span class="text-green">2</span>&nbsp;@&nbsp;<span class="text-green">541.00</span></p>
                                        <p class="fw-bold mt-1 text-red">‑22000</p>
                                        <p class="text-white fw-bold mt-1">CMP&nbsp;832.45</p>
                                    </td>
                                </tr>

                                {{-- duplicate rows trimmed for brevity --}}
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
                                        <li class="nav-item" style="background:#b24153;" data-bs-toggle="modal" data-bs-target="#DialogIconedSuccess">
                                            <a class="nav-link" style="color:#fff;font-size:15px;">Exit Buy in loss of -600</a>
                                        </li>
                                    </ul>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                {{-- Key‑value rows --}}
                                                <tr>
                                                    <td><h4 class="comodity">Bid&nbsp;:&nbsp;3082</h4></td>
                                                    <td class="text-end text-primary"><h4 class="comodity">Ask&nbsp;:&nbsp;3089</h4></td>
                                                </tr>
                                                <tr>
                                                    <td><h4 class="comodity">Last&nbsp;:&nbsp;3082</h4></td>
                                                    <td class="text-end text-primary"><h4 class="comodity">Change&nbsp;:&nbsp;3089</h4></td>
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
    <div class="modal fade dialogbox" id="DialogIconedSuccess" data-bs-backdrop="static"
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
    </div>

</div> {{-- /appCapsule --}}
@endsection
