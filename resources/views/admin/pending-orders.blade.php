@extends('layouts.admin')

@section('title', 'Pending Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Pending Orders</h4>
                    <p class="card-category">Manage pending trade orders</p>
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
                                <div class="form-group mx-sm-3">
                                    <label for="scriptFilter" class="mr-2">Script:</label>
                                    <select class="form-control" id="scriptFilter">
                                        <option value="">All Scripts</option>
                                        <option value="NIFTY">NIFTY</option>
                                        <option value="BANKNIFTY">BANKNIFTY</option>
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
                                    <th>Order ID</th>
                                    <th>Client</th>
                                    <th>Script</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Order Type</th>
                                    <th>Trigger Price</th>
                                    <th>Created Time</th>
                                    <th>Valid Till</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PO1001</td>
                                    <td>John Doe</td>
                                    <td>NIFTY 22000 CE</td>
                                    <td><span class="badge badge-success">BUY</span></td>
                                    <td>50</td>
                                    <td>150.25</td>
                                    <td>Limit</td>
                                    <td>-</td>
                                    <td>2023-05-15 10:30:25</td>
                                    <td>2023-05-15 15:30:00</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Execute</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Cancel</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PO1002</td>
                                    <td>Jane Smith</td>
                                    <td>BANKNIFTY 46000 PE</td>
                                    <td><span class="badge badge-danger">SELL</span></td>
                                    <td>25</td>
                                    <td>320.75</td>
                                    <td>Stop Loss</td>
                                    <td>315.50</td>
                                    <td>2023-05-15 11:15:40</td>
                                    <td>2023-05-15 15:30:00</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Execute</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Cancel</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PO1003</td>
                                    <td>John Doe</td>
                                    <td>NIFTY 21500 PE</td>
                                    <td><span class="badge badge-success">BUY</span></td>
                                    <td>100</td>
                                    <td>85.25</td>
                                    <td>Limit</td>
                                    <td>-</td>
                                    <td>2023-05-15 09:45:15</td>
                                    <td>2023-05-15 15:30:00</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Execute</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Cancel</button>
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
        $('.table').DataTable();
        
        // Auto refresh data every 30 seconds
        setInterval(function() {
            // In a real application, this would fetch fresh data from the server
            console.log('Refreshing pending orders data...');
        }, 30000);
    });
</script>
@endsection
