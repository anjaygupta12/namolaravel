@extends('layouts.admin')

@section('title', 'Trades List')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Trades List</h4>
                    <p class="card-category">Comprehensive list of all trades</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form class="form-inline">
                                <div class="form-group mx-sm-3">
                                    <label for="dateFrom" class="mr-2">From:</label>
                                    <input type="date" class="form-control" id="dateFrom">
                                </div>
                                <div class="form-group mx-sm-3">
                                    <label for="dateTo" class="mr-2">To:</label>
                                    <input type="date" class="form-control" id="dateTo">
                                </div>
                                <div class="form-group mx-sm-3">
                                    <label for="scriptFilter" class="mr-2">Script:</label>
                                    <select class="form-control" id="scriptFilter">
                                        <option value="">All Scripts</option>
                                        <option value="NIFTY">NIFTY</option>
                                        <option value="BANKNIFTY">BANKNIFTY</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="button" class="btn btn-success ml-2">Export to Excel</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Script</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>P&L</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1001</td>
                                    <td>2023-05-15</td>
                                    <td>John Doe</td>
                                    <td>NIFTY 22000 CE</td>
                                    <td><span class="badge badge-success">BUY</span></td>
                                    <td>50</td>
                                    <td>150.25</td>
                                    <td>₹7,512.50</td>
                                    <td><span class="badge badge-info">Executed</span></td>
                                    <td class="text-success">+₹1,262.50</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1002</td>
                                    <td>2023-05-15</td>
                                    <td>Jane Smith</td>
                                    <td>BANKNIFTY 46000 PE</td>
                                    <td><span class="badge badge-danger">SELL</span></td>
                                    <td>25</td>
                                    <td>320.75</td>
                                    <td>₹8,018.75</td>
                                    <td><span class="badge badge-info">Executed</span></td>
                                    <td class="text-success">+₹1,012.50</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1003</td>
                                    <td>2023-05-16</td>
                                    <td>John Doe</td>
                                    <td>NIFTY 21500 PE</td>
                                    <td><span class="badge badge-success">BUY</span></td>
                                    <td>75</td>
                                    <td>95.50</td>
                                    <td>₹7,162.50</td>
                                    <td><span class="badge badge-info">Executed</span></td>
                                    <td class="text-danger">-₹562.50</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                    </td>
                                </tr>
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
    $(document).ready(function() {
        // Initialize DataTables
        $('.table').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength": 25
        });
    });
</script>
@endsection
