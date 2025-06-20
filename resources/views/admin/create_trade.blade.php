@extends('layouts.admin')

@section('title', isset($trade) ? 'Edit Trade' : 'Create Trade')

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Display Flash Messages --}}
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

            <form method="POST" action="{{ route('admin.trades.save') }}">
                @csrf

                @if (isset($trade->id))
                    <input type="hidden" name="id" value="{{ $trade->id }}">
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group field-trades-commodity required">
                            <label class="control-label">Scrip</label>
                            <select name="scrip_id" class="chosen-select form-control" required>
                                <option value="">Select Scrip</option>
                                <option value="">BANKNIFTY PE 49100 2025-02-27</option>
                                <option value="BANKNIFTY25FEB49200CE"
                                    {{ old('scrip_id', $trade->scrip_id ?? '') == 'BANKNIFTY25FEB49200CE' ? 'selected' : '' }}>
                                    BANKNIFTY CE 49200 2025-02-27</option>
                                <option value="BANKNIFTY25FEB49200PE"
                                    {{ old('scrip_id', $trade->scrip_id ?? '') == 'BANKNIFTY25FEB49200PE' ? 'selected' : '' }}>
                                    BANKNIFTY PE 49200 2025-02-27</option>
                                <option value="BANKNIFTY25FEB49300CE"
                                    {{ old('scrip_id', $trade->scrip_id ?? '') == 'BANKNIFTY25FEB49300CE' ? 'selected' : '' }}>
                                    BANKNIFTY CE 49300 2025-02-27</option>
                                <option value="BANKNIFTY25FEB49300PE"
                                    {{ old('scrip_id', $trade->scrip_id ?? '') == 'BANKNIFTY25FEB49300PE' ? 'selected' : '' }}>
                                    BANKNIFTY PE 49300 2025-02-27</option>
                                <option value="BANKNIFTY25FEB49400CE"
                                    {{ old('scrip_id', $trade->scrip_id ?? '') == 'BANKNIFTY25FEB49400CE' ? 'selected' : '' }}>
                                    BANKNIFTY CE 49400 2025-02-27</option>
                                <option value="BANKNIFTY25FEB49400PE"
                                    {{ old('scrip_id', $trade->scrip_id ?? '') == 'BANKNIFTY25FEB49400PE' ? 'selected' : '' }}>
                                    BANKNIFTY PE 49400 2025-02-27</option>
                                <option value="BANKNIFTY25FEB49500CE"
                                    {{ old('scrip_id', $trade->scrip_id ?? '') == 'BANKNIFTY25FEB49500CE' ? 'selected' : '' }}>
                                    BANKNIFTY CE 49500 2025-02-27</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group field-trades-user_id required">
                            <label class="control-label">User ID</label>
                            <select name="userid" class="chosen-select form-control" required>
                                <option value="">Select User</option>
                                @foreach ($tradeUser as $item)
                                    <option value="{{ $item->UserId }}"
                                        {{ old('userid', $trade->user_id ?? '') == $item->UserId ? 'selected' : '' }}>
                                        {{ $item->AccountHolderName }} ({{ $item->FullName }}) : {{ $item->UserId }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group field-trades-lots required">
                            <label class="control-label">Lots / Units</label>
                            <input type="text" name="lots" class="form-control"
                                value="{{ old('lots', $trade->lots ?? '') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group field-trades-buy_rate required">
                            <label class="control-label">Buy Rate</label>
                            <input type="text" name="buy_price" class="form-control"
                                value="{{ old('buy_price', $trade->buy_price ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group field-trades-sell_rate required">
                            <label class="control-label">Sell Rate</label>
                            <input type="text" name="sell_price" class="form-control"
                                value="{{ old('sell_price', $trade->sell_price ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group field-tradessearch-segment">
                            <label class="control-label" for="tradessearch-segment">Segment</label>
                           <select name="segment" id="tradessearch-segment" class="form-control">
                            <option value="All" {{ old('segment', $trade->segment ?? '') == 'All' ? 'selected' : '' }}>All</option>
                            <option value="COMEX" {{ old('segment', $trade->segment ?? '') == 'COMEX' ? 'selected' : '' }}>COMEX</option>
                            <option value="NSE" {{ old('segment', $trade->segment ?? '') == 'NSE' ? 'selected' : '' }}>Equity</option>
                            <option value="MCX" {{ old('segment', $trade->segment ?? '') == 'MCX' ? 'selected' : '' }}>MCX</option>
                        </select>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group field-trades-transaction_password required">
                            <label class="control-label">Transaction Password</label>
                            <input type="password" name="transaction_password" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" name="submit" class="btn btn-success">
                        {{ isset($trade->id) ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('admin.trades') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
