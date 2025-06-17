@extends('layouts.admin')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New User</h4>
                    <p class="card-category">Add a new trading client to the system</p>
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

               
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users-store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4 class="bg-info p-2 text-white">Personal Details</h4>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="FullName">Full Name *</label>
                                    <input type="text" id="FullName" class="form-control @error('FullName') is-invalid @enderror" name="FullName" value="{{ old('FullName') }}" required maxlength="100">
                                    <small class="form-text text-muted">Enter full name of the trader</small>
                                    @error('FullName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Username">Username *</label>
                                    <input type="text" id="Username" class="form-control @error('Username') is-invalid @enderror" name="Username" value="{{ old('Username') }}" required maxlength="50">
                                    <small class="form-text text-muted">Must be unique, 4-20 characters, letters/numbers/underscore/hyphen only</small>
                                    @error('Username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Password">Password *</label>
                                    <input type="password" id="Password" class="form-control @error('Password') is-invalid @enderror" name="Password" required maxlength="100">
                                    <small class="form-text text-muted">Minimum 6 characters</small>
                                    @error('Password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="UserTransactionPassword">User Transaction Password *</label>
                                    <input type="password" id="UserTransactionPassword" class="form-control @error('UserTransactionPassword') is-invalid @enderror" name="UserTransactionPassword" required>
                                    <small class="form-text text-muted">Required for trading transactions</small>
                                    @error('UserTransactionPassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4 class="bg-info p-2 text-white">Address Details</h4>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Address">Address</label>
                                    <textarea id="Address" class="form-control @error('Address') is-invalid @enderror" name="Address" rows="3">{{ old('Address') }}</textarea>
                                    @error('Address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="City">City</label>
                                    <input type="text" id="City" class="form-control @error('City') is-invalid @enderror" name="City" value="{{ old('City') }}" maxlength="100">
                                    @error('City')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="State">State</label>
                                    <input type="text" id="State" class="form-control @error('State') is-invalid @enderror" name="State" value="{{ old('State') }}" maxlength="100">
                                    @error('State')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="PinCode">PIN Code</label>
                                    <input type="text" id="PinCode" class="form-control @error('PinCode') is-invalid @enderror" name="PinCode" value="{{ old('PinCode') }}" maxlength="6">
                                    <small class="form-text text-muted">6-digit PIN code</small>
                                    @error('PinCode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4 class="bg-info p-2 text-white">Bank Details</h4>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="BankName">Bank Name</label>
                                    <input type="text" id="BankName" class="form-control @error('BankName') is-invalid @enderror" name="BankName" value="{{ old('BankName') }}" maxlength="100">
                                    @error('BankName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="AccountNumber">Account Number</label>
                                    <input type="text" id="AccountNumber" class="form-control @error('AccountNumber') is-invalid @enderror" name="AccountNumber" value="{{ old('AccountNumber') }}" maxlength="50">
                                    @error('AccountNumber')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IFSCCode">IFSC Code</label>
                                    <input type="text" id="IFSCCode" class="form-control @error('IFSCCode') is-invalid @enderror" name="IFSCCode" value="{{ old('IFSCCode') }}" maxlength="11">
                                    <small class="form-text text-muted">Format: SBIN0123456</small>
                                    @error('IFSCCode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="AccountHolderName">Account Holder Name</label>
                                    <input type="text" id="AccountHolderName" class="form-control @error('AccountHolderName') is-invalid @enderror" name="AccountHolderName" value="{{ old('AccountHolderName') }}" maxlength="100">
                                    @error('AccountHolderName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4 class="bg-info p-2 text-white">Trading Configuration</h4>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input @error('IsDemo') is-invalid @enderror" type="checkbox" name="IsDemo" value="1" {{ old('IsDemo') == '1' ? 'checked' : '' }}>
                                        Demo Account
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                    @error('IsDemo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input @error('AllowOrdersBeyondHighLow') is-invalid @enderror" type="checkbox" name="AllowOrdersBeyondHighLow" value="1" {{ old('AllowOrdersBeyondHighLow', '1') == '1' ? 'checked' : '' }}>
                                        Allow Orders Beyond High/Low
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                    @error('AllowOrdersBeyondHighLow')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input @error('AllowOrdersBetweenHighLow') is-invalid @enderror" type="checkbox" name="AllowOrdersBetweenHighLow" value="1" {{ old('AllowOrdersBetweenHighLow', '1') == '1' ? 'checked' : '' }}>
                                        Allow Orders Between High/Low
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                    @error('AllowOrdersBetweenHighLow')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input @error('TradeEquityAsUnits') is-invalid @enderror" type="checkbox" name="TradeEquityAsUnits" value="1" {{ old('TradeEquityAsUnits', '1') == '1' ? 'checked' : '' }}>
                                        Trade Equity as Units
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                    @error('TradeEquityAsUnits')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input @error('AutoSquareOff') is-invalid @enderror" type="checkbox" name="AutoSquareOff" value="1" {{ old('AutoSquareOff', '1') == '1' ? 'checked' : '' }}>
                                        Auto Square Off
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                    @error('AutoSquareOff')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="AutoSquareOffPercentage">Auto Square Off Percentage</label>
                                    <input type="text" id="AutoSquareOffPercentage" class="form-control @error('AutoSquareOffPercentage') is-invalid @enderror" name="AutoSquareOffPercentage" value="{{ old('AutoSquareOffPercentage', '90') }}">
                                    <small class="form-text text-muted">Percentage of ledger balance</small>
                                    @error('AutoSquareOffPercentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="NotifyPercentage">Notify Percentage</label>
                                    <input type="text" id="NotifyPercentage" class="form-control @error('NotifyPercentage') is-invalid @enderror" name="NotifyPercentage" value="{{ old('NotifyPercentage', '80') }}">
                                    <small class="form-text text-muted">Notify when losses reach this percentage</small>
                                    @error('NotifyPercentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Create User</button>
                                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
