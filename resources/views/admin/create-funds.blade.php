@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
    <div class="card">
        {{-- Display success message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Display error message --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif


        <div class="card-body">

            <form id="addFundsForm" method="POST" action="{{ route('admin.funds.store', $data->UserId) }}">
                @csrf 

                <div class="row">

                    {{-- User display (read-only) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User</label>
                            <p class="form-control-plaintext mb-0">
                                {{ $data->FullName }} ({{ $data->UserId }})
                            </p>
                            <input type="hidden" name="userid" value="{{ $data->UserId }}">
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <input type="text" name="notes" id="notes"
                                class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes') }}">
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Funds --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">Funds</label>
                            <input type="number" step="0.01" name="amount" id="amount"
                                class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Transaction password --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaction_password">Transaction Password</label>
                            <input type="password" name="transaction_password" id="transaction_password"
                                class="form-control @error('transaction_password') is-invalid @enderror">
                            @error('transaction_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">
                            Add Funds
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
