@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title">Edit User</h5>
                    </div>
                    <div class="card-body ">
                        <form method="post" action="{{ route('admin.users-update', 2004) }}"
                            id="userForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Personal Details</h4>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-name">
                                        <label class="control-label" for="full_name">Name</label>
                                        <input name="full_name" type="text" id="full_name" class="form-control"
                                            value="{{ old('full_name', $user->FullName) }}">
                                        <div class="hint-block">Insert Real name of the trader. Will be visible in trading
                                            App</div>
                                        @error('full_name')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mobile">
                                        <label class="control-label" for="mobile">Mobile</label>
                                        <input name="mobile" type="text" id="mobile" class="form-control"
                                            value="{{ old('mobile', $user->Mobile) }}">
                                        <div class="hint-block">Optional</div>
                                        @error('mobile')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-username">
                                        <label class="control-label" for="username">Username</label>
                                        <input name="username" type="text" id="username" class="form-control"
                                            value="{{ old('username', $user->Username) }}">
                                        <div class="hint-block">username for logging-in with, is not case sensitive. must be
                                            unique for every trader.</div>
                                        @error('username')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-password">
                                        <label class="control-label" for="password">Password</label>
                                        <input name="password" type="password" id="password" class="form-control">
                                        <div class="hint-block">password for logging-in with, is case sensitive. Leave Blank
                                            if you want password to remain unchanged.</div>
                                        @error('password')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-city">
                                        <label class="control-label" for="city">City</label>
                                        <input name="city" type="text" id="city" class="form-control"
                                            value="{{ old('city', $user->City) }}">
                                        <div class="hint-block">Optional</div>
                                        @error('city')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-email">
                                        <label class="control-label" for="email">Email</label>
                                        <input name="email" type="email" id="email" class="form-control"
                                            value="{{ old('email', $user->Email) }}">
                                        <div class="hint-block">Optional</div>
                                        @error('email')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Config</h4>
                                </div>

                                <!-- Demo Account Checkbox -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-demo">
                                        <input type="hidden" name="is_demo" value="0">
                                        <label>
                                            <input name="is_demo" type="checkbox" value="1"
                                                {{ old('is_demo', $user->IsDemo) ? 'checked' : '' }}>
                                            demo account?
                                        </label>
                                        @error('is_demo')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Allow Orders Beyond High/Low -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-allow_entry_ahbl">
                                        <input type="hidden" name="allow_orders_beyond_high_low" value="0">
                                        <label>
                                            <input name="allow_orders_beyond_high_low" type="checkbox" value="1"
                                                {{ old('allow_orders_beyond_high_low', $user->AllowOrdersBeyondHighLow) ? 'checked' : '' }}>
                                            Allow Fresh Entry Order above high & below low?
                                        </label>
                                        @error('allow_orders_beyond_high_low')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Allow Orders Between High/Low -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-aobhl">
                                        <input type="hidden" name="allow_orders_between_high_low" value="0">
                                        <label>
                                            <input name="allow_orders_between_high_low" type="checkbox" value="1"
                                                {{ old('allow_orders_between_high_low', $user->AllowOrdersBetweenHighLow) ? 'checked' : '' }}>
                                            Allow Orders between High - Low?
                                        </label>
                                        @error('allow_orders_between_high_low')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Trade Equity as Units -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-trade_equity_as_units">
                                        <input type="hidden" name="trade_equity_as_units" value="0">
                                        <label>
                                            <input name="trade_equity_as_units" type="checkbox" value="1"
                                                {{ old('trade_equity_as_units', $user->TradeEquityAsUnits) ? 'checked' : '' }}
                                                onchange="quantity_lots();">
                                            Trade equity as units instead of lots.
                                        </label>
                                        @error('trade_equity_as_units')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Account Status -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-status">
                                        <input type="hidden" name="account_status" value="0">
                                        <label>
                                            <input name="account_status" type="checkbox" value="1"
                                                {{ old('account_status', $user->IsActive) ? 'checked' : '' }}>
                                            Account Status
                                        </label>
                                        @error('account_status')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Auto Square Off -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_off">
                                        <input type="hidden" name="auto_square_off" value="0">
                                        <label>
                                            <input name="auto_square_off" type="checkbox" value="1"
                                                {{ old('auto_square_off', $user->auto_square_off) ? 'checked' : '' }}>
                                            Auto Close Trades if condition met
                                        </label>
                                        @error('auto_square_off')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Auto Close Percentage -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="auto_square_off_percentage">
                                            auto-Close all active trades when the losses reach % of Ledger-balance
                                        </label>
                                        <input name="auto_square_off_percentage" type="text"
                                            id="auto_square_off_percentage" class="form-control"
                                            value="{{ old('auto_square_off_percentage', $user->AutoSquareOffPercentage ?? 90) }}">
                                        <div class="hint-block">Example: 95, will close when losses reach 95% of ledger
                                            balance</div>
                                        @error('auto_square_off_percentage')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Notify Percentage -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_notify_at">
                                        <label class="control-label" for="notify_percentage">
                                            Notify client when the losses reach % of Ledger-balance
                                        </label>
                                        <input name="notify_percentage" type="text" id="notify_percentage"
                                            class="form-control"
                                            value="{{ old('notify_percentage', $user->NotifyPercentage ?? 70) }}">
                                        <div class="hint-block">
                                            Example: 70, will send notification to customer every 5-minutes until losses
                                            cross 70% of ledger balance
                                        </div>
                                        @error('notify_percentage')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Profit Book Interval -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-profit_book_interval">
                                        <label class="control-label" for="profit_book_interval">
                                            Min. Time to book profit (No. of Seconds)
                                        </label>
                                        <input name="profit_book_interval" type="text" id="profit_book_interval"
                                            class="form-control"
                                            value="{{ old('profit_book_interval', $user->profit_book_interval ?? 0) }}">
                                        <div class="hint-block">
                                            Example: 120, will hold the trade for 2 minutes before closing a trade in profit
                                        </div>
                                        @error('profit_book_interval')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- MCX Futures Section -->
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">MCX Futures</h4>
                                </div>

                                <!-- MCX Trading Enabled -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-commodity">
                                        <input type="hidden" name="mcx_enabled" value="0">
                                        <label>
                                            <input name="mcx_enabled" type="checkbox" value="1"
                                                {{ old('mcx_enabled', $user->MCXOptionsEnabled) ? 'checked' : '' }}>
                                            MCX Trading
                                        </label>
                                        @error('mcx_enabled')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- MCX Min Lot Per Trade -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_commodity">
                                        <label class="control-label" for="mcx_min_lot_per_trade">
                                            Minimum lot size required per single trade of MCX
                                        </label>
                                        <input name="mcx_min_lot_per_trade" type="text" id="mcx_min_lot_per_trade"
                                            class="form-control"
                                            value="{{ old('mcx_min_lot_per_trade', $user->minimum_lots_single_comex ?? 0) }}">
                                        @error('mcx_min_lot_per_trade')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- MCX Max Lot Per Scrip -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_commodity">
                                        <label class="control-label" for="mcx_max_lot_per_scrip">
                                            Maximum lot size allowed per single scrip of MCX
                                        </label>
                                        <input name="mcx_max_lot_per_scrip" type="text" id="mcx_max_lot_per_scrip"
                                            class="form-control"
                                            value="{{ old('mcx_max_lot_per_scrip', $user->maximum_lots_comex ?? 0) }}">
                                        @error('mcx_max_lot_per_scrip')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- MCX Max Lot All Scrips -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_commodity">
                                        <label class="control-label" for="mcx_max_lot_all_scrips">
                                            Maximum lot size allowed across all scrips of MCX
                                        </label>
                                        <input name="mcx_max_lot_all_scrips" type="text" id="mcx_max_lot_all_scrips"
                                            class="form-control"
                                            value="{{ old('mcx_max_lot_all_scrips', $user->max_size_all_comex ?? 0) }}">
                                        @error('mcx_max_lot_all_scrips')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- NSE Futures Section -->
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">NSE Futures</h4>
                                </div>

                                <!-- NSE Trading Enabled -->
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-nse_futures">
                                        <input type="hidden" name="nse_futures_enabled" value="0">
                                        <label>
                                            <input name="nse_futures_enabled" type="checkbox" value="1"
                                                {{ old('nse_futures_enabled', $user->nse_futures_enabled) ? 'checked' : '' }}>
                                            NSE Futures Trading
                                        </label>
                                        @error('nse_futures_enabled')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- NSE Min Lot Per Trade -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_nse_futures">
                                        <label class="control-label" for="nse_futures_min_lot_per_trade">
                                            Minimum lot size required per single trade of NSE Futures
                                        </label>
                                        <input name="nse_futures_min_lot_per_trade" type="text"
                                            id="nse_futures_min_lot_per_trade" class="form-control"
                                            value="{{ old('nse_futures_min_lot_per_trade', $user->nse_futures_enabled ?? 0) }}">
                                        @error('nse_futures_min_lot_per_trade')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- NSE Max Lot Per Scrip -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_nse_futures">
                                        <label class="control-label" for="nse_futures_max_lot_per_scrip">
                                            Maximum lot size allowed per single scrip of NSE Futures
                                        </label>
                                        <input name="nse_futures_max_lot_per_scrip" type="text"
                                            id="nse_futures_max_lot_per_scrip" class="form-control"
                                            value="{{ old('nse_futures_max_lot_per_scrip', $user->nse_futures_brokerage ?? 0) }}">
                                        @error('nse_futures_max_lot_per_scrip')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- NSE Max Lot All Scrips -->
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_nse_futures">
                                        <label class="control-label" for="nse_futures_max_lot_all_scrips">
                                            Maximum lot size allowed across all scrips of NSE Futures
                                        </label>
                                        <input name="nse_futures_max_lot_all_scrips" type="text"
                                            id="nse_futures_max_lot_all_scrips" class="form-control"
                                            value="{{ old('nse_futures_max_lot_all_scrips', $user->NSEFuturesMaxLotPerScrip ?? 0) }}">
                                        @error('nse_futures_max_lot_all_scrips')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Other sections -->
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Other</h4>
                                </div>

                                <div class="col-md-6">
                                   
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-broker">
                                        <label class="control-label" for="broker_id">Broker</label>
                                        <select name="broker_id" id="broker_id" class="form-control">
                                            <option value="">Select Broker</option>
                                            @foreach ($brokers as $broker)
                                                <option value="{{ $broker->id }}"
                                                    {{ old('broker_id', $user->broker_id) == $broker->id ? 'selected' : '' }}>
                                                    {{ $broker->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('broker_id')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-transaction_password">
                                        <label class="control-label" for="transaction_password">Transaction
                                            Password</label>
                                        <input name="transaction_password" type="password" id="transaction_password"
                                            class="form-control">
                                        @error('transaction_password')
                                            <div class="help-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quantity_lots() {
            // Your implementation for handling the trade_equity_as_units checkbox change
            console.log('Trade equity as units changed');
        }
    </script>

@endsection
