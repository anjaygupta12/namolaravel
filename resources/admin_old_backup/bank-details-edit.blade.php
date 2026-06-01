@extends('layouts.admin')

@section('title', 'Bank Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Bank Details</h4>
                    <p class="card-category">Manage bank account details for deposits</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.update-bank-details') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $bankDetails->id ?? '' }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_holder">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="account_holder" name="account_holder" value="{{ $bankDetails->account_holder ?? old('account_holder') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_number">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $bankDetails->account_number ?? old('account_number') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $bankDetails->bank_name ?? old('bank_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifsc">IFSC Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ifsc" name="ifsc" value="{{ $bankDetails->ifsc ?? old('ifsc') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phonepe">PhonePe Number</label>
                                    <input type="text" class="form-control" id="phonepe" name="phonepe" value="{{ $bankDetails->phonepe ?? old('phonepe') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="google_pay">Google Pay</label>
                                    <input type="text" class="form-control" id="google_pay" name="google_pay" value="{{ $bankDetails->google_pay ?? old('google_pay') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paytm">Paytm</label>
                                    <input type="text" class="form-control" id="paytm" name="paytm" value="{{ $bankDetails->paytm ?? old('paytm') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="upi_id">UPI ID</label>
                                    <input type="text" class="form-control" id="upi_id" name="upi_id" value="{{ $bankDetails->upi_id ?? old('upi_id') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qr_code">QR Code (Max 1MB, JPG/PNG only)</label>
                                    <input type="file" class="form-control" id="qr_code" name="qr_code" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(isset($bankDetails->qr_code) && $bankDetails->qr_code)
                                <div class="form-group">
                                    <label>Current QR Code</label>
                                    <div>
                                        <img src="{{ asset('uploads/qr_codes/' . $bankDetails->qr_code) }}" alt="QR Code" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Save Bank Details</button>
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
    $(document).ready(function() {
        // Form validation
        $('#account_holder').on('blur', function() {
            if ($(this).val() === '') {
                alert('Please enter account holder name');
                $(this).focus();
            }
        });
        
        $('#account_number').on('blur', function() {
            if ($(this).val() === '') {
                alert('Please enter account number');
                $(this).focus();
            }
        });
        
        $('#bank_name').on('blur', function() {
            if ($(this).val() === '') {
                alert('Please enter bank name');
                $(this).focus();
            }
        });
        
        $('#ifsc').on('blur', function() {
            if ($(this).val() === '') {
                alert('Please enter IFSC code');
                $(this).focus();
            }
        });
        
        // File validation
        $('#qr_code').on('change', function() {
            var fileInput = this;
            if (fileInput.files && fileInput.files[0]) {
                var fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
                var fileType = fileInput.files[0].type;
                
                if (fileSize > 1) {
                    alert('Please upload file size maximum 1 MB');
                    $(this).val('');
                    return false;
                }
                
                if (fileType !== 'image/jpeg' && fileType !== 'image/jpg' && fileType !== 'image/png') {
                    alert('Only png, jpeg and jpg files are allowed');
                    $(this).val('');
                    return false;
                }
            }
        });
    });
</script>
@endsection
