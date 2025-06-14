@extends('layouts.admin')

@section('title', 'Closed Trades')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Closed Trades</h4>
                    <p class="card-category">History of completed trades</p>
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
                                    <label for="clientFilter" class="mr-2">Client:</label>
                                    <select class="form-control" id="clientFilter">
                                        <option value="">All Clients</option>
                                        <option value="1">John Doe</option>
                                        <option value="2">Jane Smith</option>
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
                                    <th>Client</th>
                                    <th>Script</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Entry Price</th>
                                    <th>Exit Price</th>
                                    <th>P&L</th>
                                    <th>Open Date</th>
                                    <th>Close Date</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1001</td>
                                    <td>John Doe</td>
                                    <td>NIFTY 21500 CE</td>
                                    <td><span class="badge badge-success">BUY</span></td>
                                    <td>50</td>
                                    <td>120.25</td>
                                    <td>180.50</td>
                                    <td class="text-success">+₹3,012.50</td>
                                    <td>2023-05-10 10:30:25</td>
                                    <td>2023-05-10 15:15:30</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1002</td>
                                    <td>Jane Smith</td>
                                    <td>BANKNIFTY 45000 PE</td>
                                    <td><span class="badge badge-danger">SELL</span></td>
                                    <td>25</td>
                                    <td>350.75</td>
                                    <td>420.25</td>
                                    <td class="text-danger">-₹1,737.50</td>
                                    <td>2023-05-12 11:15:40</td>
                                    <td>2023-05-12 14:45:20</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1003</td>
                                    <td>John Doe</td>
                                    <td>NIFTY 21000 PE</td>
                                    <td><span class="badge badge-success">BUY</span></td>
                                    <td>100</td>
                                    <td>85.25</td>
                                    <td>45.75</td>
                                    <td class="text-danger">-₹3,950.00</td>
                                    <td>2023-05-14 09:45:15</td>
                                    <td>2023-05-14 15:30:00</td>
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
            "order": [[ 9, "desc" ]],
            "pageLength": 25
        });
    });
</script>
@endsection
