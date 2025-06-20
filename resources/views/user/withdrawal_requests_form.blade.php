@extends('layouts.user')

@section('content')
<div id="appCapsule">
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <div class="tab-content">
                <div class="tab-pane show active">
                    <h3 class="text-center">Withdraw Request Form</h3>

                    {{-- success & error toasts (optional) --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('withdrawal.submit') }}">
                        @csrf

                        {{-- Payment Method --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="payment_method">Payment Method</label>
                                <select name="payment_method" id="payment_method"
                                        class="form-control @error('payment_method') is-invalid @enderror">
                                    <option value="" disabled selected>Select a method</option>
                                    @foreach (['Google Pay','Phone Pay','Paytm','UPI','Bank Transfer'] as $method)
                                        <option value="{{ $method }}" {{ old('payment_method') === $method ? 'selected' : '' }}>
                                            {{ $method }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="amount">Amount to withdraw</label>
                                <input  type="text"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        name="amount"
                                        id="amount"
                                        value="{{ old('amount') }}"
                                        placeholder="Amount">
                            </div>
                        </div>

                        {{-- Mobile --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="mobile">Mobile Number</label>
                                <input  type="text"
                                        class="form-control @error('mobile') is-invalid @enderror"
                                        name="mobile"
                                        id="mobile"
                                        value="{{ old('mobile') }}"
                                        placeholder="Mobile Number">
                            </div>
                        </div>

                        {{-- Account Holder --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="holder_name">Account Holder Name</label>
                                <input  type="text"
                                        class="form-control @error('holder_name') is-invalid @enderror"
                                        name="holder_name"
                                        id="holder_name"
                                        value="{{ old('holder_name') }}"
                                        placeholder="Account Holder Name">
                            </div>
                        </div>

                        {{-- Account Number --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="account_number">Account Number</label>
                                <input  type="text"
                                        class="form-control @error('account_number') is-invalid @enderror"
                                        name="account_number"
                                        id="account_number"
                                        value="{{ old('account_number') }}"
                                        placeholder="Account Number">
                            </div>
                        </div>

                        {{-- IFSC --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="ifsc">IFSC</label>
                                <input  type="text"
                                        class="form-control @error('ifsc') is-invalid @enderror"
                                        name="ifsc"
                                        id="ifsc"
                                        value="{{ old('ifsc') }}"
                                        placeholder="IFSC">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group basic">
                            <div class="input-wrapper text-center">
                                <button type="submit" class="btn btn-success w-100" style="border-radius: 0;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>

                </div> {{-- .tab-pane --}}
            </div> {{-- .tab-content --}}
        </div> {{-- .wallet-card --}}
    </div> {{-- .section --}}
</div> {{-- #appCapsule --}}
@endsection

