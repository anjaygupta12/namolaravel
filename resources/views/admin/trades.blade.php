@extends('layouts.admin')

@section('title', 'Trades')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Trades</h4>
                        <p class="card-category">Manage all trades</p>
                    </div>
                    <div class="card-body">
                        <div class="row mx-3">
                            <div class="col-md-3">
                                <input type="text" id="from_date" name="from_date" class="form-control"
                                    placeholder="From Date" required="">
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="to_date" name="to_date" class="form-control"
                                    placeholder="To Date" required="">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="export" class="btn btn-info">Export Trades</button>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-success" href="{{ route('admin.trade.create') }}">Create Trades</a>
                            </div>
                        </div>

                        <form id="trade_search" method="GET" action="{{ route('admin.trades') }}">
                            <div class="trades-search">
                                <div class="row">
                                    {{-- ID Filter --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="tradessearch-id">ID</label>
                                            <input type="text" name="id" value="{{ request('id') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    {{-- Scrip Filter --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="tradessearch-commodity">Scrip</label>
                                            <input type="text" name="scrip_id" value="{{ request('scrip_id') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    {{-- Segment Filter --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="tradessearch-segment">Segment</label>
                                            <select name="segment" class="form-control">
                                                <option value="All">All</option>
                                                <option value="COMEX"
                                                    {{ request('segment') == 'COMEX' ? 'selected' : '' }}>COMEX</option>
                                                <option value="NSE" {{ request('segment') == 'NSE' ? 'selected' : '' }}>
                                                    Equity</option>
                                                <option value="MCX" {{ request('segment') == 'MCX' ? 'selected' : '' }}>
                                                    MCX</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- User ID --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="tradessearch-user_id">User ID</label>
                                            <input type="text" name="userid" value="{{ request('userid') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    {{-- Buy Rate --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="tradessearch-buy_rate">Buy Rate</label>
                                            <input type="text" name="buy_rate" value="{{ request('buy_rate') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    {{-- Sell Rate --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="tradessearch-sell_rate">Sell Rate</label>
                                            <input type="text" name="sell_rate" value="{{ request('sell_rate') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    {{-- Lots --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="tradessearch-lots">Lots / Units</label>
                                            <input type="text" name="lots" value="{{ request('lots') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Search</button>
                                    <a href="{{ route('admin.trades') }}" class="btn btn-default">Reset</a>
                                </div>
                            </div>
                        </form>



                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th class="action-column">
                                            <label class="checkcontainer">
                                                <input type="checkbox"
                                                    onclick="checkBoxes(this, 'toggle_trade_status', 'trades')"
                                                    name="selectall">
                                                <span class="checkmark"></span>
                                            </label>
                                        </th>
                                        <th>Actions</th>
                                        <th>ID</th>
                                        <th>Scrip</th>
                                        <th>Segment</th>
                                        <th>User ID</th>
                                        <th>Buy Rate</th>
                                        <th>Sell Rate</th>
                                        <th>Lots / Units</th>
                                        <th>Bought at</th>
                                        <th>Sold at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trades as $val)
                                        <tr data-key="20094">
                                            <td>
                                                <label class="checkcontainer"><input name="trades[]" type="checkbox"
                                                        value="20094"><span class="checkmark"></span></label>
                                            </td>
                                            <td class="text-nowrap">

                                                <a href="{{ route('admin.trade.edit', $val->id) }}" style="color: black;"
                                                    title="Edit" aria="" -="" label="Update" data=""
                                                    pjax="0"><svg aria-hidden="true"
                                                        style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1em"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                        <path fill="currentColor"
                                                            d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </td>
                                            <td class="text-nowrap"> {{ $val->id }}</td>
                                            <td class="text-nowrap"> {{ $val->scrip_id }}</td>
                                            <td class="text-nowrap"> {{ $val->segment }}</td>
                                            <td class="text-nowrap"> {{ $val->user_id }} </td>
                                            <td class="text-nowrap"> {{ $val->buy_price }}</td>
                                            <td class="text-nowrap"> {{ $val->sell_price }}</td>
                                            <td class="text-nowrap">{{ $val->lots }}</td>
                                            <td class="text-nowrap">
                                                {{ \Carbon\Carbon::parse($val->created_at)->format('n/j/Y h:i:s A') }}</td>
                                            <td class="text-nowrap">
                                                {{ \Carbon\Carbon::parse($val->updated_at)->format('n/j/Y h:i:s A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.table').DataTable();
        });
    </script>
@endsection
