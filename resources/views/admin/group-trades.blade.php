@extends('layouts.admin')

@section('title', 'Group Trades')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Group Trades</h4>
                    <p class="card-category">Manage and view grouped trades</p>
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
                                    <label for="groupFilter" class="mr-2">Group:</label>
                                    <select class="form-control" id="groupFilter">
                                        <option value="">All Groups</option>
                                        <option value="1">Intraday</option>
                                        <option value="2">Positional</option>
                                        <option value="3">Options</option>
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
                                    <th>Group ID</th>
                                    <th>Group Name</th>
                                    <th>Created Date</th>
                                    <th>Total Trades</th>
                                    <th>Total Value</th>
                                    <th>Net P&L</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>G001</td>
                                    <td>Intraday NIFTY</td>
                                    <td>2023-05-15</td>
                                    <td>12</td>
                                    <td>₹85,250</td>
                                    <td class="text-success">+₹7,850</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View Trades</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Close Group</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>G002</td>
                                    <td>Positional BANKNIFTY</td>
                                    <td>2023-05-10</td>
                                    <td>8</td>
                                    <td>₹120,500</td>
                                    <td class="text-danger">-₹3,250</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View Trades</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Close Group</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>G003</td>
                                    <td>Options Strategy</td>
                                    <td>2023-05-12</td>
                                    <td>15</td>
                                    <td>₹95,750</td>
                                    <td class="text-success">+₹12,500</td>
                                    <td><span class="badge badge-warning">Closing</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View Trades</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
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
    });
</script>
@endsection
