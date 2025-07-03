<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trade Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Trade Report ({{ $from }} to {{ $to }})</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Scrip</th>
                <th>Buy Rate</th>
                <th>Sell Rate</th>
                <th>Lots</th>
                <th>Buy Turnover</th>
                <th>Sell Turnover</th>
                <th>CMP</th>
                <th>Profit/Loss</th>
                <th>Margin Used</th>
                <th>Bought At</th>
                <th>Sold At</th>
                <th>Buy IP</th>
                <th>Sell IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trades as $trade)
                @php
                    $buyTurnover = $trade->BuyPrice * $trade->Lots;
                    $sellTurnover = $trade->SalePrice * $trade->Lots;
                    $pl = ($trade->TradeLast - $trade->BuyPrice) * $trade->Lots;
                    $margin = $trade->BuyPrice * $trade->Lots * 0.2;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trade->Pk_id }}</td>
                    <td>{{ $trade->Symbol }}</td>
                    <td>{{ $trade->BuyPrice }}</td>
                    <td>{{ $trade->SalePrice }}</td>
                    <td>{{ $trade->Lots }}</td>
                    <td>{{ number_format($buyTurnover, 2) }}</td>
                    <td>{{ number_format($sellTurnover, 2) }}</td>
                    <td>{{ $trade->TradeLast }}</td>
                    <td>{{ number_format($pl, 2) }}</td>
                    <td>{{ number_format($margin, 2) }}</td>
                    <td>{{ $trade->created_at }}</td>
                    <td>{{ $trade->updated_at }}</td>
                    <td>{{ $trade->IpAddress }}</td>
                    <td>{{ $trade->IpAddress }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
