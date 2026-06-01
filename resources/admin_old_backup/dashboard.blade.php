@extends('layouts.admin')

@section('content')
<style>
    .card-category {
        color: black !important;
    }

    .card-title {
        color: black !important;
    }
</style>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>
@php
$any = request()->segment(3);
$id = request()->segment(4);

if($any=='broker'){
$secPara = 'user';
}elseif($any=='user'){
$secPara = 'trades';
}else{
$secPara = 'broker';
}
@endphp

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="Dashboard-mtm" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                    <div class="card">
                        <div class="card-header ">
                            @if ($any=='user')
                            <h3 class="card-title">{{ $totalTrade->first()->admin ?? ''  }}'s Active Positions </h3>
                            @else
                            <h3 class="card-title">Live M2M under: {{ $totalTrade->first()->admin ?? 'Admin'  }} </h3>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                @if ($any=='user')

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
                                        <tr>
                                            <td>{{ $val->Symbol }}</td>
                                            <td>{{ $val->users }} ({{ $val->BuyPrice }})</td>
                                            <td>{{ $val->users }} ({{ $val->SalePrice ?? 0.0 }})</td>
                                            <td id="buy_rate">{{ round($val->avgBuy, 2) }}</td>
                                            <td id="sell_rate">{{ round($val->avgSell, 2) }}</td>
                                            <td id="total_lots">{{ $val->total }}</td>
                                            <td id="net_lots">{{ $val->total }}</td>
                                            <td id="_m2m"></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <a class="btn btn-success" href="{{ route('admin.users-view',$id) }}">Go to {{ $totalTrade->first()->admin ?? ''  }}'s Account</a>
                                @else
                                <table class="table" id="m2m_table">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Active Profit/Loss</th>
                                            <th>Active Trades</th>
                                            <th>Margin Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Total</th>
                                            <th id="total-pl">0.00</th>
                                            <th id="net_count">{{ $totalTrade->sum('total_trades') }}</th>
                                            <th id="net_margin">{{ $totalTrade->sum('total_margin') }}</th>
                                        </tr>

                                        @foreach ($totalTrade as $item)
                                        <tr id="row-{{ $item->id }}">
                                            <td>
                                                <a class="badge badge-pill badge-success"
                                                    href="{{ $secPara != 'trades' ? route('admin.brokerm2m', ['any' => $secPara, 'id' => $item->id]) :'#' }}">
                                                    {{ $secPara != 'trades' ? $item->user_id .' :' : '' }} {{ $item->username }}
                                                </a>
                                            </td>
                                            <td id="pl_{{ $item->id }}" class="broker-pl">0.00</td>
                                            <td>{{ $item->total_trades }}</td>
                                            <td>{{ $item->total_margin }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Buy Turnover</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $turnovers['mcxBuyTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $turnovers['nseBuyTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $turnovers['optionsBuyTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">COMEX</span>
                        <h3 class="card-title">{{ $turnovers['comexBuyTurnOver'] }} <small>Lakhs</small>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Sell Turnover</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $turnovers['mcxSellTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $turnovers['nseSellTurnOver'] }}<small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $turnovers['optionsSellTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">COMEX</span>
                        <h3 class="card-title">{{ $turnovers['mcxAllTurnOver'] }} <small>Lakhs</small>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Total Turnover</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $turnovers['mcxAllTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $turnovers['nseAllTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $turnovers['optionsAllTurnOver'] }} <small>Lakhs</small>
                        </h3>
                        <hr>
                        <span class="card-category">COMEX</span>
                        <h3 class="card-title">{{ $turnovers['comexAllTurnOver'] }} <small>Lakhs</small>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Active Users</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $activeUsers['mcxActiveUser'] }}</h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $activeUsers['nseActiveUser'] }}</h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $activeUsers['optionsActiveUser'] }}</h3>
                        <hr>
                        <span class="card-category">{{ $activeUsers['comexActiveUser'] }}</span>
                        <h3 class="card-title">17</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Profit / Loss</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $ProfitLoss['mcxProfitLoss'] }}</h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $ProfitLoss['nseProfitLoss'] }}</h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $ProfitLoss['optionsProfitLoss'] }}</h3>
                        <hr>
                        <span class="card-category">COMEX</span>
                        <h3 class="card-title">{{ $ProfitLoss['comexProfitLoss'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Brokerage</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $brokerage['mcxbrokerage'] }}</h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $brokerage['nsebrokerage'] }}</h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $brokerage['optionsbrokerage'] }}</h3>
                        <hr>
                        <span class="card-category">COMEX</span>
                        <h3 class="card-title">{{ $brokerage['comexbrokerage'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Active Buy</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $activeBuyUsers['mcxactiveBuyUsers'] }}</h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $activeBuyUsers['nseactiveBuyUsers'] }}</h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $activeBuyUsers['optionsactiveBuyUsers'] }}</h3>
                        <hr>
                        <span class="card-category">COMEX</span>
                        <h3 class="card-title">{{ $activeBuyUsers['comexactiveBuyUsers'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header ">
                        <h3 class="card-title">Active Sell</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="card-category">Mcx</span>
                        <h3 class="card-title">{{ $activeSellUsers['mcxactiveSellUsers'] }}</h3>
                        <hr>
                        <span class="card-category">Equity</span>
                        <h3 class="card-title">{{ $activeSellUsers['nseactiveSellUsers'] }}</h3>
                        <hr>
                        <span class="card-category">Options</span>
                        <h3 class="card-title">{{ $activeSellUsers['optionsactiveSellUsers'] }}</h3>
                        <hr>
                        <span class="card-category">COMEX</span>
                        <h3 class="card-title">{{ $activeSellUsers['comexactiveSellUsers'] }} </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        </div>

    </div>
</div>

<script>
    function updateLivePL() {
        const id = "{{ Request::segment(4) ?? 1 }}";
        const segment = "{{ Request::segment(3) ?? 'main-admin' }}";
        const url = `/admin/dashboard-live-pl/${segment}/${id}`;

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
                    console.log(trade);
                    const tradeRow = document.getElementById(`row-${trade.Pk_id}`);
                    if (tradeRow) {
                        const tradeLastCell = tradeRow.querySelector('.trade-last');
                        const plCell = document.getElementById(`pl_${trade.Pk_id}`);

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
    // setInterval(updateLivePL, 2000);
    document.addEventListener("DOMContentLoaded", function() {
        updateLivePL();
    });
</script>




@endsection