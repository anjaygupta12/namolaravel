@extends('layouts.admin')

@section('title', 'MCX User Views - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">User Trading Details</h4>
                    <p class="card-category">{{ $user->name }} ({{ $user->email }})</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Users
                            </a>
                            <a href="{{ route('admin.users-edit', $user->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit User
                            </a>
                            <a href="{{ route('admin.wf-status', $user->id) }}" class="btn btn-info">
                                <i class="fas fa-wallet"></i> Wallet/Funds Status
                            </a>
                            <a href="{{ route('admin.comex-margins', $user->id) }}" class="btn btn-primary">
                                <i class="fas fa-chart-line"></i> Comex Margins
                            </a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name:</label>
                                <p class="form-control-static">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email:</label>
                                <p class="form-control-static">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile:</label>
                                <p class="form-control-static">{{ $user->mobile ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status:</label>
                                <p class="form-control-static">
                                    @if($user->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h3>Active Trades</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Scrip</th>
                                    <th>Trade</th>
                                    <th>Rate</th>
                                    <th>Lots / Units</th>
                                    <th>Turnover</th>
                                    <th>Current Rate</th>
                                    <th>Profit / Loss</th>
                                    <th>Brokerage</th>
                                    <th>Date</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($activeTrades->count() > 0)
                                    @foreach($activeTrades as $trade)
                                    <tr>
                                        <td>{{ $trade->id }}</td>
                                        <td>{{ $trade->commodity }}</td>
                                        <td>{{ $trade->trade_type }}</td>
                                        <td>{{ $trade->rate }}</td>
                                        <td>{{ $trade->lots }}</td>
                                        <td>{{ $trade->turnover }}</td>
                                        <td>{{ $trade->current_rate ?? 'N/A' }}</td>
                                        <td>
                                            @if(isset($trade->pl))
                                                <span class="{{ $trade->pl >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $trade->pl }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $trade->brokerage ?? '0' }}</td>
                                        <td>{{ $trade->created_at }}</td>
                                        <td>{{ $trade->ip_address }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="11">No records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h3>Closed Trades</h3>
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
                                    <th>Buy IP</th>
                                    <th>Sell IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($closedTrades->count() > 0)
                                    @foreach($closedTrades as $trade)
                                    <tr>
                                        <td>{{ $trade->id }}</td>
                                        <td>{{ $trade->commodity }}</td>
                                        <td>{{ $trade->buy_price }}</td>
                                        <td>{{ $trade->sell_price }}</td>
                                        <td>{{ $trade->lots }}</td>
                                        <td>{{ $trade->buy_turnover }}</td>
                                        <td>{{ $trade->sell_turnover }}</td>
                                        <td>
                                            <span class="{{ $trade->pl >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $trade->pl }}
                                            </span>
                                        </td>
                                        <td>{{ $trade->brokerage }}</td>
                                        <td>{{ $trade->bought_at }}</td>
                                        <td>{{ $trade->sold_at }}</td>
                                        <td>{{ $trade->buy_ip }}</td>
                                        <td>{{ $trade->sell_ip }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="13">No records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h3>MCX Pending Orders</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Trade</th>
                                    <th>Lots</th>
                                    <th>Commodity</th>
                                    <th>Condition</th>
                                    <th>Rate</th>
                                    <th>Date</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($mcxPendingOrders->count() > 0)
                                    @foreach($mcxPendingOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->trade_type }}</td>
                                        <td>{{ $order->lots }}</td>
                                        <td>{{ $order->commodity }}</td>
                                        <td>{{ $order->order_condition }}</td>
                                        <td>{{ $order->rate }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ $order->ip_address }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">No records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h3>Equity Pending Orders</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Trade</th>
                                    <th>Lots</th>
                                    <th>Commodity</th>
                                    <th>Condition</th>
                                    <th>Rate</th>
                                    <th>Date</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($equityPendingOrders->count() > 0)
                                    @foreach($equityPendingOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->trade_type }}</td>
                                        <td>{{ $order->lots }}</td>
                                        <td>{{ $order->commodity }}</td>
                                        <td>{{ $order->order_condition }}</td>
                                        <td>{{ $order->rate }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ $order->ip_address }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">No records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h3>COMEX Pending Orders</h3>
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
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($comexPendingOrders->count() > 0)
                                    @foreach($comexPendingOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->trade_type }}</td>
                                        <td>{{ $order->lots }}</td>
                                        <td>{{ $order->commodity }}</td>
                                        <td>{{ $order->order_condition }}</td>
                                        <td>{{ $order->rate }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ $order->ip_address }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">No records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
