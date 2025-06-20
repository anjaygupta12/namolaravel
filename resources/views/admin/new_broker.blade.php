@extends('layouts.admin')

@section('title', 'Brokers')

@section('content')
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
            overflow: auto;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
        }

        .bg-success {
            background-color: #822aa0 !important;
        }

        .text-white {
            color: #fff !important;
            padding: 13px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Error Message --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Brokers</h4>
                        <p class="card-category">Personal Details:</p>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.brokers-store') }}" class="form"
                            onsubmit="return validateForm()">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-name">
                                        <label class="control-label" for="mcxusers-name">First Name</label>
                                        <input type="text" id="mcxusers-name" class="form-control" name="first_name"
                                          value="{{ old('first_name', $broker->first_name ?? '') }}">
                                        <div class="hint-block">Insert Real name of the broker. Will be visible in
                                            website</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-name">
                                        <label class="control-label" for="mcxusers-name">Last Name</label>
                                        <input type="text" id="mcxusers-name" class="form-control" name="last_name"
                                            value="{{ old('last_name', $broker->last_name ?? '') }}">
                                        <div class="hint-block">Insert Real name of the broker. Will be visible in
                                            website</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Username</label>
                                        <input type="text" id="username" class="form-control" name="username"
                                            value="{{ old('username', $broker->username ?? '') }}">
                                        <div class="hint-block">username for loggin-in with, is not case sensitive.
                                            must be unique for every trader. should not contain symbols.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Password</label>
                                        <input type="text" id="password" class="form-control" name="password"
                                            value="{{ old('password', $broker->password ?? '') }}">
                                        <div class="hint-block">password for loggin-in with, is case sensitive.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-credits">
                                        <label class="control-label" for="mcxusers-credits">Transaction Password to
                                            set</label>
                                        <input type="text" id="mcxusers-credits" class="form-control"
                                            name="transaction_password" value="{{ old('transaction_password', $broker->transaction_password ?? '') }}">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Ref. Code</label>
                                        <input type="text" id="mcxusers-phone" class="form-control" name="ref_code"
                                            value="{{ old('ref_code', $broker->ref_code ?? '') }}">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-user-type">
                                        <label class="control-label" for="user-type">Type</label>
                                        <div class="dropdown">
                                            <select name="usertype">
                                                <option value="">Select User type</option>
                                                <option value="Broker"
                                                    {{ ($broker->user_type ?? '') == 'Broker' ? 'selected' : '' }}>
                                                    Broker
                                                </option>
                                                <option value="Staff"
                                                    {{ ($broker->user_type ?? '') == 'Staff' ? 'selected' : '' }}>
                                                    Office Staff
                                                </option>
                                            </select>

                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 bg-success">
                                    <h4 class="text-white">Config</h4>
                                </div>

                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-status">
                                           <input type="hidden" name="status" value="0">
                                        <input type="checkbox" class=""
                                            name="status"
                                            {{ old('status', $broker->account_status ?? 1) ? 'checked' : '' }}>
                                        <label>Account Status</label>

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">auto-Close all
                                            active trades when
                                            the losses reach % of Ledger-balance</label>
                                        <input type="text" id="mcxusers-auto_square_close_at" class="form-control"
                                            name="auto_square_off_percentage" value="90.00">
                                        <div class="hint-block">Example: 95, will close when losses reach 95% of
                                            ledger balance</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_notify_at">
                                        <label class="control-label" for="mcxusers-auto_square_notify_at">Notify
                                            client when the losses reach % of Ledger-balance</label>
                                        <input type="text" id="mcxusers-auto_square_notify_at" class="form-control"
                                            name="notify_percentage" value="70">
                                        <div class="hint-block">Example: 70, will send notification to customer
                                            every 5-minutes until losses cross 70% of ledger balance</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_notify_at">
                                        <label class="control-label" for="mcxusers-auto_square_notify_at">Profit/Loss
                                            Share in %</label>
                                        <input type="text" id="mcxusers-auto_square_notify_at" class="form-control"
                                            name="profit_share" value="0">
                                        <div class="hint-block">Example: 30, will give broker 30% of total
                                            brokerage collected from clients</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_notify_at">
                                        <label class="control-label" for="mcxusers-auto_square_notify_at">Brokerage Share
                                            in %</label>
                                        <input type="text" id="mcxusers-auto_square_notify_at" class="form-control"
                                            name="brokerage_share" value="50">
                                        <div class="hint-block">Example: 30, will give broker 30% of total
                                            brokerage collected from clients</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Trading
                                            Clients Limit</label>
                                        <input type="text" id="mcxusers-auto_square_close_at" class="form-control"
                                            name="clients_limit" value="10">
                                        <div class="hint-block">Max. no. of Trading Clients</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Sub
                                            Brokers Limit</label>
                                        <input type="text" id="mcxusers-auto_square_close_at" class="form-control"
                                            name="sub_brokers_limit" value="1">
                                        <div class="hint-block">Max. no. of Sub-brokers</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 bg-success">
                                    <h4 class="text-white">Permissions</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Sub
                                            Brokers Actions (Create, Edit)</label>
                                        <select name="sub_broker_tasks_allowed">
                                            <option value="1">Yes</option>
                                            <option value="0" selected="">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Payin
                                            Allowed</label>
                                        <select name="payin_allowed">
                                            <option value="1">Yes</option>
                                            <option value="0" selected="">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Payout
                                            Allowed</label>
                                        <select name="payout_allowed">
                                            <option value="1">Yes</option>
                                            <option value="0" selected="">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Create
                                            Clients Allowed (Create, Update and Reset Password)</label>
                                        <select name="create_client_allowed">
                                            <option value="1">Yes</option>
                                            <option value="0" selected="">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Client
                                            Tasks Allowed (Account Reset, Recalculate brokerage etc.)</label>
                                        <select name="client_tasks_allowed">
                                            <option value="1">Yes</option>
                                            <option value="0" selected="">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Trade
                                            Activity Allowed (Create, Update, Restore, Delete Trade)</label>
                                        <select name="trade_activity_allowed">
                                            <option value="1">Yes</option>
                                            <option value="0" selected="">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">Notifications
                                            Allowed</label>
                                        <select name="notifications_allowed">
                                            <option value="1">Yes</option>
                                            <option value="0" selected="">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 bg-success">
                                    <h4 class="text-white">MCX Futures</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <input type="hidden" name="Mcxusers[commodity]" value="0">
                                    <label>
                                        <input type="checkbox" name="mcx_enabled" value="1">
                                        {{-- <input type="checkbox" id="mcxusers-commodity" class="form-check-input"
                                            name="mcx_enabled" value="1" checked=""> --}}
                                        MCX Trading
                                        {{-- <span class="form-check-sign"><span class="check"></span></span> --}}
                                    </label>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type" style="opacity: 1;">
                                        <label class="control-label" for="mcx_brokerage_type">Mcx Brokerage
                                            Type</label>
                                        <div class="dropdown">
                                            <select name="mcx_brokerage_type" required="">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option value="per_crore" selected="">Per Crore Basis</option>
                                                <option value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div id="per_crore" class="col-md-6" style="">
                                    <div class="form-group field-mcxusers-commodity_brokerage" style="opacity: 1;">
                                        <label class="control-label" for="mcxusers-commodity_brokerage">MCX
                                            brokerage</label>
                                        <input type="text" id="mcx_brokerage" class="form-control"
                                            name="mcx_brokerage" value="800" required="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_mcx_type" style="opacity: 1;">
                                        <label class="control-label" for="mcxusers-exposure_mcx_type">Exposure Mcx
                                            Type</label>
                                        <div class="dropdown">
                                            <select name="mcx_exposure_type" required="">
                                                <option value="">Select Margin/Exposure Calculation type
                                                </option>
                                                <option value="per_turnover" selected="">Per Turnover Basis
                                                </option>
                                                <option value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div id="exposure_per_turnover" class="row">
                                    <div class="col-md-6">
                                        <div class="form-group field-mcxusers-exposure_mcx" style="opacity: 1;">
                                            <label class="control-label" for="mcxusers-exposure_mcx">Intraday
                                                Exposure/Margin MCX</label>
                                            <input type="text" id="mcxusers-exposure_mcx" class="form-control"
                                                name="mcx_intraday_margin" value="500" required="">
                                            <div class="hint-block">Exposure auto calculates the margin money
                                                required for any new trade entry. Calculation : turnover of a trade
                                                devided by Exposure is required margin. eg. if gold having lotsize
                                                of 100 is trading @ 45000 and exposure is 200, (45000 X 100) / 200 =
                                                22500 is required to initiate the trade.</div>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-mcxusers-holding_exposure_mcx" style="opacity: 1;">
                                            <label class="control-label" for="mcxusers-holding_exposure_mcx">Holding
                                                Exposure/Margin
                                                MCX</label>
                                            <input type="text" id="mcxusers-holding_exposure_mcx" class="form-control"
                                                name="mcx_holding_margin" value="100" required="">
                                            <div class="hint-block">Holding Exposure auto calculates the margin
                                                money required to hold a position overnight for the next market
                                                working day. Calculation : turnover of a trade devided by Exposure
                                                is required margin. eg. if gold having lotsize of 100 is trading @
                                                45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is
                                                required to hold position overnight. System automatically checks at
                                                a given time around market closure to check and close all trades if
                                                margin(M2M) insufficient.</div>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 bg-success">
                                    <h4 class="text-white">Equity Futures</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <input type="hidden" name="Mcxusers[equity]" value="0"><label><input
                                                type="checkbox" id="mcxusers-equity" class="form-check-input"
                                                name="nse_enabled" value="1" checked="">
                                            Equity Trading <span class="form-check-sign"><span
                                                    class="check"></span></span></label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage" style="opacity: 1;">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Equity
                                            brokerage Per Crore</label>
                                        <input type="text" id="mcxusers-equity_brokerage" class="form-control"
                                            name="nse_brokerage" value="800" required="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_equity" style="opacity: 1;">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Equity</label>
                                        <input type="text" id="mcxusers-exposure_equity" class="form-control"
                                            name="nse_intraday_margin" value="500" required="">
                                        <div class="hint-block">Exposure auto calculates the margin money required
                                            for any new trade entry. Calculation : turnover of a trade devided by
                                            Exposure is required margin. eg. if gold having lotsize of 100 is
                                            trading @ 45000 and exposure is 200, (45000 X 100) / 200 = 22500 is
                                            required to initiate the trade.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity" style="opacity: 1;">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin
                                            Equity</label>
                                        <input type="text" id="mcxusers-holding_exposure_equity" class="form-control"
                                            name="nse_holding_margin" value="100" required="">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money
                                            required to hold a position overnight for the next market working day.
                                            Calculation : turnover of a trade devided by Exposure is required
                                            margin. eg. if gold having lotsize of 100 is trading @ 45000 and holding
                                            exposure is 800, (45000 X 100) / 80 = 56250 is required to hold position
                                            overnight. System automatically checks at a given time around market
                                            closure to check and close all trades if margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>
                            <hr>

                            <div class="col-md-6">
                                <div class="form-group field-mcxusers-credits">
                                    <label class="control-label" for="mcxusers-credits">Transaction Password to
                                        set</label>
                                    <input type="password" id="mcxusers-credits" class="form-control"
                                        name="transaction_password" value="">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <a href="brokers.aspx" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function validateForm() {
            let isValid = true;
            const errorClass = 'is-invalid';
            const errorMsgClass = 'invalid-feedback';

            // Clear previous errors
            clearErrors();

            // Required fields validation
            const requiredFields = {
                'first_name': 'First Name is required',
                'last_name': 'Last Name is required',
                'ref_code': 'Reference Code is required',
                'usertype': 'User Type is required',
                'transaction_password': 'Your Transaction Password is required'
            };

            for (let fieldName in requiredFields) {
                const field = document.getElementsByName(fieldName)[0];
                if (!field.value.trim()) {
                    showError(field, requiredFields[fieldName]);
                    isValid = false;
                }
            }

            // Name validation (letters, spaces, and hyphens only)
            const nameFields = ['first_name', 'last_name'];
            nameFields.forEach(fieldName => {
                const field = document.getElementsByName(fieldName)[0];
                if (field.value && !/^[a-zA-Z\s-]+$/.test(field.value)) {
                    showError(field, 'Name must contain only letters, spaces, and hyphens');
                    isValid = false;
                }
            });

            // Password validation only if provided (optional in edit mode)
            const password = document.getElementsByName('password')[0];
            if (password && password.value && !validatePassword(password.value)) {
                showError(password,
                    'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character'
                );
                isValid = false;
            }

            // Broker Transaction Password validation if provided
            const brokerTransPass = document.getElementsByName('broker_transaction_password')[0];
            if (brokerTransPass && brokerTransPass.value && !validatePassword(brokerTransPass.value)) {
                showError(brokerTransPass,
                    'Transaction Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character'
                );
                isValid = false;
            }

            // Admin Transaction Password validation
            const adminTransPass = document.getElementsByName('transaction_password')[0];
            if (!adminTransPass.value.trim()) {
                showError(adminTransPass, 'Your Transaction Password is required to update broker details');
                isValid = false;
            }

            // Numeric fields validation with specific ranges
            const percentageFields = {
                'auto_square_off_percentage': {
                    min: 0,
                    max: 100,
                    message: 'Auto Square Off Percentage must be between 0 and 100'
                },
                'notify_percentage': {
                    min: 0,
                    max: 100,
                    message: 'Notify Percentage must be between 0 and 100'
                },
                'profit_share': {
                    min: 0,
                    max: 100,
                    message: 'Profit Share must be between 0 and 100'
                },
                'brokerage_share': {
                    min: 0,
                    max: 100,
                    message: 'Brokerage Share must be between 0 and 100'
                }
            };

            for (let fieldName in percentageFields) {
                const field = document.getElementsByName(fieldName)[0];
                if (field.value) {
                    const value = parseFloat(field.value);
                    if (isNaN(value) || value < percentageFields[fieldName].min || value > percentageFields[fieldName]
                        .max) {
                        showError(field, percentageFields[fieldName].message);
                        isValid = false;
                    }
                }
            }

            // Positive number validation
            const positiveNumberFields = {
                'clients_limit': 'Clients Limit must be a positive number',
                'sub_brokers_limit': 'Sub Brokers Limit must be a positive number',
                'mcx_brokerage': 'MCX Brokerage must be a positive number',
                'mcx_intraday_margin': 'MCX Intraday Margin must be a positive number',
                'mcx_holding_margin': 'MCX Holding Margin must be a positive number',
                'nse_brokerage': 'NSE Brokerage must be a positive number',
                'nse_intraday_margin': 'NSE Intraday Margin must be a positive number',
                'nse_holding_margin': 'NSE Holding Margin must be a positive number'
            };

            for (let fieldName in positiveNumberFields) {
                const field = document.getElementsByName(fieldName)[0];
                if (field.value) {
                    const value = parseFloat(field.value);
                    if (isNaN(value) || value < 0) {
                        showError(field, positiveNumberFields[fieldName]);
                        isValid = false;
                    }
                }
            }

            // MCX and NSE specific validations
            const mcxEnabled = document.getElementsByName('mcx_enabled')[0];
            if (mcxEnabled && mcxEnabled.checked) {
                // Validate MCX fields when MCX is enabled
                const mcxRequiredFields = ['mcx_brokerage_type', 'mcx_brokerage', 'mcx_exposure_type',
                    'mcx_intraday_margin', 'mcx_holding_margin'
                ];
                mcxRequiredFields.forEach(fieldName => {
                    const field = document.getElementsByName(fieldName)[0];
                    if (!field.value.trim()) {
                        showError(field, 'This field is required when MCX Trading is enabled');
                        isValid = false;
                    }
                });
            }

            const nseEnabled = document.getElementsByName('nse_enabled')[0];
            if (nseEnabled && nseEnabled.checked) {
                // Validate NSE fields when NSE is enabled
                const nseRequiredFields = ['nse_brokerage', 'nse_intraday_margin', 'nse_holding_margin'];
                nseRequiredFields.forEach(fieldName => {
                    const field = document.getElementsByName(fieldName)[0];
                    if (!field.value.trim()) {
                        showError(field, 'This field is required when Equity Trading is enabled');
                        isValid = false;
                    }
                });
            }

            return isValid;
        }

        function validatePassword(password) {
            // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return passwordRegex.test(password);
        }

        function showError(field, message) {
            field.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        function clearErrors() {
            const errorFields = document.getElementsByClassName('is-invalid');
            const errorMessages = document.getElementsByClassName('invalid-feedback');

            while (errorFields.length > 0) {
                errorFields[0].classList.remove('is-invalid');
            }

            while (errorMessages.length > 0) {
                errorMessages[0].parentNode.removeChild(errorMessages[0]);
            }
        }

        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, select');

            // Disable username field if it exists (edit mode)
            const usernameField = document.getElementsByName('username')[0];
            if (usernameField) {
                usernameField.disabled = true;
                usernameField.style.backgroundColor = '#e9ecef';
            }

            // Convert password fields to type="password"
            const passwordFields = ['password', 'broker_transaction_password', 'transaction_password'];
            passwordFields.forEach(fieldName => {
                const field = document.getElementsByName(fieldName)[0];
                if (field && field.type !== 'password') {
                    field.type = 'password';
                }
            });

            // Handle MCX/NSE section visibility
            const mcxEnabled = document.getElementsByName('mcx_enabled')[0];
            const nseEnabled = document.getElementsByName('nse_enabled')[0];

            if (mcxEnabled) {
                mcxEnabled.addEventListener('change', function() {
                    const mcxFields = document.querySelectorAll('[name^="mcx_"]');
                    mcxFields.forEach(field => {
                        if (field !== mcxEnabled) {
                            field.required = this.checked;
                            field.closest('.form-group').style.opacity = this.checked ? '1' : '0.5';
                        }
                    });
                });
                // Trigger change event to set initial state
                mcxEnabled.dispatchEvent(new Event('change'));
            }

            if (nseEnabled) {
                nseEnabled.addEventListener('change', function() {
                    const nseFields = document.querySelectorAll('[name^="nse_"]');
                    nseFields.forEach(field => {
                        if (field !== nseEnabled) {
                            field.required = this.checked;
                            field.closest('.form-group').style.opacity = this.checked ? '1' : '0.5';
                        }
                    });
                });
                // Trigger change event to set initial state
                nseEnabled.dispatchEvent(new Event('change'));
            }

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    clearErrors();
                    validateForm();
                });
            });

            // Form submit validation
            form.addEventListener('submit', function(event) {
                if (!validateForm()) {
                    event.preventDefault();
                    alert('Please fix the errors in the form before submitting.');
                }
            });
        });
    </script>
@endsection
