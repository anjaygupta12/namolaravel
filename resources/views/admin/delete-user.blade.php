@extends('layouts.admin')

@section('title', 'Active Users')

@section('content')
    <style>
        .card .card-body {
            color: #000000;
            position: relative;
        }
    </style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2>{{ $symbol }}</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Lots </th>
                                <th>Avg Buy Rate</th>
                                <th>Avg Sell Rate</th>
                                <th>Profit / Loss</th>
                                <th>Brokerage</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($closedTrade as $trade)
                                @php
                             
                                    $lotSize = (int) $trade->total_lots * $trade->avg_lotsize;
                                    $buyRate = (float) $trade->avg_buy_price;
                                    $sellRate = (float) $trade->avg_sale_price;
                                    $lots = (float) $trade->total_lots;
                                    $buyTurnover = $buyRate * $lotSize;
                                    $sellTurnover = $sellRate * $lotSize;
                                    $profitLoss = $sellTurnover - $buyTurnover;
                                    $brokerage = $trade->total_brokerage;
                                    $lotSize = (int) $trade->Lots * $trade->avg_lotsize;

                                    $buyDate = \Carbon\Carbon::parse($trade->timestamp)->format('Y-m-d H:i:s A');
                                    $sellDate = \Carbon\Carbon::parse($trade->timestamp_update)->format('Y-m-d H:i:s');

                                    $sameDate = $buyDate === $sellDate ? '✔️' : '❌';
                                @endphp
                                <tr>
                                    <td><a class="badge badge-pill badge-success"
                                            href="{{ route('admin.users-view', $trade->id) }}">{{ $trade->user_id }}:
                                            ({{ $trade->Username }})</a></td>
                                    <td>{{ $lots }}</td>
                                    <td>{{ number_format($buyRate, 2) }}</td>
                                    <td>{{ number_format($sellRate, 2) }}</td>
                                    <td style="color: {{ $profitLoss >= 0 ? 'green' : 'red' }};">
                                        {{ number_format($profitLoss, 2) }}
                                    </td>

                                    <td>{{ number_format($brokerage, 2) }}
                                    </td>
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

@endsection
