@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')

    <div class="container-fluid">
        <div class="card ">
            <div class="card-header card-header-success card-header-text">
                <div class="card-text">
                    <h4 class="card-title">Update Trades</h4>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif


            <div class="card-body ">
                <div></div>
                <form id="trade_form" action="{{ route('admin.trade-update') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group field-trades-commodity required">
                                <label class="control-label" for="trades-commodity">Scrip</label>
                                <div class="dropdown">
                                    <input type="hidden" name="id" value="{{ $trades->Pk_id }}">
                                    <input type="hidden" name="transactionmode" value="{{ $trades->TransactionMode }}">

                                    <input type="hidden" name="userid" value=" {{ request()->route('userid') }}">
                                    <select name="scrip_id" class="scrip_id">
                                        <option value="">Select Scrip</option>
                                        @foreach ($forexOption as $option)
                                            <option value="{{ $option->Symbol }}" shortName="{{ $option->SymbolShortName }}"
                                                @if ($trades->Symbol == $option->Symbol) selected @endif>{{ $option->Symbol }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-trades-user_id required">
                                <label class="control-label" for="trades-user_id">User ID</label>
                                <div class="dropdown">
                                    <select name="userid">
                                        <option value="">Select User</option>
                                        @foreach ($tradeUser as $user)
                                            <option value="{{ $user->id }}"
                                                @if ($trades->UserId == $user->id) selected @endif>{{ $user->user_id }} :
                                                {{ $user->Username }}</option>
                                        @endforeach

                                    </select>

                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group field-trades-lots min-mega" style="display: none;">

                                <div class="d-flex align-items-center">

                                    <label class="mr-3 mb-0">
                                        <input type="radio" name="min_mega"
                                            value="1"{{ $trades->Min == 1 ? 'checked' : '' }}>
                                        Min
                                    </label>

                                    <label class="mb-0">
                                        <input type="radio" name="min_mega" value="0"
                                            {{ $trades->Min == 0 ? 'checked' : '' }}>
                                        Mega
                                    </label>

                                </div>

                            </div>

                            <div class="form-group field-trades-lots required">
                                <label class="control-label" for="trades-lots">Lots / Units</label>
                                @php

                                if($trades->SymbolShortName=='SILVER'){
                                    $minLots = round(($trades->Lots*$trades->LotSize)/5,2);
                                }
                                 if ($trades->SymbolShortName == 'GOLD' || $trades->SymbolShortName == 'COPPER' || $trades->SymbolShortName == 'CRUDEOIL') {
                                     $minLots = round(($trades->Lots*$trades->LotSize)/10,2);
                                    }
                              
                                @endphp
                                <input type="text" id="trades-lots" class="form-control" name="lots"
                                    value="{{ $trades->is_qty == 1 ? $trades->quentity : ($trades->Min == 1 ? $minLots : $trades->Lots) }}"
                                    aria-required="true" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-trades-buy_rate required">
                                <label class="control-label" for="trades-buy_rate">Buy Rate</label>
                                <input type="text" id="trades-buy_rate" class="form-control" name="buy_price"
                                    value="{{ $trades->Mode=='BUY' ? $trades->BuyPrice : $trades->SalePrice }}" aria-required="true">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group field-trades-sell_rate required">
                                <label class="control-label" for="trades-sell_rate">Sell Rate</label>
                                <input type="text" id="trades-sell_rate" class="form-control" name="sell_price"
                                    value="{{ $trades->Mode=='SELL' ? $trades->BuyPrice : $trades->SalePrice }}" aria-required="true">
                                <div class="help-block"></div>
                                    <input type="hidden" name="Mode" value="{{ $trades->Mode }}">
                                    <input type="hidden" name="UserId" value="{{ $trades->UserId }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-trades-transaction_password required">
                                <label class="control-label" for="trades-transaction_password">Transaction
                                    Password</label>
                                <input type="password" id="trades-transaction_password" class="form-control"
                                    name="transaction_password" aria-required="true" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            function toggleMinMega() {

                let shortName = $('.scrip_id option:selected').attr('shortName');

                if (
                    shortName == 'GOLD' ||
                    shortName == 'COPPER' ||
                    shortName == 'CRUDEOIL' ||
                    shortName == 'SILVER'
                ) {
                    $('.min-mega').show();
                } else {
                    $('.min-mega').hide();
                }
            }

            // page load for edit page
            toggleMinMega();

            // on change
            $('.scrip_id').on('change', function() {
                toggleMinMega();
            });

        });
    </script>
@endsection
