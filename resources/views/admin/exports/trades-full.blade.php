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
                <td>{{ $trade->IpAddress }}</td> {{-- Replace if you store Sell IP separately --}}
            </tr>
        @endforeach
    </tbody>
</table>
