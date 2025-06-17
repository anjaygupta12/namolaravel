@extends('layouts.admin')

@section('title', 'Copy User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Copy User</h5>
                        <p class="text-muted">Creating a copy of user: {{ $sourceUser->FullName ?? 'Unknown' }}</p>
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

                        <form method="POST" action="{{ route('admin.users-store-copy', $sourceUser->UserId) }}" id="copyUserForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Personal Details</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Full Name *</label>
                                        <input name="full_name" type="text" class="form-control" 
                                            value="{{ old('full_name', $sourceUser->FullName ?? '') }}" required>
                                        <div class="hint-block">Insert Real name of the trader. Will be visible in trading App</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Mobile *</label>
                                        <input name="mobile" type="text" class="form-control" 
                                            value="{{ old('mobile', $sourceUser->Mobile ?? '') }}" required>
                                        <div class="hint-block">10-digit mobile number</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Username *</label>
                                        <input name="username" type="text" class="form-control" 
                                            value="{{ old('username', $sourceUser->Username ?? '') }}" required>
                                        <div class="hint-block">Username for logging-in with, is not case sensitive. Must be unique for every trader. Should not contain symbols.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Password *</label>
                                        <input name="password" type="password" class="form-control" 
                                            value="{{ old('password') }}" required>
                                        <div class="hint-block">Password for logging-in with, is case sensitive.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">City</label>
                                        <input name="city" type="text" class="form-control" 
                                            value="{{ old('city', $sourceUser->City ?? '') }}">
                                        <div class="hint-block">Optional</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Transaction Password *</label>
                                        <input name="transaction_password" type="password" class="form-control" 
                                            value="{{ old('transaction_password') }}" required>
                                        <div class="hint-block">Transaction password for secure operations</div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="is_demo" type="checkbox" class="form-check-input" value="1"
                                            {{ old('is_demo', $sourceUser->IsDemo ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            Demo account? <span class="form-check-sign"><span class="check"></span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Admin Transaction Password *</label>
                                        <input name="admin_transaction_password" type="password" class="form-control" required>
                                        <div class="hint-block">Required to create copy</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success">Save Copy</button>
                                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
