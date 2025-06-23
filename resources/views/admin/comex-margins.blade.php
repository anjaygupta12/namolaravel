@extends('layouts.admin')

@section('title', 'Comex Margins')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Comex Margins</h4>
                        <p class="card-category">Configure Comex settings for {{ $user->FullName }}</p>
                    </div>
                    <div class="card-body">
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.comex-margins.update', $user->UserId) }}" id="comexForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Comex Config</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <label>
                                            <input name="comex_trading_enabled" type="checkbox" 
                                                class="form-check-input" value="1"
                                                {{ $user->comex_trading_enabled ? 'checked' : '' }}>
                                            Comex Trading 
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="comex_brokerage_type">Comex brokerage type</label>
                                        <select name="comex_brokerage_type" id="comex_brokerage_type" class="form-control">
                                            <option value="Per Lot" {{ $user->comex_brokerage_type == 'Per Lot' ? 'selected' : '' }}>Per Lot</option>
                                            <option value="Per Crore" {{ $user->comex_brokerage_type == 'Per Crore' ? 'selected' : '' }}>Per Crore</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="comex_brokerage">Comex brokerage</label>
                                        <input name="comex_brokerage" type="number" step="0.0001" 
                                            id="comex_brokerage" class="form-control" 
                                            value="{{ old('comex_brokerage', $user->comex_brokerage ?? '1.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade">
                                        <label class="control-label" for="minimum_lots_single_comex">Minimum lots required per single trade of Comex</label>
                                        <input name="minimum_lots_single_comex" type="number" step="0.0001" 
                                            id="minimum_lots_single_comex" class="form-control" 
                                            value="{{ old('minimum_lots_single_comex', $user->minimum_lots_single_comex ?? '0.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade">
                                        <label class="control-label" for="maximum_lots_comex">Maximum lots allowed per single trade of Comex</label>
                                        <input name="maximum_lots_comex" type="number" step="0.0001" 
                                            id="maximum_lots_comex" class="form-control" 
                                            value="{{ old('maximum_lots_comex', $user->maximum_lots_comex ?? '5.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script">
                                        <label class="control-label" for="maximum_lots_allowed">Maximum lots allowed per scrip of Comex to be actively open at a time</label>
                                        <input name="maximum_lots_allowed" type="number" step="0.0001" 
                                            id="maximum_lots_allowed" class="form-control" 
                                            value="{{ old('maximum_lots_allowed', $user->maximum_lots_allowed ?? '3.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all bmd-form-group">
                                        <label class="control-label bmd-label-static" for="max_size_all_comex">Max Size All Comex</label>
                                        <input name="max_size_all_comex" type="number" step="0.0001" 
                                            id="max_size_all_comex" class="form-control" 
                                            value="{{ old('max_size_all_comex', $user->max_size_all_comex ?? '10.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure">
                                        <label class="control-label" for="intraday_exposure_margin_comex">Intraday Exposure/Margin Comex</label>
                                        <input name="intraday_exposure_margin_comex" type="number" step="0.0001" 
                                            id="intraday_exposure_margin_comex" class="form-control" 
                                            value="{{ old('intraday_exposure_margin_comex', $user->intraday_exposure_margin_comex ?? '300.0000') }}" required>
                                        <div class="hint-block">Exposure auto calculates the margin money required for any new trade entry. Calculation : turnover of a trade devided by Exposure is required margin. eg. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure">
                                        <label class="control-label" for="holding_exposure_margin_comex">Holding Exposure/Margin Comex</label>
                                        <input name="holding_exposure_margin_comex" type="number" step="0.0001" 
                                            id="holding_exposure_margin_comex" class="form-control" 
                                            value="{{ old('holding_exposure_margin_comex', $user->holding_exposure_margin_comex ?? '100.0000') }}" required>
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required to hold a position overnight for the next market working day. Calculation : turnover of a trade divided by Exposure is required margin. eg. if gold having lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold position overnight. System automatically checks at a given time around market closure to check and close all trades if margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                        <label class="control-label bmd-label-static" for="orders_price_comex">Orders to be away by % from current price Comex</label>
                                        <input name="orders_price_comex" type="number" step="0.0001" 
                                            id="orders_price_comex" class="form-control" 
                                            value="{{ old('orders_price_comex', $user->orders_price_comex ?? '0.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Forex Config</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <label>
                                            <input name="forex_trading_enabled" type="checkbox" 
                                                class="form-check-input" value="1"
                                                {{ $user->forex_trading_enabled ? 'checked' : '' }}>
                                            Forex Trading 
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="forex_brokerage_type">Forex brokerage type</label>
                                        <select name="forex_brokerage_type" id="forex_brokerage_type" class="form-control">
                                            <option value="Per Lot" {{ $user->forex_brokerage_type == 'Per Lot' ? 'selected' : '' }}>Per Lot</option>
                                            <option value="Per Crore" {{ $user->forex_brokerage_type == 'Per Crore' ? 'selected' : '' }}>Per Crore</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="forex_brokerage">Forex brokerage</label>
                                        <input name="forex_brokerage" type="number" step="0.0001" 
                                            id="forex_brokerage" class="form-control" 
                                            value="{{ old('forex_brokerage', $user->forex_brokerage ?? '1000.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade">
                                        <label class="control-label" for="minimum_lots_single_forex">Minimum lots required per single trade of forex</label>
                                        <input name="minimum_lots_single_forex" type="number" step="0.0001" 
                                            id="minimum_lots_single_forex" class="form-control" 
                                            value="{{ old('minimum_lots_single_forex', $user->minimum_lots_single_forex ?? '0.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade">
                                        <label class="control-label" for="maximum_lots_forex">Maximum lots allowed per single trade of forex</label>
                                        <input name="maximum_lots_forex" type="number" step="0.0001" 
                                            id="maximum_lots_forex" class="form-control" 
                                            value="{{ old('maximum_lots_forex', $user->maximum_lots_forex ?? '5.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script">
                                        <label class="control-label" for="maximum_lots_allowed_forex">Maximum lots allowed per scrip of forex to be actively open at a time</label>
                                        <input name="maximum_lots_allowed_forex" type="number" step="0.0001" 
                                            id="maximum_lots_allowed_forex" class="form-control" 
                                            value="{{ old('maximum_lots_allowed_forex', $user->maximum_lots_allowed_forex ?? '2.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all bmd-form-group">
                                        <label class="control-label bmd-label-static" for="max_size_all_forex">Max Size All forex</label>
                                        <input name="max_size_all_forex" type="number" step="0.0001" 
                                            id="max_size_all_forex" class="form-control" 
                                            value="{{ old('max_size_all_forex', $user->max_size_all_forex ?? '10.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure">
                                        <label class="control-label" for="intraday_exposure_margin_forex">Intraday Exposure/Margin forex</label>
                                        <input name="intraday_exposure_margin_forex" type="number" step="0.0001" 
                                            id="intraday_exposure_margin_forex" class="form-control" 
                                            value="{{ old('intraday_exposure_margin_forex', $user->intraday_exposure_margin_forex ?? '100.0000') }}" required>
                                        <div class="hint-block">Exposure auto calculates the margin money required for any new trade entry. Calculation : turnover of a trade devided by Exposure is required margin. eg. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure">
                                        <label class="control-label" for="holding_exposure_margin_forex">Holding Exposure/Margin forex</label>
                                        <input name="holding_exposure_margin_forex" type="number" step="0.0001" 
                                            id="holding_exposure_margin_forex" class="form-control" 
                                            value="{{ old('holding_exposure_margin_forex', $user->holding_exposure_margin_forex ?? '50.0000') }}" required>
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required to hold a position overnight for the next market working day. Calculation : turnover of a trade divided by Exposure is required margin. eg. if gold having lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold position overnight. System automatically checks at a given time around market closure to check and close all trades if margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                        <label class="control-label bmd-label-static" for="orders_price_forex">Orders to be away by % from current price forex</label>
                                        <input name="orders_price_forex" type="number" step="0.0001" 
                                            id="orders_price_forex" class="form-control" 
                                            value="{{ old('orders_price_forex', $user->orders_price_forex ?? '0.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Crypto Config</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <label>
                                            <input name="crypto_trading_enabled" type="checkbox" 
                                                class="form-check-input" value="1"
                                                {{ $user->crypto_trading_enabled ? 'checked' : '' }}>
                                            Crypto Trading 
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="crypto_brokerage_type">Crypto brokerage type</label>
                                        <select name="crypto_brokerage_type" id="crypto_brokerage_type" class="form-control">
                                            <option value="Per Lot" {{ $user->crypto_brokerage_type == 'Per Lot' ? 'selected' : '' }}>Per Lot</option>
                                            <option value="Per Crore" {{ $user->crypto_brokerage_type == 'Per Crore' ? 'selected' : '' }}>Per Crore</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="crypto_brokerage">Crypto brokerage</label>
                                        <input name="crypto_brokerage" type="number" step="0.0001" 
                                            id="crypto_brokerage" class="form-control" 
                                            value="{{ old('crypto_brokerage', $user->crypto_brokerage ?? '1000.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade">
                                        <label class="control-label" for="minimum_lots_single_crypto">Minimum lots required per single trade of crypto</label>
                                        <input name="minimum_lots_single_crypto" type="number" step="0.0001" 
                                            id="minimum_lots_single_crypto" class="form-control" 
                                            value="{{ old('minimum_lots_single_crypto', $user->minimum_lots_single_crypto ?? '0.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade">
                                        <label class="control-label" for="maximum_lots_crypto">Maximum lots allowed per single trade of crypto</label>
                                        <input name="maximum_lots_crypto" type="number" step="0.0001" 
                                            id="maximum_lots_crypto" class="form-control" 
                                            value="{{ old('maximum_lots_crypto', $user->maximum_lots_crypto ?? '5.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script">
                                        <label class="control-label" for="maximum_lots_allowed_crypto">Maximum lots allowed per scrip of crypto to be actively open at a time</label>
                                        <input name="maximum_lots_allowed_crypto" type="number" step="0.0001" 
                                            id="maximum_lots_allowed_crypto" class="form-control" 
                                            value="{{ old('maximum_lots_allowed_crypto', $user->maximum_lots_allowed_crypto ?? '2.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all bmd-form-group">
                                        <label class="control-label bmd-label-static" for="max_size_all_crypto">Max Size All crypto</label>
                                        <input name="max_size_all_crypto" type="number" step="0.0001" 
                                            id="max_size_all_crypto" class="form-control" 
                                            value="{{ old('max_size_all_crypto', $user->max_size_all_crypto ?? '10.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure">
                                        <label class="control-label" for="intraday_exposure_margin_crypto">Intraday Exposure/Margin crypto</label>
                                        <input name="intraday_exposure_margin_crypto" type="number" step="0.0001" 
                                            id="intraday_exposure_margin_crypto" class="form-control" 
                                            value="{{ old('intraday_exposure_margin_crypto', $user->intraday_exposure_margin_crypto ?? '50.0000') }}" required>
                                        <div class="hint-block">Exposure auto calculates the margin money required for any new trade entry. Calculation : turnover of a trade devided by Exposure is required margin. eg. if gold having lotsize of 100 is trading @ 45000 and exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure">
                                        <label class="control-label" for="holding_exposure_margin_crypto">Holding Exposure/Margin crypto</label>
                                        <input name="holding_exposure_margin_crypto" type="number" step="0.0001" 
                                            id="holding_exposure_margin_crypto" class="form-control" 
                                            value="{{ old('holding_exposure_margin_crypto', $user->holding_exposure_margin_crypto ?? '20.0000') }}" required>
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required to hold a position overnight for the next market working day. Calculation : turnover of a trade divided by Exposure is required margin. eg. if gold having lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) / 80 = 56250 is required to hold position overnight. System automatically checks at a given time around market closure to check and close all trades if margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                        <label class="control-label bmd-label-static" for="orders_price_crypto">Orders to be away by % from current price crypto</label>
                                        <input name="orders_price_crypto" type="number" step="0.0001" 
                                            id="orders_price_crypto" class="form-control" 
                                            value="{{ old('orders_price_crypto', $user->orders_price_crypto ?? '120.0000') }}" required>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>

                            <fieldset class="row">
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="transaction_password">Transaction Password</label>
                                        <input name="transaction_password" type="password" 
                                            id="transaction_password" class="form-control" 
                                            placeholder="Enter your transaction password" required>
                                        <div class="hint-block">Enter your admin transaction password to save changes.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Users
                                </a>
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
    $(document).ready(function() {
        // Form validation
        $('#comexForm').on('submit', function(e) {
            var isValid = true;
            var errorMessage = '';
            
            // Check all required fields
            $(this).find('input[required]').each(function() {
                if ($(this).val().trim() === '') {
                    isValid = false;
                    errorMessage = 'All fields are required. Please fill in all the fields.';
                    $(this).focus();
                    return false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
            
            // Confirm before saving
            if (!confirm('Are you sure you want to save these changes?')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection
