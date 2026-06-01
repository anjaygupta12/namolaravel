@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
    <div class="container-fluid">
        <div>
            <a class="btn btn-primary"
                href="{{ route('admin.trade-edit', [
                    'id' => $trades->Pk_id,
                    'userid' => request()->route('userid'),
                ]) }}">Update</a>
            <a class="btn btn-danger"
                href="{{ route('admin.trade-delete-confirm', [
                    'id' => $trades->Pk_id,
                    'userid' => request()->route('userid'),
                ]) }}"
                onclick="return confirm('Are you sure you want to delete this item?');">
                Delete
            </a>

            @if ($trades->Isactive == 2)
                <a class="btn btn-success"
                    href="{{ route('admin.trade-restore', [
                        'id' => $trades->Pk_id,
                        'userid' => request()->route('userid'),
                    ]) }}"
                    onclick="return confirm('Are you sure you want to Restore to Buy position?');">
                    Restore {{ $trades->Mode }}
                </a>
            @endif
            <div class="pull-right" style="margin-bottom: 10px;">
            </div>
            <table id="w0" class="table table-striped table-bordered detail-view">
                <tbody>
                    @php
                       $lotSize = round($trades->Lots * $trades->LotSize, 2);
                        $buyRate = (float) $trades->BuyPrice;
                        $sellRate = (float) $trades->SalePrice;
                        $lots = (float) $trades->Lots;
                        $buyTurnover = $buyRate * $lotSize;
                        $sellTurnover = $sellRate * $lotSize;
                        $profitLoss = $sellTurnover - $buyTurnover;
                        $brokerage = $trades->brokrage;
                       
                        $buyDate = \Carbon\Carbon::parse($trades->created_at)->format('Y-m-d H:i:s A');
                        $sellDate = \Carbon\Carbon::parse($trades->updated_at)->format('Y-m-d H:i:s');

                        $sameDate = $buyDate === $sellDate ? '✔️' : '❌';
                       
                    @endphp
                    <tr>
                        <th>ID</th>
                        <td>{{ $trades->trade_id }}</td>
                    </tr>
                    <tr>
                        <th>Script</th>
                        <td>{{ $trades->Symbol }}</td>
                    </tr>
                    <tr>
                        <th>Segment</th>
                        <td>{{ $trades->TransactionMode }}</td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td>{{ $trades->user->user_id }}</td>
                    </tr>
                    <tr>
                        <th>Buy Rate</th>
                        <td>{{ $trades->Mode=='BUY' ? $trades->BuyPrice : $trades->SalePrice }}</td>
                    </tr>
                    <tr>
                        <th>Sell Rate</th>
                        <td>{{ $trades->Mode=='BUY' ? $trades->SalePrice : $trades->BuyPrice }}</td>
                    </tr>
                    <tr>
                        <th>Buy IP</th>
                        <td>{{ $trades->IpAddress }}</td>
                    </tr>

                    <tr>
                        <th>Lots / Units</th>
                        <td>{{ $trades->Lots }} / {{ $lotSize }}</td>
                    </tr>
                    <tr>
                        <th>Profit / Loss</th>
                        <td>Not booked yet</td>
                    </tr>
                    <tr>
                        <th>Buy Turnover</th>
                        <td>{{ $trades->Mode=='BUY' ? number_format($buyTurnover, 2) :  number_format($sellTurnover, 2)}}</td>
                    </tr>
                    <tr>
                        <th>Sell Turnover</th>
                        {{-- @if ($trades->Isactive != 1) --}}
                            <td>{{ $trades->Mode=='BUY' ? number_format($sellTurnover, 2): number_format($buyTurnover, 2) }}</td>
                        {{-- @else --}}
                            {{-- <td>(not set)</td> --}}
                        {{-- @endif --}}
                    </tr>
                    <tr>
                        <th>Brokerage</th>
                        <td>{{ number_format($trades->brokrage, 2) }}</td>
                        {{-- <td style="color: {{ $profitLoss >= 0 ? 'green' : 'red' }};">
                            {{ number_format($profitLoss, 2) }}
                        </td> --}}
                    </tr>

                    <tr>
                        <th>Buy Order ID</th>
                        <td><span class="not-set">{{ $trades->Mode == 'BUY'? $trades->trade_id : '(not set)' }}</span></td>
                    </tr>
                    <tr>
                        <th>Sell Order ID</th>
                        <td><span class="not-set">{{ $trades->Mode == 'SELL'? $trades->trade_id : '(not set)' }}</span></td>
                    </tr>
                    <tr>
                        <th>Bought at</th>
                        <td>{{ $trades->Mode == 'BUY'? $buyDate: '' }}</td>
                    </tr>
                    <tr>
                        <th>Sold at</th>
                        <td>{{ $trades->Mode == 'SELL'? $sellDate : '' }}</td>
                        {{-- @if ($trades->Isactive != 1)
                            <td>{{ $sellDate }}</td>
                        @else
                            <td><span class="not-set">(not set)</span></td>
                        @endif --}}
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
