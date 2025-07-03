@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
    <style>
        .form-check-sign .check {
            background-color: black;
        }

        #per_lot {
            display: table;
        }
    </style>

    <script src="https://esaytraders.live/admin/js/jquery.js"></script>
    <script>
        function toggle_exposure() {
            if (document.getElementById("exposure_per_lot").style.display == "none")
                document.getElementById("exposure_per_lot").style.display = "flex";
            else
                document.getElementById("exposure_per_lot").style.display = "none";
            if (document.getElementById("exposure_per_turnover").style.display == "none")
                document.getElementById("exposure_per_turnover").style.display = "flex";
            else
                document.getElementById("exposure_per_turnover").style.display = "none";
        }

        function toggle_brokerage_type() {
            if (document.getElementById("mcx_lot_brokerage_div").style.display == "none")
                document.getElementById("mcx_lot_brokerage_div").style.display = "flex";
            else
                document.getElementById("mcx_lot_brokerage_div").style.display = "none";
        }

        function shortselling_config(val) {
            var index_ss = document.getElementById("options_short_selling_allowed").value;
            var equity_ss = document.getElementById("options_equity_short_selling_allowed").value;
            var mcx_ss = document.getElementById("options_mcx_short_selling_allowed").value;
            if (index_ss == 1 || equity_ss == 1 || mcx_ss == 1 || val == 1)
                document.getElementById("options_shortselling_config").style.display = "flex";
            else
                document.getElementById("options_shortselling_config").style.display = "none";
        }
    </script>


    <div class="card">
        <div class="card-header">
            <h4 class="card-title">New User Registration</h4>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="toast align-items-center text-white bg-danger border-0 show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif

        </div>
        <div class="card-body">

            <form class="form" action="{{ route('admin.store.trade.user') }}" method="POST" validateonsubmit="">
                @csrf
                <div class="mx-2">
                    <fieldset class="row">
                        <legend>Personal Details: </legend>

                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-name">
                                <label class="control-label" for="mcxusers-name">Name</label>
                                <input type="text" id="mcxusers-name" class="form-control" name="fullname"
                                    value="">
                                <div class="hint-block">Insert Real name of the trader. Will be visible in trading App</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mobile">
                                <label class="control-label" for="mcxusers-mobile">Mobile</label>
                                <input type="text" id="mcxusers-mobile" class="form-control" name="mobile"
                                    value="">
                                <div class="hint-block">Optional</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-phone">
                                <label class="control-label" for="mcxusers-phone">Username</label>
                                <input type="text" id="mcxusers-phone" class="form-control" name="username"
                                    value="">
                                <div class="hint-block">username for loggin-in with, is not case sensitive. must be unique
                                    for every trader. should not contain symbols.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-phone">
                                <label class="control-label" for="mcxusers-phone">Password</label>
                                <input type="text" id="mcxusers-phone" class="form-control" name="password"
                                    value="">
                                <div class="hint-block">password for loggin-in with, is case sensitive.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-credits">
                                <label class="control-label" for="mcxusers-credits">Initial Funds</label>
                                <input type="number" id="mcxusers-credits" class="form-control" name="funds"
                                    value="0">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-city">
                                <label class="control-label" for="mcxusers-city">City</label>
                                <input type="text" id="mcxusers-city" class="form-control" name="city" value="">
                                <div class="hint-block">Optional</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset class="row">
                        <legend>Config:</legend>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-demo">
                                <!-- Hidden input for unchecked state -->
                                <input type="hidden" name="Mcxusers[demo]" value="0">

                                <!-- Checkbox for checked state -->
                                <label>
                                    <input type="checkbox" id="mcxusers-demo" class="form-check-input" name="Mcxusers[demo]"
                                        value="1">
                                    Demo account?
                                    <span class="form-check-sign"><span class="check"></span></span>
                                </label>
                                <div class="help-block"></div>
                            </div>
                        </div>


                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-allow_entry_ahbl">
                                <input type="hidden" name="Mcxusers[allow_entry_ahbl]" value="0"><label><input
                                        type="checkbox" id="mcxusers-allow_entry_ahbl" class="form-check-input"
                                        name="allow_orders_beyond_high_low" checked> Allow Fresh Entry Order above high
                                    &amp; below low? <span class="form-check-sign"><span
                                            class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-aobhl">
                                <input type="hidden" name="Mcxusers[aobhl]" value="0"><label><input
                                        type="checkbox" id="mcxusers-aobhl" class="form-check-input"
                                        name="allow_orders_between_high_low" checked> Allow Orders between High - Low?
                                    <span class="form-check-sign"><span class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-trade_equity_as_units">

                                <input type="hidden" name="Mcxusers[trade_equity_as_units]" value="0"><label><input
                                        type="checkbox" id="trade_equity_as_units" class="form-check-input"
                                        name="trade_equity_as_units" value="1" onchange="quantity_lots();"> Trade
                                    equity as units instead of lots. <span class="form-check-sign"><span
                                            class="check"></span></span></label>

                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-status">
                                <input type="hidden" name="Mcxusers[status]" value="0"><label><input
                                        type="checkbox" id="mcxusers-status" class="form-check-input" name="status"
                                        checked> Account Status <span class="form-check-sign"><span
                                            class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-status">
                                <label><input type="checkbox" id="mcxusers-status" class="form-check-input"
                                        name="auto_square_off" checked> Auto Close Trades if condition met <span
                                        class="form-check-sign"><span class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-auto_square_close_at">
                                <label class="control-label" for="mcxusers-auto_square_close_at">auto-Close all active
                                    trades when the losses reach % of Ledger-balance</label>
                                <input type="number" id="mcxusers-auto_square_close_at" class="form-control"
                                    name="auto_square_off_percentage" value="90">
                                <div class="hint-block">Example: 95, will close when losses reach 95% of ledger balance
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-auto_square_notify_at">
                                <label class="control-label" for="mcxusers-auto_square_notify_at">Notify client when the
                                    losses reach % of Ledger-balance</label>
                                <input type="number" id="mcxusers-auto_square_notify_at" class="form-control"
                                    name="notify_percentage" value="70">
                                <div class="hint-block">Example: 70, will send notification to customer every 5-minutes
                                    until losses cross 70% of ledger balance</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-auto_square_notify_at">
                                <label class="control-label" for="mcxusers-auto_square_notify_at">Min. Time to book profit
                                    (No. of Seconds)</label>
                                <input type="number" id="profit_book_interval" class="form-control"
                                    name="profit_book_interval" value="120">
                                <div class="hint-block">Example: 120, will hold the trade for 2 minutes before closing a
                                    trade in profit</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset class="row">
                        <legend>MCX Futures: </legend>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-commodity">
                                <input type="hidden" name="Mcxusers[commodity]" value="0"><label><input
                                        type="checkbox" id="mcxusers-commodity" class="form-check-input"
                                        name="mcx_enabled" value="1" checked=""> MCX Trading <span
                                        class="form-check-sign"><span class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_commodity">
                                <label class="control-label" for="mcxusers-min_size_trade_commodity">Minimum lot size
                                    required per single trade of MCX</label>
                                <input type="text" id="mcxusers-min_size_trade_commodity" class="form-control"
                                    name="mcx_min_lot_per_trade" value="0">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_commodity">
                                <label class="control-label" for="mcxusers-max_size_trade_commodity">Maximum lot size
                                    allowed per single trade of MCX</label>
                                <input type="text" id="mcxusers-max_size_trade_commodity" class="form-control"
                                    name="mcx_max_lot_per_trade" value="20">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_commodity">
                                <label class="control-label" for="mcxusers-max_size_script_commodity">Maximum lot size
                                    allowed per script of MCX to be actively open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_commodity" class="form-control"
                                    name="mcx_max_lot_per_scrip" value="50">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_commodity bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_commodity">Max
                                    Size All Commodity</label>
                                <input type="text" id="mcxusers-max_size_all_commodity" class="form-control"
                                    name="max_commodity_lots" value="100">

                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mcx_brokerage_type">
                                <label class="control-label" for="mcx_brokerage_type">Mcx Brokerage Type</label>
                                <div class="dropdown">
                                    <select name="mcx_brokerage_type" id="mcx_brokerage_type"
                                        onchange="toggle_brokerage_type();">
                                        <option value="">Select Brokerage Calculation type</option>
                                        <option value="per_crore" selected="">Per Crore Basis</option>
                                        <option value="per_lot">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div id="per_crore" class="col-md-6" style="">
                            <div class="form-group field-mcxusers-commodity_brokerage">
                                <label class="control-label" for="mcxusers-commodity_brokerage">MCX brokerage</label>
                                <input type="text" id="mcx_brokerage" class="form-control" name="mcx_brokerage"
                                    value="800" required="required">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_mcx_type">
                                <label class="control-label" for="mcxusers-exposure_mcx_type">Exposure Mcx Type</label>
                                <div class="dropdown">
                                    <select name="mcx_exposure_type" onchange="toggle_exposure();">
                                        <option value="">Select Margin/Exposure Calculation type</option>
                                        <option value="per_turnover" selected="">Per Turnover Basis</option>
                                        <option value="per_lot">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div id="exposure_per_turnover" class="row">
                            <div class="col-md-6">
                                <div class="form-group field-mcxusers-exposure_mcx">
                                    <label class="control-label" for="mcxusers-exposure_mcx">Intraday Exposure/Margin
                                        MCX</label>
                                    <input type="text" id="mcxusers-exposure_mcx" class="form-control"
                                        name="mcx_intraday_margin" value="500">
                                    <div class="hint-block">Exposure auto calculates the margin money required for any new
                                        trade entry. Calculation : turnover of a trade devided by Exposure is required
                                        margin. eg. if gold having lotsize of 100 is trading @ 45000 and exposure is 200,
                                        (45000 X 100) / 200 = 22500 is required to initiate the trade.</div>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group field-mcxusers-holding_exposure_mcx">
                                    <label class="control-label" for="mcxusers-holding_exposure_mcx">Holding
                                        Exposure/Margin MCX</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx" class="form-control"
                                        name="mcx_holding_margin" value="100">
                                    <div class="hint-block">Holding Exposure auto calculates the margin money required to
                                        hold a position overnight for the next market working day. Calculation : turnover of
                                        a trade devided by Exposure is required margin. eg. if gold having lotsize of 100 is
                                        trading @ 45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required
                                        to hold position overnight. System automatically checks at a given time around
                                        market closure to check and close all trades if margin(M2M) insufficient.</div>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div id="exposure_per_lot" class="row" style="display:none;">
                            <div class="mt-2 pt-2 col-12"><label>MCX Exposure Lot wise: </label></div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-bulldex bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-bulldex">BULLDEX INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-bulldex" class="form-control"
                                        name="mcx_lot_margin[MCXBULLDEX][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-bulldex bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-bulldex">BULLDEX HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-bulldex"
                                        class="form-control" name="mcx_lot_margin[MCXBULLDEX][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-gold bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-gold">GOLD INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-gold" class="form-control"
                                        name="mcx_lot_margin[GOLD][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-gold bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-gold">GOLD HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-gold"
                                        class="form-control" name="mcx_lot_margin[GOLD][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-silver bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-silver">SILVER INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-silver" class="form-control"
                                        name="mcx_lot_margin[SILVER][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-silver bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-silver">SILVER HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-silver"
                                        class="form-control" name="mcx_lot_margin[SILVER][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-crudeoil">CRUDEOIL INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-crudeoil" class="form-control"
                                        name="mcx_lot_margin[CRUDEOIL][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-crudeoil">CRUDEOIL HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-crudeoil"
                                        class="form-control" name="mcx_lot_margin[CRUDEOIL][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-crudeoil">CRUDEOIL MINI INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-crudeoil" class="form-control"
                                        name="mcx_lot_margin[CRUDEOILM][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-crudeoil">CRUDEOIL MINI HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-crudeoil"
                                        class="form-control" name="mcx_lot_margin[CRUDEOILM][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-copper bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-copper">COPPER INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-copper" class="form-control"
                                        name="mcx_lot_margin[COPPER][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-copper bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-copper">COPPER HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-copper"
                                        class="form-control" name="mcx_lot_margin[COPPER][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-nickel bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-nickel">NICKEL INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-nickel" class="form-control"
                                        name="mcx_lot_margin[NICKEL][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-nickel bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-nickel">NICKEL HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-nickel"
                                        class="form-control" name="mcx_lot_margin[NICKEL][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-zinc bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-zinc">ZINC INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-zinc" class="form-control"
                                        name="mcx_lot_margin[ZINC][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-zinc bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-zinc">ZINC HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-zinc"
                                        class="form-control" name="mcx_lot_margin[ZINC][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-zinc bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-zincmini">ZINCMINI INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-zincmini" class="form-control"
                                        name="mcx_lot_margin[ZINCMINI][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-zinc bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-zincmini">ZINCMINI HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-zincmini"
                                        class="form-control" name="mcx_lot_margin[ZINCMINI][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-lead bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-lead">LEAD INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-lead" class="form-control"
                                        name="mcx_lot_margin[LEAD][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-lead bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-lead">LEAD HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-lead"
                                        class="form-control" name="mcx_lot_margin[LEAD][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-lead bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-lead">LEADMINI INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-leadmini" class="form-control"
                                        name="mcx_lot_margin[LEADMINI][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-lead bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-lead">LEADMINI HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-leadmini"
                                        class="form-control" name="mcx_lot_margin[LEADMINI][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-aluminium">ALUMINIUM INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-aluminium" class="form-control"
                                        name="mcx_lot_margin[ALUMINIUM][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-alumini">ALUMINI INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-alumini" class="form-control"
                                        name="mcx_lot_margin[ALUMINI][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-alumini">ALUMINI HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-alumini"
                                        class="form-control" name="mcx_lot_margin[ALUMINI][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-aluminium">ALUMINIUM HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-aluminium"
                                        class="form-control" name="mcx_lot_margin[ALUMINIUM][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-naturalgas">NATURALGAS INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-naturalgas" class="form-control"
                                        name="mcx_lot_margin[NATURALGAS][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-naturalgas">NATURALGAS HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-naturalgas"
                                        class="form-control" name="mcx_lot_margin[NATURALGAS][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-naturalgas">NATURALGAS MINI INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-naturalgas" class="form-control"
                                        name="mcx_lot_margin[NATGASMINI][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-naturalgas">NATURALGAS MINI HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-naturalgas"
                                        class="form-control" name="mcx_lot_margin[NATGASMINI][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-menthaoil bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-menthaoil">MENTHAOIL INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-menthaoil" class="form-control"
                                        name="mcx_lot_margin[MENTHAOIL][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-menthaoil bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-menthaoil">MENTHAOIL HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-menthaoil"
                                        class="form-control" name="mcx_lot_margin[MENTHAOIL][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-cotton bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-cotton">COTTON INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-cotton" class="form-control"
                                        name="mcx_lot_margin[COTTON][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-cotton bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-cotton">COTTON HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-cotton"
                                        class="form-control" name="mcx_lot_margin[COTTON][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-gold-mini bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-gold-mini">GOLDM INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-gold-mini" class="form-control"
                                        name="mcx_lot_margin[GOLDM][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-gold-mini bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-gold-mini">GOLDM HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-gold-mini"
                                        class="form-control" name="mcx_lot_margin[GOLDM][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-exposure_mcx_lot-silver-mini bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-silver-mini">SILVERM INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-silver-mini" class="form-control"
                                        name="mcx_lot_margin[SILVERM][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-silver-mini bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-silver-mini">SILVERM HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-silver-mini"
                                        class="form-control" name="mcx_lot_margin[SILVERM][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group field-mcxusers-exposure_mcx_lot-silvermic bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-exposure_mcx_lot-silvermic">SILVER MIC INTRADAY</label>
                                    <input type="text" id="mcxusers-exposure_mcx_lot-silvermic" class="form-control"
                                        name="mcx_lot_margin[SILVERMIC][INTRADAY]" value="0" required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="form-group field-mcxusers-holding_exposure_mcx_lot-silvermic bmd-form-group is-filled">
                                    <label class="control-label bmd-label-static"
                                        for="mcxusers-holding_exposure_mcx_lot-silvermic">SILVER MIC HOLDING</label>
                                    <input type="text" id="mcxusers-holding_exposure_mcx_lot-silvermic"
                                        class="form-control" name="mcx_lot_margin[SILVERMIC][HOLDING]" value="0"
                                        required="required">

                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12" id="mcx_lot_brokerage_div" style="display:none;">
                            <div class="row">
                                <div class="col-sm-12"><label>MCX Lot Wise Brokerage: </label></div>
                                <div class="col-6"><label>GOLDM: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[GOLDM]"
                                            value="0"></span>
                                </div>
                                <div class="col-6"><label>SILVERM: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[SILVERM]"
                                            value="0"></span></div>
                                <div class="col-6"><label>BULLDEX: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[MCXBULLDEX]"
                                            value="0"></span></div>
                                <div class="col-6"><label>GOLD: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[GOLD]"
                                            value="0"></span></div>
                                <div class="col-6"><label>SILVER: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[SILVER]"
                                            value="0"></span></div>
                                <div class="col-6"><label>CRUDEOIL: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[CRUDEOIL]"
                                            value="0"></span></div>
                                <div class="col-6"><label>COPPER: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[COPPER]"
                                            value="0"></span></div>
                                <div class="col-6"><label>NICKEL: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[NICKEL]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ZINC: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[ZINC]"
                                            value="0"></span></div>
                                <div class="col-6"><label>LEAD: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[LEAD]"
                                            value="0"></span></div>
                                <div class="col-6"><label>NATURALGAS: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[NATURALGAS]"
                                            value="0"></span></div>
                                <div class="col-6"><label>NATURALGAS MINI: </label><span
                                        class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[NATGASMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ALUMINIUM: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[ALUMINIUM]"
                                            value="0"></span></div>
                                <div class="col-6"><label>MENTHAOIL: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[MENTHAOIL]"
                                            value="0"></span></div>
                                <div class="col-6"><label>COTTON: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[COTTON]"
                                            value="0"></span></div>
                                <div class="col-6"><label>SILVERMIC: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[SILVERMIC]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ZINCMINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[ZINCMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ALUMINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[ALUMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>LEADMINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[LEADMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>CRUDEOIL MINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_lot_brokerage[CRUDEOILM]"
                                            value="0"></span></div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-12"><label>Orders to be away by points in each scrip MCX: </label></div>
                                <div class="col-6"><label>GOLDM: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[GOLDM]"
                                            value="0"></span>
                                </div>
                                <div class="col-6"><label>SILVERM: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[SILVERM]"
                                            value="0"></span></div>
                                <div class="col-6"><label>BULLDEX: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[MCXBULLDEX]"
                                            value="0"></span></div>
                                <div class="col-6"><label>GOLD: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[GOLD]"
                                            value="0"></span></div>
                                <div class="col-6"><label>SILVER: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[SILVER]"
                                            value="0"></span></div>
                                <div class="col-6"><label>CRUDEOIL: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[CRUDEOIL]"
                                            value="0"></span></div>
                                <div class="col-6"><label>COPPER: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[COPPER]"
                                            value="0"></span></div>
                                <div class="col-6"><label>NICKEL: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[NICKEL]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ZINC: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[ZINC]"
                                            value="0"></span></div>
                                <div class="col-6"><label>LEAD: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[LEAD]"
                                            value="0"></span></div>
                                <div class="col-6"><label>NATURALGAS: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[NATURALGAS]"
                                            value="0"></span></div>
                                <div class="col-6"><label>NATURALGAS MINI: </label><span
                                        class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[NATGASMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ALUMINIUM: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[ALUMINIUM]"
                                            value="0"></span></div>
                                <div class="col-6"><label>MENTHAOIL: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[MENTHAOIL]"
                                            value="0"></span></div>
                                <div class="col-6"><label>COTTON: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[COTTON]"
                                            value="0"></span></div>
                                <div class="col-6"><label>SILVERMIC: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[SILVERMIC]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ZINCMINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[ZINCMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>ALUMINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[ALUMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>LEADMINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[LEADMINI]"
                                            value="0"></span></div>
                                <div class="col-6"><label>CRUDEOIL MINI: </label><span class="bmd-form-group is-filled">
                                        <input type="text" class="form-control" name="mcx_bid_gap[CRUDEOILM]"
                                            value="0"></span></div>
                            </div>
                        </div>

                    </fieldset>
                    <hr>
                    <fieldset class="row">
                        <legend>Equity Futures: </legend>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-equity">
                                <input type="hidden" name="Mcxusers[equity]" value="0"><label><input
                                        type="checkbox" id="mcxusers-equity" class="form-check-input"
                                        name="nse_enabled" value="1" checked=""> Equity Trading <span
                                        class="form-check-sign"><span class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Equity brokerage Per
                                    Crore</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="nse_brokerage" value="800">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_equity">
                                <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum <span
                                        class="quantity_lot">lot</span> size required per single trade of Equity</label>
                                <input type="text" id="mcxusers-min_size_trade_equity" class="form-control"
                                    name="nse_equity_min_lot_per_trade" value="0">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_equity">
                                <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum <span
                                        class="quantity_lot">lot</span> size allowed per single trade of Equity</label>
                                <input type="text" id="mcxusers-max_size_trade_equity" class="form-control"
                                    name="nse_equity_max_lot_per_trade" value="50">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_index">
                                <label class="control-label" for="mcxusers-min_size_trade_index">Minimum <span
                                        class="quantity_lot">lot</span> size required per single trade of Equity
                                    INDEX</label>
                                <input type="text" id="mcxusers-min_size_trade_index" class="form-control"
                                    name="nse_index_min_lot_per_trade" value="0">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_index">
                                <label class="control-label" for="mcxusers-max_size_trade_index">Maximum <span
                                        class="quantity_lot">lot</span> size allowed per single trade of Equity
                                    INDEX</label>
                                <input type="text" id="mcxusers-max_size_trade_index" class="form-control"
                                    name="nse_index_max_lot_per_trade" value="20">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_equity">
                                <label class="control-label" for="mcxusers-max_size_script_equity">Maximum <span
                                        class="quantity_lot">lot</span> size allowed per scrip of Equity to be actively
                                    open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_equity" class="form-control"
                                    name="nse_equity_max_lot_per_scrip" value="100">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_index">
                                <label class="control-label" for="mcxusers-max_size_script_index">Maximum <span
                                        class="quantity_lot">lot</span> size allowed per scrip of Equity INDEX to be
                                    actively open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_index" class="form-control"
                                    name="nse_index_max_lot_per_scrip" value="100">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_equity bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_equity">Max
                                    Size All Equity</label>
                                <input type="text" id="mcxusers-max_size_all_equity" class="form-control"
                                    name="max_nse_equity_lots" value="100">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_index">Max Size
                                    All Index</label>
                                <input type="text" id="mcxusers-max_size_all_index" class="form-control"
                                    name="max_nse_index_lots" value="100">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_equity">
                                <label class="control-label" for="mcxusers-exposure_equity">Intraday Exposure/Margin
                                    Equity</label>
                                <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                    name="nse_intraday_margin" value="500">
                                <div class="hint-block">Exposure auto calculates the margin money required for any new
                                    trade entry. Calculation : turnover of a trade devided by Exposure is required margin.
                                    eg. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100)
                                    / 200 = 22500 is required to initiate the trade.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-holding_exposure_equity">
                                <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                    Exposure/Margin Equity</label>
                                <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                    name="nse_holding_margin" value="100">
                                <div class="hint-block">Holding Exposure auto calculates the margin money required to hold
                                    a position overnight for the next market working day. Calculation : turnover of a trade
                                    divided by Exposure is required margin. eg. if gold having lot size of 100 is trading @
                                    45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold
                                    position overnight. System automatically checks at a given time around market closure to
                                    check and close all trades if margin(M2M) insufficient.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-eqaway">Orders to be away by
                                    % from current price Equity</label>
                                <input type="text" id="mcxusers-eqaway" class="form-control"
                                    name="nse_bid_gap_percentage" value="0">

                                <div class="help-block"></div>
                            </div>
                        </div>

                    </fieldset>
                    <hr>
                    <fieldset class="row">
                        <legend>Options Config: </legend>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-equity">
                                <label><input type="checkbox" id="mcxusers-equity" class="form-check-input"
                                        name="options_enabled" value="1" checked=""> Index Options Trading
                                    <span class="form-check-sign"><span class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-equity">
                                <label><input type="checkbox" id="mcxusers-equity" class="form-check-input"
                                        name="equity_options_enabled" value="1" checked=""> Equity Options
                                    Trading <span class="form-check-sign"><span class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="px-3 form-check col-md-6">
                            <div class="form-group field-mcxusers-equity">
                                <label><input type="checkbox" id="mcxusers-equity" class="form-check-input"
                                        name="mcx_options_enabled" value="1" checked=""> MCX Options Trading
                                    <span class="form-check-sign"><span class="check"></span></span></label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mcx_brokerage_type">
                                <label class="control-label" for="options_brokerage_type">Options Index Brokerage
                                    Type</label>
                                <div class="dropdown">
                                    <select name="options_brokerage_type">
                                        <option value="">Select Brokerage Calculation type</option>
                                        <option value="per_crore">Per Crore Basis</option>
                                        <option value="per_lot" selected="">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options Index
                                    brokerage</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="options_brokerage" value="25">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mcx_brokerage_type">
                                <label class="control-label" for="options_brokerage_type">Options Equity Brokerage
                                    Type</label>
                                <div class="dropdown">
                                    <select name="options_equity_brokerage_type">
                                        <option value="">Select Brokerage Calculation type</option>
                                        <option value="per_crore">Per Crore Basis</option>
                                        <option value="per_lot" selected="">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options Equity
                                    brokerage</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="options_equity_brokerage" value="40">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mcx_brokerage_type">
                                <label class="control-label" for="options_brokerage_type">Options MCX Brokerage
                                    Type</label>
                                <div class="dropdown">
                                    <select name="options_mcx_brokerage_type">
                                        <option value="">Select Brokerage Calculation type</option>
                                        <option value="per_crore">Per Crore Basis</option>
                                        <option value="per_lot" selected="">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options MCX
                                    brokerage</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="options_mcx_brokerage" value="50">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options Min. Bid
                                    Price</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="options_minimum_bid" value="2">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options Index Short Selling
                                    Allowed (Sell First and Buy later)</label>
                                <div class="dropdown">
                                    <select name="options_short_selling_allowed" id="options_short_selling_allowed"
                                        onchange="shortselling_config(this.value);">
                                        <option value="0">No</option>
                                        <option value="1" selected="">Yes</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options Equity Short
                                    Selling Allowed (Sell First and Buy later)</label>
                                <div class="dropdown">
                                    <select name="options_equity_short_selling_allowed"
                                        id="options_equity_short_selling_allowed"
                                        onchange="shortselling_config(this.value);">
                                        <option value="0" selected="">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">MCX Options Short Selling
                                    Allowed (Sell First and Buy later)</label>
                                <div class="dropdown">
                                    <select name="options_mcx_short_selling_allowed"
                                        id="options_mcx_short_selling_allowed"
                                        onchange="shortselling_config(this.value);">
                                        <option value="0" selected="">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_equity">
                                <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot size
                                    required per single trade of Equity Options</label>
                                <input type="text" id="mcxusers-min_size_trade_equity" class="form-control"
                                    name="options_equity_min_lot_per_trade" value="1">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_equity">
                                <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot size
                                    allowed per single trade of Equity Options</label>
                                <input type="text" id="mcxusers-max_size_trade_equity" class="form-control"
                                    name="options_equity_max_lot_per_trade" value="10">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_index">
                                <label class="control-label" for="mcxusers-min_size_trade_index">Minimum lot size
                                    required per single trade of Equity INDEX Options</label>
                                <input type="text" id="mcxusers-min_size_trade_index" class="form-control"
                                    name="options_index_min_lot_per_trade" value="1">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_index">
                                <label class="control-label" for="mcxusers-max_size_trade_index">Maximum lot size
                                    allowed per single trade of Equity INDEX Options</label>
                                <input type="text" id="mcxusers-max_size_trade_index" class="form-control"
                                    name="options_index_max_lot_per_trade" value="20">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_equity">
                                <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot size
                                    required per single trade of MCX Options</label>
                                <input type="text" id="mcxusers-min_size_trade_equity" class="form-control"
                                    name="options_mcx_min_lot_per_trade" value="1">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_equity">
                                <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot size
                                    allowed per single trade of MCX Options</label>
                                <input type="text" id="mcxusers-max_size_trade_equity" class="form-control"
                                    name="options_mcx_max_lot_per_trade" value="20">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_equity">
                                <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot size
                                    allowed per scrip of Equity Options to be actively open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_equity" class="form-control"
                                    name="options_equity_max_lot_per_scrip" value="10">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_index">
                                <label class="control-label" for="mcxusers-max_size_script_index">Maximum lot size
                                    allowed per scrip of Equity INDEX Options to be actively open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_index" class="form-control"
                                    name="options_index_max_lot_per_scrip" value="20">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_equity">
                                <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot size
                                    allowed per scrip of MCX Options to be actively open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_equity" class="form-control"
                                    name="options_mcx_max_lot_per_scrip" value="10">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_equity bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_equity">Max
                                    Size All Equity Options</label>
                                <input type="text" id="mcxusers-max_size_all_equity" class="form-control"
                                    name="max_options_equity_lots" value="10">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_index">Max Size
                                    All Index Options</label>
                                <input type="text" id="mcxusers-max_size_all_index" class="form-control"
                                    name="max_options_index_lots" value="10">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_index">Max Size
                                    All MCX Options</label>
                                <input type="text" id="mcxusers-max_size_all_index" class="form-control"
                                    name="max_options_mcx_lots" value="10">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_equity">
                                <label class="control-label" for="mcxusers-exposure_equity">Intraday Exposure/Margin
                                    Options Index</label>
                                <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                    name="options_intraday_margin" value="7">
                                <div class="hint-block">Exposure auto calculates the margin money required for any new
                                    trade entry. Calculation : turnover of a trade divided by Exposure is required margin.
                                    e.g. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100)
                                    / 200 = 22500 is required to initiate the trade.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-holding_exposure_equity">
                                <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                    Exposure/Margin Options Index</label>
                                <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                    name="options_holding_margin" value="3">
                                <div class="hint-block">Holding Exposure auto calculates the margin money required to hold
                                    a position overnight for the next market working day. Calculation : turnover of a trade
                                    divided by Exposure is required margin. eg. if gold having lot size of 100 is trading @
                                    45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold
                                    position overnight. System automatically checks at a given time around market closure to
                                    check and close all trades if margin(M2M) insufficient.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_equity">
                                <label class="control-label" for="mcxusers-exposure_equity">Intraday Exposure/Margin
                                    Options Equity</label>
                                <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                    name="options_equity_intraday_margin" value="10">
                                <div class="hint-block">Exposure auto calculates the margin money required for any new
                                    trade entry. Calculation : turnover of a trade divided by Exposure is required margin.
                                    e.g. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100)
                                    / 200 = 22500 is required to initiate the trade.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-holding_exposure_equity">
                                <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                    Exposure/Margin Options Equity</label>
                                <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                    name="options_equity_holding_margin" value="3">
                                <div class="hint-block">Holding Exposure auto calculates the margin money required to hold
                                    a position overnight for the next market working day. Calculation : turnover of a trade
                                    divided by Exposure is required margin. eg. if gold having lot size of 100 is trading @
                                    45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold
                                    position overnight. System automatically checks at a given time around market closure to
                                    check and close all trades if margin(M2M) insufficient.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_equity">
                                <label class="control-label" for="mcxusers-exposure_equity">Intraday Exposure/Margin
                                    Options MCX</label>
                                <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                    name="options_mcx_intraday_margin" value="10">
                                <div class="hint-block">Exposure auto calculates the margin money required for any new
                                    trade entry. Calculation : turnover of a trade divided by Exposure is required margin.
                                    e.g. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100)
                                    / 200 = 22500 is required to initiate the trade.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-holding_exposure_equity">
                                <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                    Exposure/Margin Options MCX</label>
                                <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                    name="options_mcx_holding_margin" value="3">
                                <div class="hint-block">Holding Exposure auto calculates the margin money required to hold
                                    a position overnight for the next market working day. Calculation : turnover of a trade
                                    divided by Exposure is required margin. eg. if gold having lot size of 100 is trading @
                                    45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold
                                    position overnight. System automatically checks at a given time around market closure to
                                    check and close all trades if margin(M2M) insufficient.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-eqaway">Orders to be away by
                                    % from current price in Options</label>
                                <input type="text" id="mcxusers-eqaway" class="form-control"
                                    name="options_bid_gap_percentage" value="0">

                                <div class="help-block"></div>
                            </div>
                        </div>

                    </fieldset>

                    <hr>
                    <fieldset class="row" id="options_shortselling_config">
                        <legend>Options Shortselling Config: </legend>

                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mcx_brokerage_type">
                                <label class="control-label" for="options_ss_brokerage_type">Options Index Shortselling
                                    Brokerage Type</label>
                                <div class="dropdown">
                                    <select name="options_ss_brokerage_type">
                                        <option value="">Select Brokerage Calculation type</option>
                                        <option value="per_crore">Per Crore Basis</option>
                                        <option value="per_lot" selected="">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options Index Shortselling
                                    brokerage</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="options_ss_brokerage" value="50">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mcx_brokerage_type">
                                <label class="control-label" for="options_ss_brokerage_type">Options Equity Shortselling
                                    Brokerage Type</label>
                                <div class="dropdown">
                                    <select name="options_ss_equity_brokerage_type">
                                        <option value="">Select Brokerage Calculation type</option>
                                        <option value="per_crore">Per Crore Basis</option>
                                        <option value="per_lot" selected="">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options Equity Shortselling
                                    brokerage</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="options_ss_equity_brokerage" value="20">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-mcx_brokerage_type">
                                <label class="control-label" for="options_ss_brokerage_type">Options MCX Shortselling
                                    Brokerage Type</label>
                                <div class="dropdown">
                                    <select name="options_ss_mcx_brokerage_type">
                                        <option value="">Select Brokerage Calculation type</option>
                                        <option value="per_crore">Per Crore Basis</option>
                                        <option value="per_lot" selected="">Per Lot Basis</option>
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-equity_brokerage">
                                <label class="control-label" for="mcxusers-equity_brokerage">Options MCX Shortselling
                                    brokerage</label>
                                <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                    name="options_ss_mcx_brokerage" value="20">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_equity">
                                <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot size
                                    required per single trade of Equity Options Shortselling</label>
                                <input type="text" id="mcxusers-min_size_trade_equity" class="form-control"
                                    name="options_ss_equity_min_lot_per_trade" value="0">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_equity">
                                <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot size
                                    allowed per single trade of Equity Options Shortselling</label>
                                <input type="text" id="mcxusers-max_size_trade_equity" class="form-control"
                                    name="options_ss_equity_max_lot_per_trade" value="2">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_equity">
                                <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot size
                                    required per single trade of MCX Options Shortselling</label>
                                <input type="text" id="mcxusers-min_size_trade_equity" class="form-control"
                                    name="options_ss_mcx_min_lot_per_trade" value="1">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_equity">
                                <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot size
                                    allowed per single trade of MCX Options Shortselling</label>
                                <input type="text" id="mcxusers-max_size_trade_equity" class="form-control"
                                    name="options_ss_mcx_max_lot_per_trade" value="3">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-min_size_trade_index">
                                <label class="control-label" for="mcxusers-min_size_trade_index">Minimum lot size
                                    required per single trade of Equity INDEX Options Shortselling</label>
                                <input type="text" id="mcxusers-min_size_trade_index" class="form-control"
                                    name="options_ss_index_min_lot_per_trade" value="0">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_trade_index">
                                <label class="control-label" for="mcxusers-max_size_trade_index">Maximum lot size
                                    allowed per single trade of Equity INDEX Options Shortselling</label>
                                <input type="text" id="mcxusers-max_size_trade_index" class="form-control"
                                    name="options_ss_index_max_lot_per_trade" value="10">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_equity">
                                <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot size
                                    allowed per scrip of Equity Options Shortselling to be actively open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_equity" class="form-control"
                                    name="options_ss_equity_max_lot_per_scrip" value="5">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_index">
                                <label class="control-label" for="mcxusers-max_size_script_index">Maximum lot size
                                    allowed per scrip of Equity INDEX Options Shortselling to be actively open at a
                                    time</label>
                                <input type="text" id="mcxusers-max_size_script_index" class="form-control"
                                    name="options_ss_index_max_lot_per_scrip" value="10">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_script_equity">
                                <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot size
                                    allowed per scrip of MCX Options Shortselling to be actively open at a time</label>
                                <input type="text" id="mcxusers-max_size_script_equity" class="form-control"
                                    name="options_ss_mcx_max_lot_per_scrip" value="7">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_equity bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_equity">Max
                                    Size All Equity Options Shortselling</label>
                                <input type="text" id="mcxusers-max_size_all_equity" class="form-control"
                                    name="max_options_ss_equity_lots" value="10">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_index">Max Size
                                    All Index Options Shortselling</label>
                                <input type="text" id="mcxusers-max_size_all_index" class="form-control"
                                    name="max_options_ss_index_lots" value="10">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                <label class="control-label bmd-label-static" for="mcxusers-max_size_all_index">Max Size
                                    All MCX Options Shortselling</label>
                                <input type="text" id="mcxusers-max_size_all_index" class="form-control"
                                    name="max_options_ss_mcx_lots" value="30">

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_equity">
                                <label class="control-label" for="mcxusers-exposure_equity">Intraday Exposure/Margin
                                    Options Index Shortselling</label>
                                <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                    name="options_ss_intraday_margin" value="2">
                                <div class="hint-block">Exposure auto calculates the margin money required for any new
                                    trade entry. Calculation : turnover of a trade divided by Exposure is required margin.
                                    e.g. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100)
                                    / 200 = 22500 is required to initiate the trade.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-holding_exposure_equity">
                                <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                    Exposure/Margin Options Index Shortselling</label>
                                <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                    name="options_ss_holding_margin" value="1">
                                <div class="hint-block">Holding Exposure auto calculates the margin money required to hold
                                    a position overnight for the next market working day. Calculation : turnover of a trade
                                    divided by Exposure is required margin. e.g. if gold having lot size of 100 is trading @
                                    45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold
                                    position overnight. System automatically checks at a given time around market closure to
                                    check and close all trades if margin(M2M) insufficient.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_equity">
                                <label class="control-label" for="mcxusers-exposure_equity">Intraday Exposure/Margin
                                    Options Equity Shortselling</label>
                                <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                    name="options_ss_equity_intraday_margin" value="3">
                                <div class="hint-block">Exposure auto calculates the margin money required for any new
                                    trade entry. Calculation : turnover of a trade divided by Exposure is required margin.
                                    e.g. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100)
                                    / 200 = 22500 is required to initiate the trade.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-holding_exposure_equity">
                                <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                    Exposure/Margin Options Equity Shortselling</label>
                                <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                    name="options_ss_equity_holding_margin" value="2">
                                <div class="hint-block">Holding Exposure auto calculates the margin money required to hold
                                    a position overnight for the next market working day. Calculation : turnover of a trade
                                    divided by Exposure is required margin. e.g. if gold having lot size of 100 is trading @
                                    45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold
                                    position overnight. System automatically checks at a given time around market closure to
                                    check and close all trades if margin(M2M) insufficient.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-exposure_equity">
                                <label class="control-label" for="mcxusers-exposure_equity">Intraday Exposure/Margin
                                    Options MCX Shortselling</label>
                                <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                    name="options_ss_mcx_intraday_margin" value="5">
                                <div class="hint-block">Exposure auto calculates the margin money required for any new
                                    trade entry. Calculation : turnover of a trade divided by Exposure is required margin.
                                    e.g. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100)
                                    / 200 = 22500 is required to initiate the trade.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-holding_exposure_equity">
                                <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                    Exposure/Margin Options MCX Shortselling</label>
                                <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                    name="options_ss_mcx_holding_margin" value="3">
                                <div class="hint-block">Holding Exposure auto calculates the margin money required to hold
                                    a position overnight for the next market working day. Calculation : turnover of a trade
                                    divided by Exposure is required margin. e.g. if gold having lot size of 100 is trading @
                                    45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold
                                    position overnight. System automatically checks at a given time around market closure to
                                    check and close all trades if margin(M2M) insufficient.</div>
                                <div class="help-block"></div>
                            </div>
                        </div>


                    </fieldset>
                    <hr>
                    <fieldset class="row">
                        <legend>Other: </legend>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-notes">
                                <label class="control-label" for="mcxusers-notes">Notes</label>
                                <textarea id="mcxusers-notes" class="form-control" name="notes" rows="6"></textarea>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-referedby">
                                <label class="control-label" for="mcxusers-referedby">Broker</label>
                                <div class="dropdown">
                                    <select name="broker_id" id="broker_id">
                                        <option value="0">Select Broker</option>
                                         @foreach ($brokers as $broker)
                                            <option value="{{ $broker->BrokerId }}">
                                                {{ $broker->BrokerId }} : {{ $broker->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-mcxusers-phone">
                                <label class="control-label" for="mcxusers-phone">Transaction Password</label>
                                <input type="password" id="mcxusers-phone" class="form-control"
                                    name="transaction_password" value="">
                                <div class="hint-block"></div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function quantity_lots() {
            var x = document.getElementsByClassName("quantity_lot");
            if (document.getElementById('trade_equity_as_units').checked === true) {
                for (var i = 0; i < x.length; i++) {
                    x[i].innerText = "quantity"; // Change the content
                }
            } else {
                for (var i = 0; i < x.length; i++) {
                    x[i].innerText = "lots"; // Change the content
                }
            }
        }
        quantity_lots();

        document.getElementById('mcxusers-demo').addEventListener('change', function() {
            if (this.checked) {
                console.log("Demo account enabled");
                // You can also enable fields, show options, etc.
            } else {
                console.log("Demo account disabled");
            }
        });
    </script>

@endsection
