@extends('layouts.admin')

@section('title', 'Closed Trades')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Closed Trades</h4>
                        <p class="card-category">History of completed trades</p>
                    </div>
                    <div class="card-body">
                        <div class="trades-search">
                            <div class="row">
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group field-tradessearch-id">
                                        <label class="control-label" for="tradessearch-id">Time Diff.</label>
                                        <input type="text" id="txttradessearchid" class="form-control" name="time"
                                            placeholder="No. of seconds" value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group field-tradessearch-commodity">
                                        <label class="control-label" for="tradessearch-commodity">Scrip</label>
                                        <input type="text" id="txttradessearchcommodity" class="form-control"
                                            name="scrip_id" placeholder="e.g. GOLD" value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group field-tradessearch-commodity">
                                        <label class="control-label" for="tradessearch-commodity">Username</label>
                                        <input type="text" id="txtuserid" class="form-control" name="username"
                                            placeholder="" value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success"
                                        onclick="LoadWatchList();">Search</button>
                                    <a href="{{ route('admin.closed-trades') }}" class="btn btn-default">Reset</a>
                                    {{-- <button type="reset" class="btn btn-default" onclick="LoadWatchList();">Reset</button> --}}
                                </div>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Scrip</th>
                                        <th>Buy Rate</th>
                                        <th>Sell Rate</th>
                                        <th>Lots / Units</th>
                                        <th>Buy Turnover</th>
                                        <th>Sell Turnover</th>
                                        <th>Profit / Loss</th>
                                        <th>Brokerage</th>
                                        <th>Bought at</th>
                                        <th>Sold at</th>
                                        <th>Buy Ip</th>
                                        <th>Sell Ip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($closedTrades as $trade)
                                        @php
                                            $lotSize = (int) $trade->Lots * $trade->LotSize;
                                            $buyRate = (float) $trade->BuyPrice;
                                            $sellRate = (float) $trade->SalePrice;
                                            $lots = (float) $trade->Lots;
                                            $buyTurnover = $buyRate * $lotSize;
                                            $sellTurnover = $sellRate * $lotSize;
                                            $profitLoss = $sellTurnover - $buyTurnover;
                                            $brokerage = $trade->brokrage;
                                            $lotSize = (int) $trade->Lots * $trade->LotSize;

                                            $buyDate = \Carbon\Carbon::parse($trade->created_at)->format(
                                                'Y-m-d H:i:s A',
                                            );
                                            $sellDate = \Carbon\Carbon::parse($trade->updated_at)->format(
                                                'Y-m-d H:i:s',
                                            );

                                            $sameDate = $buyDate === $sellDate ? '✔️' : '❌';
                                        @endphp
                                        <tr>
                                            <td><a style="color: black;"
                                                    href="{{ route('admin.trade-view', $trade->Pk_id) }}">{{ $trade->trade_id }}</a>
                                            </td>
                                            <td>{{ $trade->user->Username }}</td>
                                            <td>{{ $trade->Symbol }}</td>
                                            <td>{{ number_format($buyRate, 2) }}</td>
                                            <td>{{ number_format($sellRate, 2) }}</td>
                                            <td>{{ $lots }}/ {{ $lotSize }}</td>
                                            <td> {{ number_format($buyTurnover, 2) }}</td>
                                            <td>{{ number_format($sellTurnover, 2) }}</td>
                                            <td style="color: {{ $profitLoss >= 0 ? 'green' : 'red' }};">
                                                {{ number_format($profitLoss, 2) }}
                                            </td>

                                            <td>{{ number_format($brokerage, 2) }}
                                            </td>
                                            <td>{{ $trade->created_at }}</td>
                                            <td>{{ $trade->updated_at }} </td>
                                            <td>{{ $trade->IpAddress }}</td>
                                            <td>{{ $trade->IpAddress }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center">No records found</td>
                                        </tr>
                                    @endforelse
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
            $('.table').DataTable({
                "order": [
                    [9, "desc"]
                ],
                "pageLength": 25
            });
        });
    </script>
@endsection
