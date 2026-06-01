@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container-fluid">
    <div class="card ">
        <div class="card-header card-header-success card-header-text">
            <div class="card-text">
                <h4 class="card-title">Update Trades</h4>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card-body">
            <form id="trade_form" action="{{ route('admin.pending-trade-create') }}" method="post">
                @csrf

                <div class="row">

                    <!-- Select2 Dropdown -->
                    <div class="col-md-6">
                        <div class="form-group field-trades-commodity required">
                            <label class="control-label">Scrip</label>
                            <select name="Symbol" class="form-control select2" required>
                                <option value="">Select Scrip</option>
                                @foreach ($forexOption as $option)
                                    <option value="{{ $option->Symbol }}">{{ $option->Symbol }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="col-md-6">
                        <div class="form-group field-trades-user_id required">
                            <label class="control-label">User ID</label>
                            <select name="userid" class="form-control">
                                <option value="">Select User</option>
                                @foreach ($tradeUser as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->user_id }} : {{ $user->Username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Lots -->
                    <div class="col-md-6">
                        <div class="form-group field-trades-lots required">
                            <label class="control-label">Lots / Units</label>
                            <input type="text" class="form-control" name="Lots" required>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6">
                        <div class="form-group field-trades-buy_rate required">
                            <label class="control-label">Price</label>
                            <input type="text" class="form-control" name="tblfcbuyprice" required>
                            <input type="hidden" value="1" name="isOrder">
                        </div>
                    </div>

                    <!-- Order Type -->
                    <div class="col-md-6">
                        <div class="form-group field-trades-sell_rate required">
                            <label class="control-label">Order Type</label>
                            <select name="Mode" class="form-control" required>
                                <option value="" disabled selected>Select Order Type</option>
                                <option value="BUY">BUY</option>
                                <option value="SELL">SELL</option>
                            </select>
                        </div>
                    </div>

                    <!-- Transaction Password -->
                    <div class="col-md-6">
                        <div class="form-group field-trades-transaction_password required">
                            <label class="control-label">Transaction Password</label>
                            <input type="password" class="form-control" name="transaction_password" required>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-success">Save</button>

            </form>
        </div>
    </div>
</div>

<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Select Scrip",
        allowClear: true,
        width: '100%'
    });

    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
});
</script>

@endsection
