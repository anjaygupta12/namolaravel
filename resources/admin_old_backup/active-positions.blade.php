@extends('layouts.admin')

@section('title', 'Active Positions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Active Positions</h4>
                        <p class="card-category">Hs01's Active Positions</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Scrip</th>
                                        <th>Active Buy</th>
                                        <th>Active Sell</th>
                                        <th>Avg buy rate</th>
                                        <th>Avg sell rate</th>
                                        <th>Total</th>
                                        <th>Net</th>
                                        <th>M2m</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($positions as $val)
                                        <tr id="row-{{ $val->Pk_id }}">
                                            <td><a class="badge badge-pill badge-success"
                                                    href="{{ route('admin.active-users', $val->Pk_id) }}">{{ $val->Symbol }}</a>
                                            </td>
                                            <td>{{ $val->BuyCount }}({{ $val->BuyCount != 0 ? $val->Lots : 0 }})</td>
                                            <td>{{ $val->SellCount }}({{ $val->SellCount = !0 ? $val->Lots : 0 }})</td>
                                            <td>{{ number_format($val->BuyPrice, 2) }}</td>
                                            <td>{{ number_format($val->SellPrice, 2) }}</td>
                                            <td>{{ $val->BuyCount + $val->SellCount }}</td>
                                            <td>{{ $val->BuyCount + $val->SellCount }}</td>
                                            <td id="pl_{{ $val->Pk_id }}" class="broker-pl">0.00</td>
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
        function updateLivePL() {

            const url = '/admin/dashboard-live-pl/admin/1';
          
            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                   
                    return res.json();
                })
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        console.warn("No live price data received");
                        return;
                    }

                    const brokerPLMap = {};
                    let totalPL = 0;
                   
                    data.forEach(trade => {
                        // Each trade row (if exists)
                        const tradeRow = document.getElementById(`row-${trade.bid_id}`);
                     
                        if (tradeRow) {
                            const tradeLastCell = tradeRow.querySelector('.trade-last');
                            const plCell = document.getElementById(`pl_${trade.bid_id}`);
                    
                            // Update trade last price
                            if (tradeLastCell && trade.TradeLast !== undefined) {
                                tradeLastCell.textContent = parseFloat(trade.TradeLast).toFixed(2);
                            }

                            // Update P&L cell
                            if (plCell && trade.pl !== undefined) {
                                const plVal = parseFloat(trade.pl);
                                plCell.textContent = plVal.toFixed(2);
                                plCell.style.color = plVal > 0 ? 'green' : (plVal < 0 ? 'red' : 'black');
                            }
                        }
                        // 🔹 Group total by BrokerId
                        totalPL += trade.pl;

                    });

                    // 🔹 Update each broker’s PL
                    Object.entries(brokerPLMap).forEach(([brokerId, plVal]) => {
                        const el = document.getElementById(`pl_${brokerId}`);
                        if (el) {
                            el.textContent = plVal.toFixed(2);
                            el.style.color = plVal > 0 ? 'green' : (plVal < 0 ? 'red' : 'black');
                        }
                    });

                    // 🔹 Update total PL
                    const totalPlEl = document.getElementById('total-pl');
                    if (totalPlEl) {
                        totalPlEl.textContent = totalPL.toFixed(2);
                        totalPlEl.style.color = totalPL > 0 ? 'green' : (totalPL < 0 ? 'red' : 'black');
                    }
                })
                .catch(err => console.error('❌ Live price fetch error:', err));
        }

        // updateLivePL();
        document.addEventListener("DOMContentLoaded", function() {
            updateLivePL(); // Call only once when page loads
        });
    </script>
@endsection
