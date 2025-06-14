@extends('layouts.admin')

@section('title', 'Scrip Data')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Scrip Data</h4>
                    <p class="card-category">Manage script information and data</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success">Add New Scrip</button>
                            <button class="btn btn-info">Import Data</button>
                            <button class="btn btn-warning">Update All</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Symbol</th>
                                    <th>Name</th>
                                    <th>Exchange</th>
                                    <th>Category</th>
                                    <th>Last Price</th>
                                    <th>Change %</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>NIFTY</td>
                                    <td>Nifty 50 Index</td>
                                    <td>NSE</td>
                                    <td>Index</td>
                                    <td>22,055.20</td>
                                    <td class="text-success">+0.57%</td>
                                    <td>2023-05-15 15:30:00</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>BANKNIFTY</td>
                                    <td>Bank Nifty Index</td>
                                    <td>NSE</td>
                                    <td>Index</td>
                                    <td>46,780.50</td>
                                    <td class="text-danger">-0.68%</td>
                                    <td>2023-05-15 15:30:00</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>RELIANCE</td>
                                    <td>Reliance Industries Ltd</td>
                                    <td>NSE</td>
                                    <td>Equity</td>
                                    <td>2,950.75</td>
                                    <td class="text-success">+1.25%</td>
                                    <td>2023-05-15 15:30:00</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
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
