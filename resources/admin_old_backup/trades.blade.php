@extends('layouts.admin')

@section('title', 'Trades')

@section('content')
    <style>
        a {
            color: #000000;
            text-decoration: none;
        }

        a:hover {
            color: #251818;
            text-decoration: none;
        }
    </style>
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
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    placeholder="From Date" required="">
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="to_date" name="to_date" class="form-control"
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
                                        {{-- <th class="action-column">
                                            <label class="checkcontainer">
                                                <input type="checkbox"
                                                    onclick="checkBoxes(this, 'toggle_trade_status', 'trades')"
                                                    name="selectall">
                                                <span class="checkmark"></span>
                                            </label>
                                        </th> --}}
                                        <th>Actions</th>
                                        <th>ID</th>
                                        <th>Scrip</th>
                                        <th>Buy Rate</th>
                                        <th>Sell Rate</th>
                                        <th class="w-110">Lots / Units</th>
                                        <th>Buy Turnover</th>
                                        <th>Sell Turnover</th>
                                        <th>CMP</th>
                                        <th>Active P/L</th>
                                        <th>Margin Used</th>
                                        <th>Bought at</th>
                                        <th>Sold at</a></th>
                                        <th>Buy Ip</a></th>
                                        <th>Sell Ip</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trades as $trade)
                                        @php
                                            $lotSize = (int) $trade->Lots * $trade->LotSize;
                                        @endphp
                                        <tr id="row-{{ $trade->Pk_id }}">
                                            <td>
                                                <a href="{{ route('admin.trade-view', $trade->Pk_id) }}"
                                                    style="margin-right:10px">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('admin.trade-edit', $trade->Pk_id) }}"
                                                    style="margin-right:10px">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('admin.trade-delete', $trade->Pk_id) }}"
                                                    onclick="return confirm('Are you sure you want to delete this item?');">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>

                                            </td>
                                            <td><a style="color: black;"
                                                    href="{{ route('admin.trade-view', $trade->Pk_id) }}">{{ $trade->trade_id }}</a>
                                            </td>

                                            <td>{{ $trade->Symbol }}</td>
                                            <td>{{ $trade->Mode == 'BUY' ? $trade->BuyPrice : '' }}</td>
                                            <td>{{ $trade->Mode == 'SELL' ? $trade->BuyPrice : '' }}</td>
                                            <td>{{ (int) $trade->Lots }} /
                                                {{ number_format($lotSize, 2) }}
                                            </td>
                                            <td>{{ $trade->Mode == 'BUY' ? number_format($trade->BuyPrice * $lotSize, 2) : '' }}
                                            </td>
                                            <td>{{ $trade->Mode == 'SELL' ? number_format($trade->BuyPrice * $lotSize, 2) : '' }}
                                            </td>
                                            <td class="trade-last">{{ $trade->TradeLast }}</td>
                                            <td class="pl">0.00 </td>
                                            <td>{{ $trade->used_margin_req }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($trade->created_at)->format('d-M-Y h:i:s A') }}
                                            </td>
                                            <td>{{ $trade->Isactive != 1 ? \Carbon\Carbon::parse($trade->updated_at)->format('d-M-Y h:i:s A') : '' }}
                                            </td>
                                            <td>{{ $trade->IpAddress }}</td>
                                            <td>{{ $trade->Isactive != 1 ? $trade->IpAddress : '' }}</td>
                                            <!-- If you have a separate SellIp, replace this -->
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
