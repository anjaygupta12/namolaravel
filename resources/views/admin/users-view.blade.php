@extends('layouts.admin')

@section('title', 'View User')

@section('content')
    <style>
        .card .card-body {
            color: #000000;
            position: relative;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Export Trades (Excel) -->
                        <form method="POST" action="{{ route('admin.trades.export') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="date" id="from_date" name="from_date" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="to_date" name="to_date" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="export_type" value="excel"
                                        class="btn btn-info w-100">Export Trades</button>
                                </div>
                            </div>
                        </form>

                        <!-- Download Trades PDF -->
                        <form method="POST" action="{{ route('admin.trades.pdf') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="date" id="from_date1" name="from_date" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="to_date1" name="to_date" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="export_type" value="pdf"
                                        class="btn btn-info w-100">Download Trades PDF</button>
                                </div>
                            </div>
                        </form>

                        <!-- Export Funds -->
                        <form method="GET" action="{{ route('admin.funds-report') }}">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" value="" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" value="" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <button type="submit" name="export" class="btn btn-info w-100">Download Funds
                                        Report</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div id="ContentPlaceHolder1_dvdropdown" class="btn-group">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" onclick="action_fun();">Actions</button>
                        <button type="button" class="btn btn-primary dropdown-toggle" onclick="action_fun();">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" id="action_menu">
                            <li><a class="dropdown-item" href="{{ route('admin.users-edit', $user->id) }}">Update</a></li>
                            <li role="separator" class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.users-reset', $user->id) }}"
                                    onclick="return confirm('Are you sure you want to reset this account? All history will be cleared...')">
                                    Reset Account
                                </a>
                            </li>
                            <li role="separator" class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.recalculate-brokerage', $user->id) }}"
                                    onclick="return confirm('Brokerage for all existing (active & inactive) trades will be recalculated. Would you like to proceed?')">
                                    Refresh Brokerage
                                </a>
                            </li>

                            <li role="separator" class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.users-copy', $user->id) }}"
                                    onclick="return confirm('A new account with similar details will be created. Are you sure you want to proceed?')">
                                    Duplicate
                                </a>
                            </li>

                            <li role="separator" class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.change-password', $user->id) }}">
                                    Change Password
                                </a>
                            </li>

                            <li role="separator" class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.users-delete', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this account?')"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"
                                        style="border: none; background: none; padding: 0;">
                                        Delete Account
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </div>
                </div>

                <script type="text/javascript">
                    function action_fun() {
                        document.getElementById("action_menu").classList.toggle("actionShow");
                    }
                </script>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-success btn-lg btn-block" type="button"
                                            onclick="view_detailsShow();">
                                            View Details
                                        </button>
                                    </h2>
                                </div>
                                <script type="text/javascript">
                                    function view_detailsShow() {
                                        document.getElementById("show_collpse").classList.toggle("show_colla");
                                    }
                                </script>
                            </div>
                            <div class="col-md-12">
                                <div class="accordion" id="accordionExample">
                                    <div class="collapse" id="show_collpse">
                                        <table id="w2" class="table table-striped table-bordered detail-view">
                                            <tbody>
                                                <tr>
                                                    <th>ID</th>
                                                    <td>{{ $user->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Name</th>
                                                    <td>{{ $user->FullName }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mobile</th>
                                                    <td>{{ $user->Mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Username</th>
                                                    <td>{{ $user->Username }}</td>
                                                </tr>
                                                <tr>
                                                    <th>City</th>
                                                    <td>{{ $user->City }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Account Status</th>
                                                    <td>{{ $user->IsActive == 1 ? 'Active' : 'InActive' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Allow Orders between High - Low?</th>
                                                    <td>{{ $user->AllowOrdersBetweenHighLow ? 'Yes' : 'NO' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Allow Fresh Entry Order above high &amp; below low?</th>
                                                    <td>{{ $user->AllowOrdersBetweenHighLow ? 'Yes' : 'NO' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>demo account?</th>
                                                    <td>{{ $user->IsDemo ? 'Yes' : 'NO' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Auto-close trades if losses cross beyond the configured limit</th>
                                                    <td>Yes</td>
                                                </tr>
                                                <tr>
                                                    <th>Auto-close trades if insufficient fund to hold overnight</th>
                                                    <td>Yes</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of MCX</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of MCX</th>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity</th>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity INDEX</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity INDEX</th>
                                                    <td>20</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of MCX to be actively open at a
                                                        time</th>
                                                    <td>15</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity to be actively open at
                                                        a time</th>
                                                    <td>20</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity INDEX to be actively
                                                        open at a time</th>
                                                    <td>100</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity Options</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity Options</th>
                                                    <td>25</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity INDEX Options
                                                    </th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity INDEX Options
                                                    </th>
                                                    <td>25</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity to be actively open at
                                                        a time</th>
                                                    <td>50</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity INDEX Options to be
                                                        actively open at a time</th>
                                                    <td>50</td>
                                                </tr>
                                                <tr>
                                                    <th>auto-Close all active trades when the losses reach % of
                                                        Ledger-balance</th>
                                                    <td>90</td>
                                                </tr>
                                                <tr>
                                                    <th>Notify client when the losses reach % of Ledger-balance</th>
                                                    <td>70</td>
                                                </tr>
                                                <tr>
                                                    <th>MCX Trading</th>
                                                    <td>Active</td>
                                                </tr>
                                                <tr>
                                                    <th>MCX brokerage per_crore</th>
                                                    <td>1000.0000</td>
                                                </tr>
                                                <tr>
                                                    <th>Equity Trading</th>
                                                    <td>Active</td>
                                                </tr>
                                                <tr>
                                                    <th>Equity brokerage</th>
                                                    <td>1000.0000</td>
                                                </tr>
                                                <tr>
                                                    <th>Intraday Exposure/Margin Equity</th>
                                                    <td>500</td>
                                                </tr>
                                                <tr>
                                                    <th>Holding Exposure/Margin Equity</th>
                                                    <td>70</td>
                                                </tr>
                                                <tr>
                                                    <th>Options Trading</th>
                                                    <td>Active</td>
                                                </tr>
                                                <tr>
                                                    <th>Options brokerage</th>
                                                    <td>25.0000</td>
                                                </tr>
                                                <tr>
                                                    <th>Intraday Exposure/Margin Options</th>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <th>Holding Exposure/Margin Options</th>
                                                    <td>2</td>
                                                </tr>
                                                <tr>
                                                    <th>Ledger Balance</th>
                                                    <td>0.0000</td>
                                                </tr>
                                                <tr>
                                                    <th>Broker</th>
                                                    <td>0 : </td>
                                                </tr>
                                                <tr>
                                                    <th>Account Created At</th>
                                                    <td>2024-12-07 14:01:06</td>
                                                </tr>
                                                <tr>
                                                    <th>Notes</th>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Profit / Loss</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Brokerage</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Net Profit / Loss</th>
                                                    <td>0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <h3>Fund - Withdrawal &amp; Deposits</h3>
                                    <div id="w3" class="grid-view">
                                        <div class="summary">Showing <b>1</b> of <b>1</b> items.</div>
                                        <table class="table ">
                                            <thead>
                                                <tr>
                                                    <th>Amount</th>
                                                    <th>Funds</th>
                                                    <th>Created At</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($funds as $val)
                                                    <tr>
                                                        <td>{{ $val->Amount }}</td>
                                                        <td>{!! $val->type == 1
                                                            ? '<span class="badge badge-danger">Deposit</span>'
                                                            : '<span class="badge badge-success">Withdraw</span>' !!}</td>

                                                        <td>{{ \Carbon\Carbon::parse($val->LastModify)->format('d-M-Y h:i:s A') }}
                                                        </td>
                                                        <td>{{ $val->notes }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="pagination-wrapper">
                                            {{ $funds->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <h3>Active Trades</h3>
                                    <div id="pjax-grid-index" data-pjax-container="" data-pjax-push-state=""
                                        data-pjax-timeout="1000">
                                        <div id="w4" class="grid-view">

                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>X</th>
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
                                                        <th>Bought at</th>
                                                        <th>Sold at</a></th>
                                                        <th>Buy Ip</a></th>
                                                        <th>Sell Ip</a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($trades as $trade)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $trade->Pk_id }}</td>
                                                            <td>{{ $trade->Symbol }}</td>
                                                            <td>{{ $trade->BuyPrice }}</td>
                                                            <td>{{ ($trade->Isactive != 1 ) ? $trade->SalePrice : '' }}</td>
                                                            <td>{{ $trade->Lots }}</td>
                                                            <td>{{ number_format($trade->BuyPrice * $trade->Lots, 2) }}
                                                            </td>
                                                            <td>{{ ($trade->Isactive != 1 ) ? number_format($trade->SalePrice * $trade->Lots, 2) :'' }}
                                                            </td>
                                                            <td>{{ $trade->TradeLast }}</td>
                                                            <td>
                                                                @php
                                                                    $pl =
                                                                        ($trade->TradeLast - $trade->BuyPrice) *
                                                                        $trade->Lots;
                                                                @endphp
                                                                {{ number_format($pl, 2) }}
                                                            </td>
                                                            <td>{{ ($trade->Isactive != 1 ) ? number_format($trade->BuyPrice * $trade->Lots * 0.2, 2): '' }}
                                                            </td> <!-- example: 20% margin -->
                                                            <td>{{ $trade->created_at }}</td>
                                                            <td>{{ ($trade->Isactive != 1 ) ? $trade->updated_at: '' }}</td>
                                                            <td>{{ $trade->IpAddress }}</td>
                                                            <td>{{ ($trade->Isactive != 1 ) ? $trade->IpAddress: '' }}</td>
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

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <h3>Closed Trades</h3>
                                    <div id="w5" class="grid-view">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
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
                                                @forelse($closedTrade as $trade)
                                                    @php
                                                        $buyRate = (float) $trade->BuyPrice;
                                                        $sellRate = (float) $trade->SalePrice;
                                                        $lots = (float) $trade->Lots;
                                                        $buyTurnover = $buyRate * $lots;
                                                        $sellTurnover = $sellRate * $lots;
                                                        $profitLoss = $sellTurnover - $buyTurnover;
                                                        $brokerage = ($buyTurnover + $sellTurnover) * 0.0005; // Change % as per your brokerage rule

                                                        $buyDate = \Carbon\Carbon::parse($trade->created_at)->format(
                                                            'Y-m-d',
                                                        );
                                                        $sellDate = \Carbon\Carbon::parse($trade->updated_at)->format(
                                                            'Y-m-d',
                                                        );

                                                        $sameDate = $buyDate === $sellDate ? '✔️' : '❌';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $trade->Pk_id }}</td>
                                                        <td>{{ $trade->Symbol }}</td>
                                                        <td>{{ number_format($buyRate, 2) }}</td>
                                                        <td>{{ number_format($sellRate, 2) }}</td>
                                                        <td>{{ $lots }}</td>
                                                        <td>{{ number_format($buyTurnover, 2) }}</td>
                                                        <td>{{ number_format($sellTurnover, 2) }}</td>
                                                        <td style="color: {{ $profitLoss >= 0 ? 'green' : 'red' }};">
                                                            {{ number_format($profitLoss, 2) }}
                                                        </td>
                                                        <td>{{ number_format($brokerage, 2) }}</td>
                                                        <td>{{ $trade->created_at }}</td>
                                                        <td>{{ $trade->updated_at }} {!! $sameDate !!}</td>
                                                        <td>{{ $trade->IpAddress }}</td>
                                                        <td>{{ $trade->IpAddress }}</td> <!-- Change if Sell IP differs -->
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="13" class="text-center">No records found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <h3>MCX Pending Orders</h3>
                                    <div id="w6" class="grid-view">

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Trade</th>
                                                    <th>Lots</th>
                                                    <th>Scrip</th>
                                                    <th>Condition</th>
                                                    <th>Rate</th>
                                                    <th>Date</th>
                                                    <th>Ip Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($mxcPendingTrade as $trade)
                                                    <tr>
                                                        <td>{{ $trade->Pk_id }}</td>
                                                        <td>{{ $trade->Mode }}</td>
                                                        <td>{{ $trade->Lots }}</td>
                                                        <td>{{ $trade->Symbol }}</td>
                                                        <td>{{ $trade->OPTION ?? '-' }}</td>
                                                        <td>
                                                            @if ($trade->Mode == 'BUY')
                                                                {{ number_format((float) $trade->BuyPrice, 2) }}
                                                            @else
                                                                {{ number_format((float) $trade->SalePrice, 2) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $trade->created_at }}</td>
                                                        <td>{{ $trade->IpAddress }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No records found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <h3>Equity Pending Orders</h3>
                                    <div id="w7" class="grid-view">

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Trade</th>
                                                    <th>Lots</th>
                                                    <th>Scrip</th>
                                                    <th>Condition</th>
                                                    <th>Rate</th>
                                                    <th>Date</th>
                                                    <th>Ip Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($equityPendingTrade as $trade)
                                                    <tr>
                                                        <td>{{ $trade->Pk_id }}</td>
                                                        <td>{{ $trade->Mode }}</td>
                                                        <td>{{ $trade->Lots }}</td>
                                                        <td>{{ $trade->Symbol }}</td>
                                                        <td>{{ $trade->OPTION ?? '-' }}</td>
                                                        <td>
                                                            @if ($trade->Mode == 'BUY')
                                                                {{ number_format((float) $trade->BuyPrice, 2) }}
                                                            @else
                                                                {{ number_format((float) $trade->SalePrice, 2) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $trade->created_at }}</td>
                                                        <td>{{ $trade->IpAddress }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No records found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <h3>COMEX Pending Orders</h3>
                                    <div id="w7" class="grid-view">

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Trade</th>
                                                    <th>Lots</th>
                                                    <th>Scrip</th>
                                                    <th>Condition</th>
                                                    <th>Rate</th>
                                                    <th>Date</th>
                                                    <th>Ip Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($comexPendingTrade as $trade)
                                                    <tr>
                                                        <td>{{ $trade->Pk_id }}</td>
                                                        <td>{{ $trade->Mode }}</td>
                                                        <td>{{ $trade->Lots }}</td>
                                                        <td>{{ $trade->Symbol }}</td>
                                                        <td>{{ $trade->OPTION ?? '-' }}</td>
                                                        <td>
                                                            @if ($trade->Mode == 'BUY')
                                                                {{ number_format((float) $trade->BuyPrice, 2) }}
                                                            @else
                                                                {{ number_format((float) $trade->SalePrice, 2) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $trade->created_at }}</td>
                                                        <td>{{ $trade->IpAddress }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No records found</td>
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
            </div>
        </div>
    </div>
@endsection
