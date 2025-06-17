@extends('layouts.admin')

@section('title', 'Wallet Status')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Wallet Status</h4>
                    <p class="card-category">View wallet and funds status for {{ $user->name }}</p>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Account Summary</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Current Balance</h5>
                                                    <h3 class="text-primary">₹ {{ number_format($user->balance, 2) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Total Deposits</h5>
                                                    <h3 class="text-success">₹ {{ number_format($user->total_deposits ?? 0, 2) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Total Withdrawals</h5>
                                                    <h3 class="text-danger">₹ {{ number_format($user->total_withdrawals ?? 0, 2) }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Net P/L</h5>
                                                    <h3 class="{{ ($user->net_pl ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                                        ₹ {{ number_format($user->net_pl ?? 0, 2) }}
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Transaction History</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="text-primary">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Transaction ID</th>
                                                    <th>Type</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($transactions) && count($transactions) > 0)
                                                    @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                                        <td>{{ $transaction->id }}</td>
                                                        <td>
                                                            @if($transaction->type == 'deposit')
                                                                <span class="badge badge-success">Deposit</span>
                                                            @elseif($transaction->type == 'withdrawal')
                                                                <span class="badge badge-danger">Withdrawal</span>
                                                            @elseif($transaction->type == 'profit')
                                                                <span class="badge badge-info">Profit</span>
                                                            @elseif($transaction->type == 'loss')
                                                                <span class="badge badge-warning">Loss</span>
                                                            @elseif($transaction->type == 'brokerage')
                                                                <span class="badge badge-secondary">Brokerage</span>
                                                            @else
                                                                <span class="badge badge-primary">{{ ucfirst($transaction->type) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>₹ {{ number_format($transaction->amount, 2) }}</td>
                                                        <td>
                                                            @if($transaction->status == 'completed')
                                                                <span class="badge badge-success">Completed</span>
                                                            @elseif($transaction->status == 'pending')
                                                                <span class="badge badge-warning">Pending</span>
                                                            @elseif($transaction->status == 'rejected')
                                                                <span class="badge badge-danger">Rejected</span>
                                                            @else
                                                                <span class="badge badge-secondary">{{ ucfirst($transaction->status) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $transaction->description }}</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center">No transactions found</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    @if(isset($transactions) && $transactions->hasPages())
                                        <div class="pagination-wrapper">
                                            {{ $transactions->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="#" class="btn btn-success btn-block mb-3">
                                                <i class="material-icons">add</i> Add Funds
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="#" class="btn btn-danger btn-block mb-3">
                                                <i class="material-icons">remove</i> Withdraw Funds
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
