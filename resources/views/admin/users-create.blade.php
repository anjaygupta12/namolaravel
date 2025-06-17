@extends('layouts.admin')

@section('title', 'Create New User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New User Registration</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.users-store') }}" id="userForm">
                            @csrf

                            <!-- Personal Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Personal Details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name *</label>
                                        <input name="full_name" type="text" class="form-control" maxlength="100"
                                            placeholder="Enter full name" value="{{ old('full_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username *</label>
                                        <input name="username" type="text" class="form-control" maxlength="50"
                                            placeholder="Enter username" value="{{ old('username') }}" required>
                                        <div class="hint-block">Must be unique, 4-20 characters,
                                            letters/numbers/underscore/hyphen only</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password *</label>
                                        <input name="password" type="password" class="form-control" maxlength="100"
                                            placeholder="Enter password" required>
                                        <div class="hint-block">Minimum 6 characters</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input name="email" type="email" class="form-control" maxlength="100"
                                            placeholder="Enter email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mobile *</label>
                                        <input name="mobile" type="text" class="form-control" maxlength="15"
                                            placeholder="Enter mobile number" value="{{ old('mobile') }}" required>
                                        <div class="hint-block">10-digit number starting with 6-9</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Address Details</h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control" rows="3" 
                                            placeholder="Enter full address">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input name="city" type="text" class="form-control" maxlength="100"
                                            placeholder="Enter city" value="{{ old('city') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input name="state" type="text" class="form-control" maxlength="100"
                                            placeholder="Enter state" value="{{ old('state') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PIN Code</label>
                                        <input name="pin_code" type="text" class="form-control" maxlength="6"
                                            placeholder="Enter PIN code" value="{{ old('pin_code') }}">
                                        <div class="hint-block">6-digit PIN code</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Identity Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Identity Details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input name="pan" type="text" class="form-control" maxlength="10"
                                            placeholder="Enter PAN number" value="{{ old('pan') }}">
                                        <div class="hint-block">Format: ABCDE1234F</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Aadhar Number</label>
                                        <input name="aadhar" type="text" class="form-control" maxlength="12"
                                            placeholder="Enter Aadhar number" value="{{ old('aadhar') }}">
                                        <div class="hint-block">12-digit number</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Bank Details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input name="bank_name" type="text" class="form-control" maxlength="100"
                                            placeholder="Enter bank name" value="{{ old('bank_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Number</label>
                                        <input name="account_number" type="text" class="form-control" maxlength="50"
                                            placeholder="Enter account number" value="{{ old('account_number') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IFSC Code</label>
                                        <input name="ifsc_code" type="text" class="form-control" maxlength="11"
                                            placeholder="Enter IFSC code" value="{{ old('ifsc_code') }}">
                                        <div class="hint-block">Format: SBIN0123456</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Holder Name</label>
                                        <input name="account_holder_name" type="text" class="form-control"
                                            maxlength="100" placeholder="Enter account holder name" value="{{ old('account_holder_name') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Trading Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Trading Configuration</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="is_demo" type="checkbox" class="form-check-input" value="1"
                                            {{ old('is_demo') ? 'checked' : '' }}>
                                        <label class="form-check-label">Demo Account</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="allow_orders_beyond_high_low" type="checkbox" class="form-check-input" 
                                            value="1" {{ old('allow_orders_beyond_high_low', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Allow Orders Beyond High/Low</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="allow_orders_between_high_low" type="checkbox" class="form-check-input"
                                            value="1" {{ old('allow_orders_between_high_low', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Allow Orders Between High-Low</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="trade_equity_as_units" type="checkbox" class="form-check-input"
                                            value="1" {{ old('trade_equity_as_units', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Trade Equity as Units</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="auto_square_off" type="checkbox" class="form-check-input"
                                            value="1" {{ old('auto_square_off', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Auto Square Off</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Auto Square Off Percentage</label>
                                        <input name="auto_square_off_percentage" type="text" class="form-control"
                                            value="{{ old('auto_square_off_percentage', '90') }}">
                                        <div class="hint-block">Percentage of ledger balance</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Notify Percentage</label>
                                        <input name="notify_percentage" type="text" class="form-control"
                                            value="{{ old('notify_percentage', '80') }}">
                                        <div class="hint-block">Notify when losses reach this percentage</div>
                                    </div>
                                </div>
                            </div>

                            <!-- MCX Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">MCX Configuration</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MCX Lot Margin</label>
                                        <textarea name="mcx_lot_margin_json" class="form-control" rows="3"
                                            placeholder='{"GOLD":{"INTRADAY":1000,"HOLDING":2000}}'>{{ old('mcx_lot_margin_json', '{}') }}</textarea>
                                        <div class="hint-block">JSON format for lot-wise margin</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MCX Lot Brokerage</label>
                                        <textarea name="mcx_lot_brokerage_json" class="form-control" rows="3" 
                                            placeholder='{"GOLD":20,"SILVER":15}'>{{ old('mcx_lot_brokerage_json', '{}') }}</textarea>
                                        <div class="hint-block">JSON format for lot-wise brokerage</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MCX Bid Gap</label>
                                        <textarea name="mcx_bid_gap_json" class="form-control" rows="3" 
                                            placeholder='{"GOLD":10,"SILVER":20}'>{{ old('mcx_bid_gap_json', '{}') }}</textarea>
                                        <div class="hint-block">JSON format for bid gap configuration</div>
                                    </div>
                                </div>
                            </div>

                            <!-- NSE Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">NSE Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="nse_futures_enabled" type="checkbox" class="form-check-input"
                                            value="1" {{ old('nse_futures_enabled', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Enable NSE Futures</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="nse_options_enabled" type="checkbox" class="form-check-input"
                                            value="1" {{ old('nse_options_enabled', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Enable NSE Options</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="mcx_options_enabled" type="checkbox" class="form-check-input"
                                            value="1" {{ old('mcx_options_enabled', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Enable MCX Options</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Futures Max Lot/Scrip</label>
                                        <input name="nse_futures_max_lot_per_scrip" type="text" class="form-control"
                                            value="{{ old('nse_futures_max_lot_per_scrip', '100') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Options Max Lot/Scrip</label>
                                        <input name="nse_options_max_lot_per_scrip" type="text" class="form-control"
                                            value="{{ old('nse_options_max_lot_per_scrip', '50') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>MCX Options Max Lot/Scrip</label>
                                        <input name="mcx_options_max_lot_per_scrip" type="text" class="form-control"
                                            value="{{ old('mcx_options_max_lot_per_scrip', '50') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Brokerage Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Brokerage Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Futures Brokerage</label>
                                        <input name="nse_futures_brokerage" type="text" class="form-control"
                                            value="{{ old('nse_futures_brokerage', '20.0000') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Options Brokerage</label>
                                        <input name="nse_options_brokerage" type="text" class="form-control"
                                            value="{{ old('nse_options_brokerage', '20.0000') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>MCX Options Brokerage</label>
                                        <input name="mcx_options_brokerage" type="text" class="form-control"
                                            value="{{ old('mcx_options_brokerage', '20.0000') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Margin Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Margin Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Futures Holding Margin</label>
                                        <input name="nse_futures_holding_margin" type="text" class="form-control"
                                            value="{{ old('nse_futures_holding_margin', '2.0000') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Options Holding Margin</label>
                                        <input name="nse_options_holding_margin" type="text" class="form-control"
                                            value="{{ old('nse_options_holding_margin', '2.0000') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>MCX Options Holding Margin</label>
                                        <input name="mcx_options_holding_margin" type="text" class="form-control"
                                            value="{{ old('mcx_options_holding_margin', '2.0000') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Short Selling Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Short Selling Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="nse_futures_short_selling_allowed" type="checkbox" class="form-check-input"
                                            value="1" {{ old('nse_futures_short_selling_allowed', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Allow NSE Futures Short Selling</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="nse_options_short_selling_allowed" type="checkbox" class="form-check-input"
                                            value="1" {{ old('nse_options_short_selling_allowed', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Allow NSE Options Short Selling</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="mcx_options_short_selling_allowed" type="checkbox" class="form-check-input"
                                            value="1" {{ old('mcx_options_short_selling_allowed', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label">Allow MCX Options Short Selling</label>
                                    </div>
                                </div>
                            </div>

                            <!-- User Security -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">User Security</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User Transaction Password *</label>
                                        <input name="user_transaction_password" type="password" class="form-control"
                                            placeholder="Enter User transaction password" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Transaction Password -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Security</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Admin Transaction Password *</label>
                                        <input name="admin_transaction_password" type="password" class="form-control"
                                            placeholder="Enter admin transaction password" required>
                                        <div class="hint-block">Required to save changes</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateTradeUserForm() {
            // Add any client-side validation here if needed
            return true;
        }
    </script>
@endsection
