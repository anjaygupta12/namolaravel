@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Edit User</h4>
                    <p class="card-category">Update user information for {{ $user->name }}</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users-update', $user->UserId) }}">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-12 mt-3">
            <h4 class="bg-info p-2 text-white">Personal Details</h4>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="FullName">Name</label>
                <input type="text" id="FullName" class="form-control" name="FullName" 
                       value="{{ old('FullName', $user->FullName) }}" required>
                @error('FullName')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="Email">Email</label>
                <input type="email" id="Email" class="form-control" name="Email" 
                       value="{{ old('Email', $user->Email) }}" required>
                @error('Email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="Mobile">Mobile</label>
                <input type="text" id="Mobile" class="form-control" name="Mobile" 
                       value="{{ old('Mobile', $user->Mobile) }}">
                @error('Mobile')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="Password">Password</label>
                <input type="password" id="Password" class="form-control" name="Password">
                <small class="text-muted">Leave blank to keep current password</small>
                @error('Password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="Address">Address</label>
                <textarea id="Address" class="form-control" name="Address" rows="3">{{ old('Address', $user->Address) }}</textarea>
                @error('Address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="City">City</label>
                <input type="text" id="City" class="form-control" name="City" 
                       value="{{ old('City', $user->City) }}">
                @error('City')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="State">State</label>
                <input type="text" id="State" class="form-control" name="State" 
                       value="{{ old('State', $user->State) }}">
                @error('State')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="PinCode">PIN Code</label>
                <input type="text" id="PinCode" class="form-control" name="PinCode" 
                       value="{{ old('PinCode', $user->PinCode) }}">
                @error('PinCode')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-12">
            <h4 class="bg-info p-2 text-white">Bank Details</h4>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="PAN">PAN</label>
                <input type="text" id="PAN" class="form-control" name="PAN" 
                       value="{{ old('PAN', $user->PAN) }}">
                @error('PAN')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="Aadhar">Aadhar</label>
                <input type="text" id="Aadhar" class="form-control" name="Aadhar" 
                       value="{{ old('Aadhar', $user->Aadhar) }}">
                @error('Aadhar')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="BankName">Bank Name</label>
                <input type="text" id="BankName" class="form-control" name="BankName" 
                       value="{{ old('BankName', $user->BankName) }}">
                @error('BankName')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="AccountNumber">Account Number</label>
                <input type="text" id="AccountNumber" class="form-control" name="AccountNumber" 
                       value="{{ old('AccountNumber', $user->AccountNumber) }}">
                @error('AccountNumber')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="IFSCCode">IFSC Code</label>
                <input type="text" id="IFSCCode" class="form-control" name="IFSCCode" 
                       value="{{ old('IFSCCode', $user->IFSCCode) }}">
                @error('IFSCCode')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="AccountHolderName">Account Holder Name</label>
                <input type="text" id="AccountHolderName" class="form-control" name="AccountHolderName" 
                       value="{{ old('AccountHolderName', $user->AccountHolderName) }}">
                @error('AccountHolderName')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-12">
            <h4 class="bg-info p-2 text-white">Configuration</h4>
        </div>
        
        <div class="col-md-6">
            <div class="form-check">
                <input type="hidden" name="IsActive" value="0">
                <input class="form-check-input" type="checkbox" name="IsActive" value="1" 
                       {{ old('IsActive', $user->IsActive) ? 'checked' : '' }}>
                <label class="form-check-label">Active Account</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-check">
                <input type="hidden" name="IsDemo" value="0">
                <input class="form-check-input" type="checkbox" name="IsDemo" value="1" 
                       {{ old('IsDemo', $user->IsDemo) ? 'checked' : '' }}>
                <label class="form-check-label">Demo Account</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-check">
                <input type="hidden" name="AllowOrdersBeyondHighLow" value="0">
                <input class="form-check-input" type="checkbox" name="AllowOrdersBeyondHighLow" value="1" 
                       {{ old('AllowOrdersBeyondHighLow', $user->AllowOrdersBeyondHighLow) ? 'checked' : '' }}>
                <label class="form-check-label">Allow Orders Beyond High/Low</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-check">
                <input type="hidden" name="AllowOrdersBetweenHighLow" value="0">
                <input class="form-check-input" type="checkbox" name="AllowOrdersBetweenHighLow" value="1" 
                       {{ old('AllowOrdersBetweenHighLow', $user->AllowOrdersBetweenHighLow) ? 'checked' : '' }}>
                <label class="form-check-label">Allow Orders Between High/Low</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-check">
                <input type="hidden" name="TradeEquityAsUnits" value="0">
                <input class="form-check-input" type="checkbox" name="TradeEquityAsUnits" value="1" 
                       {{ old('TradeEquityAsUnits', $user->TradeEquityAsUnits) ? 'checked' : '' }}>
                <label class="form-check-label">Trade Equity As Units</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-check">
                <input type="hidden" name="AutoSquareOff" value="0">
                <input class="form-check-input" type="checkbox" name="AutoSquareOff" value="1" 
                       {{ old('AutoSquareOff', $user->AutoSquareOff) ? 'checked' : '' }}>
                <label class="form-check-label">Auto Square Off</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="AutoSquareOffPercentage">Auto Square Off Percentage</label>
                <input type="number" id="AutoSquareOffPercentage" class="form-control" 
                       name="AutoSquareOffPercentage" step="0.01"
                       value="{{ old('AutoSquareOffPercentage', $user->AutoSquareOffPercentage) }}">
                @error('AutoSquareOffPercentage')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="NotifyPercentage">Notify Percentage</label>
                <input type="number" id="NotifyPercentage" class="form-control" 
                       name="NotifyPercentage" step="0.01"
                       value="{{ old('NotifyPercentage', $user->NotifyPercentage) }}">
                @error('NotifyPercentage')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="TransPass">Transaction Password</label>
                <input type="password" id="TransPass" class="form-control" name="TransPass">
                <small class="text-muted">Leave blank to keep current password</small>
                @error('TransPass')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Update User</button>
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
