<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Trade Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
            word-wrap: break-word;
            overflow: hidden;
            line-height: 1.2;
            height: 18px;
            max-height: 18px;
        }

        th {
            background-color: green;
            font-size: 8px;
            font-weight: bold;
            color: white;
            padding: 3px;
        }

        td {
            font-size: 8px;
            padding: 2px 3px;
        }

        h2,
        h3 {
            font-size: 12px;
            margin-bottom: 6px;
            margin-top: 12px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        h2 {
            font-size: 14px;
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .profit-positive {
            color: green;
            font-weight: bold;
        }

        .profit-negative {
            color: red;
            font-weight: bold;
        }

        .badge {
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
            display: inline-block;
            min-width: 45px;
            text-align: center;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .text-center {
            text-align: center;
        }

        .summary-row {
            background-color: #f0f0f0;
            font-weight: bold;
            height: 20px;
        }

        .summary-row td {
            padding: 3px;
            border: 1px solid #000;
        }

        /* Column widths for funds table */
        table:first-of-type th:nth-child(1),
        table:first-of-type td:nth-child(1) {
            width: 15%;
        }

        table:first-of-type th:nth-child(2),
        table:first-of-type td:nth-child(2) {
            width: 15%;
        }

        table:first-of-type th:nth-child(3),
        table:first-of-type td:nth-child(3) {
            width: 25%;
        }

        table:first-of-type th:nth-child(4),
        table:first-of-type td:nth-child(4) {
            width: 45%;
        }

        /* Pending orders tables - optimized for 8px font */
        .pending-table th,
        .pending-table td {
            padding: 1px 2px;
            font-size: 8px;
            line-height: 1.1;
            height: 16px;
        }

        .pending-table th:nth-child(1),
        .pending-table td:nth-child(1) {
            width: 4%;
        }

        .pending-table th:nth-child(2),
        .pending-table td:nth-child(2) {
            width: 6%;
        }

        .pending-table th:nth-child(3),
        .pending-table td:nth-child(3) {
            width: 6%;
        }

        .pending-table th:nth-child(4),
        .pending-table td:nth-child(4) {
            width: 8%;
        }

        .pending-table th:nth-child(5),
        .pending-table td:nth-child(5) {
            width: 10%;
        }

        .pending-table th:nth-child(6),
        .pending-table td:nth-child(6) {
            width: 8%;
        }

        .pending-table th:nth-child(7),
        .pending-table td:nth-child(7) {
            width: 8%;
        }

        .pending-table th:nth-child(8),
        .pending-table td:nth-child(8) {
            width: 7%;
        }

        .pending-table th:nth-child(9),
        .pending-table td:nth-child(9) {
            width: 8%;
        }

        .pending-table th:nth-child(10),
        .pending-table td:nth-child(10) {
            width: 15%;
        }

        .pending-table th:nth-child(11),
        .pending-table td:nth-child(11) {
            width: 10%;
        }

        /* Summary sections in pending tables */
        .pending-summary td {
            font-size: 7px;
            padding: 1px 2px;
            line-height: 1;
        }

        .pending-summary span {
            display: block;
            margin: 1px 0;
            line-height: 1.1;
        }

        /* Compact table rows */
        .compact-row {
            height: 16px !important;
            max-height: 16px !important;
        }

        /* Ensure no empty spaces */
        tr {
            height: 16px;
            max-height: 16px;
        }

        tbody tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <h2>Complete Trade Report ({{ $from }} to {{ $to }})</h2>
    @php
        $allTotal_pa = 0;
        $allTotalBrokerage = 0;
        $netAmount = 0;
    @endphp
    <!-- Funds Section -->
    <h3>Fund - Withdrawal &amp; Deposits</h3>
    <table>
        <thead>
            <tr>
                <th>Amount</th>
                <th>Funds</th>
                <th>Created At</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDeposits = 0;
                $totalWithdrawals = 0;
            @endphp

            @foreach ($funds as $val)
                @php
                    if ($val->type == 1) {
                        $totalDeposits += $val->Amount;
                    } else {
                        $totalWithdrawals += $val->Amount;
                    }
                @endphp
                <tr class="compact-row">
                    <td>{{ number_format($val->Amount, 2) }}</td>
                    <td>
                        @if ($val->type == 1)
                            <span class="badge badge-success">Deposit</span>
                        @else
                            <span class="badge badge-danger">Withdraw</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($val->created_at)->format('d-M-Y h:i:s A') }}</td>
                    <td>{{ $val->notes }}</td>
                </tr>
            @endforeach

            <!-- Funds Totals Row -->
            <tr class="summary-row">
                <td colspan="1">Totals:</td>
                <td colspan="3">
                    Total Deposits: {{ number_format($totalDeposits, 2) }} |
                    Total Withdrawals: {{ number_format($totalWithdrawals, 2) }} |
                    Net: {{ number_format($totalDeposits - $totalWithdrawals, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- MCX Pending Orders -->
    <h3>MCX Trade List</h3>
    <table class="pending-table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Scrip</th>
                <th>Buy Rate</th>
                <th>Sell Rate</th>
                <th>Lots / Units</th>
                <th>Buy Turnover</th>
                <th>Sell Turnover</th>
                <th>CMP</th>
                <th>Active P/L</th>
                <th>Margin Used</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_brokerage = 0;
                $totalProfitloss = 0;
                $totalBuyTornover = 0;
                $totalSellTornover = 0;
                $hasRecords = false;
            @endphp

            @forelse($mxcPendingTrade as $trade)
                @php
                    $hasRecords = true;
                    $lots = (float) $trade->Lots;
                    $lotSize = (int) $trade->Lots * (int) $trade->LotSize;
                    $buyRate = (float) $trade->BuyPrice;
                    $sellRate = (float) $trade->SalePrice;

                    $buyTurnover = $buyRate * $lotSize;
                    $sellTurnover = $sellRate * $lotSize;
                    $profitLoss = $sellTurnover - $buyTurnover;
                    $brokerage = (float) $trade->brokrage;
                    $total_brokerage += $brokerage;
                    $totalProfitloss += $profitLoss;
                    $totalBuyTornover += $buyTurnover;
                    $totalSellTornover += $sellTurnover;

                    $allTotal_pa += $profitLoss;
                    $allTotalBrokerage += $brokerage;
                    $netAmount += $profitLoss - $brokerage;

                    $buyDate = \Carbon\Carbon::parse($trade->created_at)->format('d-m-Y h:i:s A');
                    $sellDate = \Carbon\Carbon::parse($trade->updated_at)->format('d-m-Y h:i:s A');
                @endphp

                <tr class="compact-row">
                    <td>{{ $trade->trade_id }}</td>
                    <td>{{ $trade->Symbol }}</td>
                    <td>{{ number_format($buyRate, 2) }}</td>
                    <td>{{ number_format($sellRate, 2) }}</td>
                    <td>{{ $lots }} / {{ $lotSize }}</td>
                    <td>{{ number_format($buyTurnover, 2) }}</td>
                    <td>{{ number_format($sellTurnover, 2) }}</td>
                    <td style="color: {{ $profitLoss >= 0 ? 'green' : 'red' }};">
                        {{ number_format($profitLoss, 2) }}
                    </td>
                    <td>{{ number_format($brokerage, 2) }}</td>
                    <td>{{ $buyDate }}</td>
                    <td>{{ $sellDate }}</td>
                </tr>
            @empty
                <tr class="compact-row">
                    <td colspan="11" class="text-center">No records found</td>
                </tr>
            @endforelse

            @if ($hasRecords)
                <tr class="pending-summary summary-row">
                    <td colspan="6">
                        <span>Total Brokerage: {{ number_format($total_brokerage, 2) }}</span>
                        <span>Buy Turnover: {{ number_format($totalBuyTornover, 2) }}</span>
                        <span>Sell Turnover: {{ number_format($totalSellTornover, 2) }}</span>
                    </td>
                    <td colspan="5">
                        Total Profit/Loss:
                        <span style="color: {{ $totalProfitloss >= 0 ? 'green' : 'red' }}; font-size: 9px;">
                            {{ number_format($totalProfitloss, 2) }}
                        </span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Equity Pending Orders -->
    <h3>Equity Trades List</h3>
    <table class="pending-table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Scrip</th>
                <th>Buy Rate</th>
                <th>Sell Rate</th>
                <th>Lots / Units</th>
                <th>Buy Turnover</th>
                <th>Sell Turnover</th>
                <th>CMP</th>
                <th>Active P/L</th>
                <th>Margin Used</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_brokerage = 0;
                $totalProfitloss = 0;
                $totalBuyTornover = 0;
                $totalSellTornover = 0;
                $hasRecords = false;
            @endphp

            @forelse($equityPendingTrade as $trade)
                @php
                    $hasRecords = true;
                    $lots = (float) $trade->Lots;
                    $lotSize = (int) $trade->Lots * (int) $trade->LotSize;
                    $buyRate = (float) $trade->BuyPrice;
                    $sellRate = (float) $trade->SalePrice;

                    $buyTurnover = $buyRate * $lotSize;
                    $sellTurnover = $sellRate * $lotSize;
                    $profitLoss = $sellTurnover - $buyTurnover;
                    $brokerage = (float) $trade->brokrage;
                    $total_brokerage += $brokerage;
                    $totalProfitloss += $profitLoss;
                    $totalBuyTornover += $buyTurnover;
                    $totalSellTornover += $sellTurnover;

                    $allTotal_pa += $profitLoss;
                    $allTotalBrokerage += $brokerage;
                    $netAmount += $profitLoss - $brokerage;

                    $buyDate = \Carbon\Carbon::parse($trade->created_at)->format('d-m-Y h:i:s A');
                    $sellDate = \Carbon\Carbon::parse($trade->updated_at)->format('d-m-Y h:i:s A');
                @endphp

                <tr class="compact-row">
                    <td>{{ $trade->trade_id }}</td>
                    <td>{{ $trade->Symbol }}</td>
                    <td>{{ number_format($buyRate, 2) }}</td>
                    <td>{{ number_format($sellRate, 2) }}</td>
                    <td>{{ $lots }} / {{ $lotSize }}</td>
                    <td>{{ number_format($buyTurnover, 2) }}</td>
                    <td>{{ number_format($sellTurnover, 2) }}</td>
                    <td style="color: {{ $profitLoss >= 0 ? 'green' : 'red' }};">
                        {{ number_format($profitLoss, 2) }}
                    </td>
                    <td>{{ number_format($brokerage, 2) }}</td>
                    <td>{{ $buyDate }}</td>
                    <td>{{ $sellDate }}</td>
                </tr>
            @empty
                <tr class="compact-row">
                    <td colspan="11" class="text-center">No records found</td>
                </tr>
            @endforelse

            @if ($hasRecords)
                <tr class="pending-summary summary-row">
                    <td colspan="6">
                        <span>Total Brokerage: {{ number_format($total_brokerage, 2) }}</span>
                        <span>Buy Turnover: {{ number_format($totalBuyTornover, 2) }}</span>
                        <span>Sell Turnover: {{ number_format($totalSellTornover, 2) }}</span>
                    </td>
                    <td colspan="5">
                        Total Profit/Loss:
                        <span style="color: {{ $totalProfitloss >= 0 ? 'green' : 'red' }}; font-size: 9px;">
                            {{ number_format($totalProfitloss, 2) }}
                        </span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- COMEX Pending Orders -->
    <h3>Comix Trades List</h3>
    <table class="pending-table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Scrip</th>
                <th>Buy Rate</th>
                <th>Sell Rate</th>
                <th>Lots / Units</th>
                <th>Buy Turnover</th>
                <th>Sell Turnover</th>
                <th>CMP</th>
                <th>Active P/L</th>
                <th>Margin Used</th>
        </thead>
        <tbody>
            @php
                $total_brokerage = 0;
                $totalProfitloss = 0;
                $totalBuyTornover = 0;
                $totalSellTornover = 0;
                $hasRecords = false;
            @endphp

            @forelse($comexPendingTrade as $trade)
                @php
                    $hasRecords = true;
                    $lots = (float) $trade->Lots;
                    $lotSize = (int) $trade->Lots * (int) $trade->LotSize;
                    $buyRate = (float) $trade->BuyPrice;
                    $sellRate = (float) $trade->SalePrice;

                    $buyTurnover = $buyRate * $lotSize;
                    $sellTurnover = $sellRate * $lotSize;
                    $profitLoss = $sellTurnover - $buyTurnover;
                    $brokerage = (float) $trade->brokrage;
                    $total_brokerage += $brokerage;
                    $totalProfitloss += $profitLoss;
                    $totalBuyTornover += $buyTurnover;
                    $totalSellTornover += $sellTurnover;

                    $allTotal_pa += $profitLoss;
                    $allTotalBrokerage += $brokerage;
                    $netAmount += $profitLoss - $brokerage;
                    $buyDate = \Carbon\Carbon::parse($trade->created_at)->format('d-m-Y h:i:s A');
                    $sellDate = \Carbon\Carbon::parse($trade->updated_at)->format('d-m-Y h:i:s A');
                @endphp

                <tr class="compact-row">
                    <td>{{ $trade->trade_id }}</td>
                    <td>{{ $trade->Symbol }}</td>
                    <td>{{ number_format($buyRate, 2) }}</td>
                    <td>{{ number_format($sellRate, 2) }}</td>
                    <td>{{ $lots }} / {{ $lotSize }}</td>
                    <td>{{ number_format($buyTurnover, 2) }}</td>
                    <td>{{ number_format($sellTurnover, 2) }}</td>
                    <td style="color: {{ $profitLoss >= 0 ? 'green' : 'red' }};">
                        {{ number_format($profitLoss, 2) }}
                    </td>
                    <td>{{ number_format($brokerage, 2) }}</td>
                    <td>{{ $buyDate }}</td>
                    <td>{{ $sellDate }}</td>
                </tr>
            @empty
                <tr class="compact-row">
                    <td colspan="11" class="text-center">No records found</td>
                </tr>
            @endforelse

            @if ($hasRecords)
                <tr class="pending-summary summary-row">
                    <td colspan="6">
                        <span>Total Brokerage: {{ number_format($total_brokerage, 2) }}</span>
                        <span>Buy Turnover: {{ number_format($totalBuyTornover, 2) }}</span>
                        <span>Sell Turnover: {{ number_format($totalSellTornover, 2) }}</span>
                    </td>
                    <td colspan="5">
                        Total Profit/Loss:
                        <span style="color: {{ $totalProfitloss >= 0 ? 'green' : 'red' }}; font-size: 9px;">
                            {{ number_format($totalProfitloss, 2) }}
                        </span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Total profit Loss -->

    <table class="pending-table">

        <tbody>
            <tr>
                <td>Total Profit/Loss</td>

                <td>{{ $allTotal_pa }}</td>
            </tr>
            <tr>
                <td>Total Brockerage:</td>
                <td>{{ $allTotalBrokerage }}</td>
            </tr>
            <tr>
                <td>Net Amount:</td>
                <td>{{ $netAmount }}</td>
            </tr>

        </tbody>
    </table>
</body>

</html>
