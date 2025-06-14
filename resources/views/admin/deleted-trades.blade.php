@extends('layouts.admin')

@section('title', 'Deleted Trades')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Deleted Trades</h4>
                    <p class="card-category">History of deleted trades</p>
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
                                    <th>Price</th>
                                    <th>Value</th>
                                    <th>Created Date</th>
                                    <th>Deleted Date</th>
                                    <th>Deleted By</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1005</td>
                                    <td>John Doe</td>
                                    <td>NIFTY 22000 CE</td>
                                    <td><span class="badge badge-success">BUY</span></td>
                                    <td>50</td>
                                    <td>150.25</td>
                                    <td>₹7,512.50</td>
                                    <td>2023-05-15 10:30:25</td>
                                    <td>2023-05-15 10:35:40</td>
                                    <td>Admin</td>
                                    <td>Duplicate entry</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-success btn-sm">Restore</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1008</td>
                                    <td>Jane Smith</td>
                                    <td>BANKNIFTY 46000 PE</td>
                                    <td><span class="badge badge-danger">SELL</span></td>
                                    <td>25</td>
                                    <td>320.75</td>
                                    <td>₹8,018.75</td>
                                    <td>2023-05-16 11:15:40</td>
                                    <td>2023-05-16 11:20:15</td>
                                    <td>Admin</td>
                                    <td>Client request</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-success btn-sm">Restore</button>
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
            "order": [[ 8, "desc" ]],
            "pageLength": 25
        });
    });
</script>
@endsection
