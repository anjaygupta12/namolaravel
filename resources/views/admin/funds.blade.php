@extends('layouts.admin')

@section('title', 'Trader Funds')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Trader Funds</h4>
                    <p class="card-category">Manage trader account balances and funds</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form class="form-inline">
                                <div class="form-group mx-sm-3">
                                    <label for="clientFilter" class="mr-2">Client:</label>
                                    <select class="form-control" id="clientFilter">
                                        <option value="">All Clients</option>
                                        <option value="1">John Doe</option>
                                        <option value="2">Jane Smith</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Available Balance</th>
                                    <th>Used Margin</th>
                                    <th>Total Balance</th>
                                    <th>Last Transaction</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>₹15,000</td>
                                    <td>₹10,000</td>
                                    <td>₹25,000</td>
                                    <td>2023-05-15 (Deposit: ₹5,000)</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View History</button>
                                        <button class="btn btn-success btn-sm">Add Funds</button>
                                        <button class="btn btn-warning btn-sm">Withdraw</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td>₹8,500</td>
                                    <td>₹7,000</td>
                                    <td>₹15,500</td>
                                    <td>2023-05-14 (Withdrawal: ₹2,000)</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View History</button>
                                        <button class="btn btn-success btn-sm">Add Funds</button>
                                        <button class="btn btn-warning btn-sm">Withdraw</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fund Transaction Modal -->
    <div class="modal fade" id="fundTransactionModal" tabindex="-1" role="dialog" aria-labelledby="fundTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fundTransactionModalLabel">Fund Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="transactionType">Transaction Type</label>
                            <select class="form-control" id="transactionType">
                                <option value="deposit">Deposit</option>
                                <option value="withdrawal">Withdrawal</option>
                                <option value="adjustment">Adjustment</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="transactionAmount">Amount</label>
                            <input type="number" class="form-control" id="transactionAmount" placeholder="Enter amount">
                        </div>
                        <div class="form-group">
                            <label for="transactionNotes">Notes</label>
                            <textarea class="form-control" id="transactionNotes" rows="3" placeholder="Enter transaction notes"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Process Transaction</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('.table').DataTable();
        
        // Fund transaction modal handlers
        $('.btn-success, .btn-warning').on('click', function() {
            $('#fundTransactionModal').modal('show');
            
            // Set transaction type based on button clicked
            if ($(this).hasClass('btn-success')) {
                $('#transactionType').val('deposit');
            } else {
                $('#transactionType').val('withdrawal');
            }
        });
    });
</script>
@endsection
