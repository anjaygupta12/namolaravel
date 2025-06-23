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
            <form method="POST" action="{{ route('admin.funds.withdraw') }}" id="withdrawFundsForm">
                @csrf

                <input type="hidden" name="userid" value="{{ $data->UserId }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="userid">User Name</label>
                            <input type="text" class="form-control" value="{{ $data->FullName }} ({{ $data->UserId }})"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <input type="text" name="notes" id="notes" class="form-control"
                                value="{{ old('notes') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">Funds</label>
                            <input type="text" name="amount" id="amount" class="form-control"
                                value="{{ old('amount') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaction_password">Transaction Password</label>
                            <input type="password" name="transaction_password" id="transaction_password"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <button type="submit" class="btn btn-success">Save Funds Withdrawal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
