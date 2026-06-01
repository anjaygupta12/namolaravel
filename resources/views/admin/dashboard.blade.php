@extends('layouts.admin')

@section('content')
    <style>
        .card-category {
            color: rgb(250, 250, 250) !important;
          font-weight: bold;
        }

        .card-title {
            color: rgb(255, 255, 255) !important;
            font-weight: bold;
        }
        .container-fluid {
            background-color: black!important; 
        }
        .text-gray-800{
            color:white!important;
        }
    </style>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    @php
        $any = request()->segment(3);
        $id = request()->segment(4);

        if ($any == 'broker') {
            $secPara = 'user';
        } elseif ($any == 'user') {
            $secPara = 'trades';
        } else {
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
                                @if ($any == 'user')
                                    <h3 class="card-title">{{ $totalTrade->first()->admin ?? '' }}'s Active Positions </h3>
                                @else
                                    <h3 class="card-title">Live M2M under: {{ $totalTrade->first()->admin ?? 'Admin' }}
                                    </h3>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    @if ($any == 'user')
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
                                                    <th>Margin Used</th>
                                                    <th>CMP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalLotsBuy = 0;
                                                    $totalLotsSell = 0;
                                                    $total = 0;
                                                    $netTotal =0;
                                                    $totalMargin  =0;
                                                @endphp
                                                @foreach ($positions as $val)
                                                 @php
                                                             $lotSize = round($val->total * $val->LotSize, 2);
                                                            $lotSizeNew = $val->is_qty==1 ? $val->quentity : $lotSize;
                                                            $totalLotsBuy+= $val->Mode == 'BUY' ? $val->total:0;
                                                            $totalLotsSell+= $val->Mode == 'SELL' ? $val->total:0;
                                                            $total+= $val->total;
                                                            $netTotal+= $val->total;
                                                            $totalMargin+=$val->total_margin; 
                                                        @endphp
                                                    <tr id="row-{{ $val->Pk_id }}">
                                                        <td>{{ explode(':', $val->Symbol)[1] ?? $val->Symbol }}</td>
                                                        <td>{{ $val->Mode == 'BUY' ? $val->total . ' (' .$lotSizeNew . ')' : '0(0)' }}
                                                        </td>
                                                        <td>{{ $val->Mode == 'SELL' ? $val->total . ' (' . $lotSizeNew . ')' : '0(0)' }}
                                                        </td>
                                                        <td id="buy_rate">
                                                            {{ $val->Mode == 'BUY' ? round($val->avgBuy, 2) : '0' }}</td>
                                                        <td id="sell_rate">
                                                            {{ $val->Mode == 'SELL' ? round($val->avgBuy, 2) : '0' }}</td>
                                                        <td id="total_lots">{{ $val->total }}</td>
                                                        <td id="net_lots">{{ $val->Mode == 'SELL' ? '-' . $val->total : $val->total }}</td>
                                                        <td id="_m2mu{{ $val->Symbol }}">0</td>
                                                        <td>{{ $val->total_margin }}</td>
                                                        <td id="cmp_s_{{ $val->Pk_id }}">0</td>
                                                    </tr>
                                                @endforeach
                                                 <tr>
                                                    <th>Total</th>
                                                    <th>{{ $totalLotsBuy }}</th>
                                                    <th>{{ $totalLotsSell }}</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>{{ $total }}</th>
                                                    <th>{{  $totalLotsBuy-$totalLotsSell }}</th> 
                                                    <th  id="_m2muTotal" ></th>
                                                    <th>{{ $totalMargin }}</th>
                                                    <th></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a class="btn btn-success" href="{{ route('admin.users-view', $id) }}">Go to
                                            {{ $totalTrade->first()->admin ?? '' }}'s Account</a>
                                    @endif
                                    @if ($any == 'broker')
                                        <table class="table" id="m2m_table">
                                            <thead>
                                                <tr>
                                                    <th>User ID</th>
                                                    <th>Ledger Balance</th>
                                                    <th>M2M</th>
                                                    <th>Active Profit/Loss</th>
                                                    <th>Active Trades</th>
                                                    <th>Margin Used</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($totalTrade as $item)
                                             
                                                    <tr id="row-{{ $item->id }}">
                                                        <td>
                                                            <a class="badge badge-pill badge-success"
                                                                href="{{ $secPara != 'trades' ? route('admin.brokerm2m', ['any' => $secPara, 'id' => $item->id]) : '#' }}">
                                                                {{ $secPara != 'trades' ? $item->user_id . ' :' : '' }}
                                                                {{ $item->username }}
                                                            </a>
                                                        </td>
                                                        <td id="bal_u_{{ $item->id }}">{{ $item->balance }}</td>
                                                        <td id="m2ma_{{ $item->id }}"></td>
                                                        <td id="pl_{{ $item->id }}" class="broker-pl">0.00</td>
                                                        <td>{{ $item->total_trades }}</td>
                                                        <td>{{ $item->total_margin }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    @if ($any == '')
                                        <table class="table" id="m2m_table">
                                            <thead>
                                                <tr>
                                                    <th>User ID</th>
                                                    <th>Active Profit/Loss</th>
                                                    <th>Active Trades</th>
                                                    <th>Margin Used</th>
                                                    <th>Holding Margin Used</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Total</th>
                                                    <th id="total-pl">0.00</th>
                                                    <th id="net_count">{{ $totalTrade->sum('total_trades') }}</th>
                                                    <th id="net_margin">{{ $totalTrade->sum('total_margin') }}</th>
                                                     <th id="net_margin">{{ $totalTrade->sum('holding_margin') }}</th>
                                                </tr>

                                                @foreach ($totalTrade as $item)
                                                    <tr id="row-{{ $item->id }}">
                                                        <td>
                                                            <a class="badge badge-pill badge-success"
                                                                href="{{ $secPara != 'trades' ? route('admin.brokerm2m', ['any' => $secPara, 'id' => $item->id]) : '#' }}">
                                                                {{ $secPara != 'trades' ? $item->user_id . ' :' : '' }}
                                                                {{ $item->username }}
                                                            </a>
                                                        </td>
                                                        <td id="pl_{{ $item->id }}" class="broker-pl">0.00</td>
                                                        <td>{{ $item->total_trades }}</td>
                                                        <td>{{ $item->total_margin }}</td>
                                                        <td>{{ $item->holding_margin }}</td>
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
             @if ($any != 'user')
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
                            <h3 class="card-title">{{ $turnovers['nseSellTurnOver'] }} <small>Lakhs</small>
                            </h3>
                            <hr>
                            <span class="card-category">Options</span>
                            <h3 class="card-title">{{ $turnovers['optionsSellTurnOver'] }} <small>Lakhs</small>
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
                            <span class="card-category">Total Users</span>
                            <h3 class="card-title">{{ $users }}</h3>
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
                            <h3 class="card-title" id="MCX">{{ $ProfitLoss['mcxProfitLoss'] }}</h3>
                            <hr>
                            <span class="card-category">Equity</span>
                            <h3 class="card-title" id="NSE" >{{ $ProfitLoss['nseProfitLoss'] }}</h3>
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
            @endif
        </div>
    </div>

    <script>
        function updateLivePL() {
            const id = "{{ Request::segment(4) ?? 1 }}";
            const segment = "{{ Request::segment(3) ?? 'main-admin' }}";
            console.log(segment);
            const url = `/admin/dashboard-live-pl/${segment}/${id}`;
            console.log('testing',url);
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
                    let mcxProfitloss = 0;
                    let nseProfitloss = 0;
                    var TotalM2m  =0;
                    const symbolTotals = {};
                    data.forEach(trade => {
                        // Each trade row (if exists)
                    //  mcxProfitloss += trade.transModeWise.MCX;
                    //  nseProfitloss += trade.transModeWise.NSE ??0;
                    if(segment=='user'){
                    if (!symbolTotals[trade.Symbol]) {
                        symbolTotals[trade.Symbol] = 0;
                    }

                    // Add PL
                   
                    symbolTotals[trade.Symbol] += trade.pl;
                    }
                     
                        const tradeRow = document.getElementById(`row-${trade.Pk_id}`);
                        const plCell = document.getElementById(`pl_${trade.Pk_id}`);
                        if (tradeRow) {
                            const tradeLastCell = tradeRow.querySelector('.trade-last');
                            const plCell = document.getElementById(`pl_${trade.Pk_id}`);
                            const m2ma = document.getElementById(`m2ma_${trade.Pk_id}`);
                            const balanceU = document.getElementById(`bal_u_${trade.Pk_id}`);
                            const cmp_s = document.getElementById(`cmp_s_${trade.Pk_id}`);
                            const value = parseFloat(trade.TradeLast || 0);
                           const m2mau = document.getElementById(`_m2mu${trade.Pk_id}`);
                          
                              
                             totalPL += trade.pl;
                            if (m2mau) { 
                                let balance = parseFloat(trade.balance) || 0;
                                m2mau.textContent = (trade.pl).toFixed(2);
                               
                            }
 
                            if (cmp_s) {
                                cmp_s.textContent = value.toFixed(2);
                            } else {
                                console.log("cmp_s element not found");
                            }

                            // Update trade last price
                            if (tradeLastCell && trade.TradeLast !== undefined) {
                                tradeLastCell.textContent = parseFloat(trade.TradeLast).toFixed(2);
                            }

                            // Update P&L cell
                    if (plCell && trade.pl !== undefined) {

                        const plVal = parseFloat(trade.pl) || 0;
                        plCell.textContent = plVal.toFixed(2);

                        if (balanceU && m2ma) {

                            let balance = parseFloat(balanceU.textContent) || 0;
                            let pl = plVal;

                            m2ma.textContent = (balance + pl).toFixed(2);
                        }

                        // Color change
                        if (plVal > 0) {
                            plCell.style.color = 'white';
                        } else if (plVal < 0) {
                            plCell.style.color = 'white';
                        } else {
                            plCell.style.color = 'white';
                        }
                    }
                        }
                        // 🔹 Group total by BrokerId
                      

                    });
                    
                          //    const oldmcxProfit = parseInt(document.getElementById("MCX").textContent) || 0;
        //                 const oldNseProfitLoss = parseInt(document.getElementById("NSE").textContent) || 0;

        //                 document.getElementById("MCX").textContent = parseFloat(mcxProfitloss + oldmcxProfit).toFixed(2);
        //                 document.getElementById("NSE").textContent = parseFloat(nseProfitloss + oldNseProfitLoss).toFixed(2);
                   
        //             Object.entries(brokerPLMap).forEach(([brokerId, plVal]) => {
        //                 const el = document.getElementById(`pl_${brokerId}`);
        //                 if (el) {
        //                     el.textContent = plVal.toFixed(2);
        //                 }
        //             });
                        Object.keys(symbolTotals).forEach(symbol => {
                        const td = document.getElementById(`_m2mu${symbol}`);
                        if (td) {
                         TotalM2m += symbolTotals[symbol]; 
                            td.textContent = symbolTotals[symbol].toFixed(2);
                        }
                    });

                    //console.log('toltal pl sybmols iwse',symbolTotals);
                    // 🔹 Update total PL
                    console.log('TotalM2m',TotalM2m);
                       const m2muTotal = document.getElementById(`_m2muTotal`);
                            if(m2muTotal){
                                m2muTotal.textContent = TotalM2m.toFixed(2);
                            }
                    const totalPlEl = document.getElementById('total-pl');
                    if (totalPlEl) {
                        totalPlEl.textContent = totalPL.toFixed(2);
                        // totalPlEl.style.color = totalPL > 0 ? 'green' : (totalPL < 0 ? 'red' : 'black');
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
