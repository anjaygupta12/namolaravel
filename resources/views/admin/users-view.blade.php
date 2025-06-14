@extends('layouts.admin')

@section('title', 'View User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">User Details</h4>
                    <p class="card-category">Viewing information for {{ $user->name }}</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">Edit User</a>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <h4 class="bg-info p-2 text-white">Personal Details</h4>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <p class="form-control-static">{{ $user->name }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <p class="form-control-static">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile</label>
                                <p class="form-control-static">{{ $user->mobile ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <p class="form-control-static">
                                    @if($user->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                    
                                    @if($user->is_demo)
                                        <span class="badge badge-info">Demo Account</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <p class="form-control-static">{{ $user->address ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <p class="form-control-static">{{ $user->city ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State</label>
                                <p class="form-control-static">{{ $user->state ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PIN Code</label>
                                <p class="form-control-static">{{ $user->pin_code ?? 'Not provided' }}</p>
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
                                <label>PAN</label>
                                <p class="form-control-static">{{ $user->pan ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Aadhar</label>
                                <p class="form-control-static">{{ $user->aadhar ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bank Name</label>
                                <p class="form-control-static">{{ $user->bank_name ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Account Number</label>
                                <p class="form-control-static">{{ $user->account_number ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>IFSC Code</label>
                                <p class="form-control-static">{{ $user->ifsc_code ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Account Holder Name</label>
                                <p class="form-control-static">{{ $user->account_holder_name ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="bg-info p-2 text-white">Account Summary</h4>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Balance</label>
                                <p class="form-control-static">₹ {{ number_format($user->balance, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gross P/L</label>
                                <p class="form-control-static">₹ {{ number_format($user->gross_pl ?? 0, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Brokerage</label>
                                <p class="form-control-static">₹ {{ number_format($user->brokerage ?? 0, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Net P/L</label>
                                <p class="form-control-static">₹ {{ number_format($user->net_pl ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="bg-info p-2 text-white">Configuration</h4>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Allow Fresh Entry Order above high & below low?</label>
                                <p class="form-control-static">
                                    @if($user->allow_orders_beyond_high_low)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-danger">No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Allow Orders between High - Low?</label>
                                <p class="form-control-static">
                                    @if($user->allow_orders_between_high_low)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-danger">No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Trade equity as units instead of lots</label>
                                <p class="form-control-static">
                                    @if($user->trade_equity_as_units)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-danger">No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Auto Square Off</label>
                                <p class="form-control-static">
                                    @if($user->auto_square_off)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-danger">No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Auto Square Off Percentage</label>
                                <p class="form-control-static">{{ $user->auto_square_off_percentage ?? 0 }}%</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Notify Percentage</label>
                                <p class="form-control-static">{{ $user->notify_percentage ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="bg-info p-2 text-white">Account Information</h4>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Created At</label>
                                <p class="form-control-static">{{ $user->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Updated</label>
                                <p class="form-control-static">{{ $user->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">Edit User</a>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
