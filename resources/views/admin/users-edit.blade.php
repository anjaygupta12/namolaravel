@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit User</h4>
                    <p class="card-category">Update user information for {{ $user->name }}</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users-update', $user->UserId) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4 class="bg-info p-2 text-white">Personal Details</h4>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                                    <small class="form-text text-muted">Insert real name of the trader. Will be visible in trading App</small>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                                    <small class="form-text text-muted">Email address for the user</small>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" id="mobile" class="form-control" name="mobile" value="{{ old('mobile', $user->mobile) }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('mobile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" class="form-control" name="password">
                                    <small class="form-text text-muted">Leave blank to keep current password</small>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea id="address" class="form-control" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                    <small class="form-text text-muted">Optional</small>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" class="form-control" name="city" value="{{ old('city', $user->city) }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">State</label>
                                    <input type="text" id="state" class="form-control" name="state" value="{{ old('state', $user->state) }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pin_code">PIN Code</label>
                                    <input type="text" id="pin_code" class="form-control" name="pin_code" value="{{ old('pin_code', $user->pin_code) }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('pin_code')
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
                                    <label for="pan">PAN</label>
                                    <input type="text" id="pan" class="form-control" name="pan" value="{{ old('pan', $user->pan) }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('pan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aadhar">Aadhar</label>
                                    <input type="text" id="aadhar" class="form-control" name="aadhar" value="{{ old('aadhar', $user->aadhar) }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('aadhar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" id="bank_name" class="form-control" name="bank_name" value="{{ old('bank_name', $user->bank_name ?? '') }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('bank_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_number">Account Number</label>
                                    <input type="text" id="account_number" class="form-control" name="account_number" value="{{ old('account_number', $user->account_number ?? '') }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('account_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifsc_code">IFSC Code</label>
                                    <input type="text" id="ifsc_code" class="form-control" name="ifsc_code" value="{{ old('ifsc_code', $user->ifsc_code ?? '') }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('ifsc_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_holder_name">Account Holder Name</label>
                                    <input type="text" id="account_holder_name" class="form-control" name="account_holder_name" value="{{ old('account_holder_name', $user->account_holder_name ?? '') }}">
                                    <small class="form-text text-muted">Optional</small>
                                    @error('account_holder_name')
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
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                                        Active Account
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="is_demo" value="1" {{ $user->is_demo ? 'checked' : '' }}>
                                        Demo Account?
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="allow_orders_beyond_high_low" value="1" {{ $user->allow_orders_beyond_high_low ? 'checked' : '' }}>
                                        Allow Fresh Entry Order above high & below low?
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="allow_orders_between_high_low" value="1" {{ $user->allow_orders_between_high_low ? 'checked' : '' }}>
                                        Allow Orders between High - Low?
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="trade_equity_as_units" value="1" {{ $user->trade_equity_as_units ? 'checked' : '' }}>
                                        Trade equity as units instead of lots
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="auto_square_off" value="1" {{ $user->auto_square_off ? 'checked' : '' }}>
                                        Auto Square Off
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="auto_square_off_percentage">Auto Square Off Percentage</label>
                                    <input type="number" id="auto_square_off_percentage" class="form-control" name="auto_square_off_percentage" value="{{ old('auto_square_off_percentage', $user->auto_square_off_percentage ?? 0) }}" step="0.01">
                                    <small class="form-text text-muted">Percentage at which positions will be automatically squared off</small>
                                    @error('auto_square_off_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notify_percentage">Notify Percentage</label>
                                    <input type="number" id="notify_percentage" class="form-control" name="notify_percentage" value="{{ old('notify_percentage', $user->notify_percentage ?? 0) }}" step="0.01">
                                    <small class="form-text text-muted">Percentage at which to send notification to user</small>
                                    @error('notify_percentage')
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
