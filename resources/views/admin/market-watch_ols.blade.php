@extends('layouts.admin')

@section('title', 'Market Watch')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <!-- Left section -->
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-0 mr-3">Market Watch</h5>
                                    <small class="text-muted">Monitor market prices and trends</small>
                                </div>
                            </div>

                            <!-- Right section -->
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="mb-0 mr-3">Active Clients: <span id="active_clients">5</span></h6>
                                <input type="submit" name="add_to_ban" class="btn btn-success btn-sm mr-2" id="btnaddtoban"
                                    value="Add to Ban">
                                <input type="submit" name="remove_ban" class="btn btn-danger btn-sm" id="btnremoveban"
                                    value="Remove from Ban">
                            </div>
                        </div>
                    </div>

                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Scrip</th>
                                        <th>Bid </th>
                                        <th>Ask</th>
                                        <th>Ltp</th>
                                        <th>Change</th>
                                        <th>High</th>
                                        <th>Low</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>NIFTY</td>
                                        <td>22,055.20</td>
                                        <td class="text-success">+125.30</td>
                                        <td class="text-success">+0.57%</td>
                                        <td>21,950.10</td>
                                        <td>22,075.50</td>
                                        <td>21,900.30</td>

                                    </tr>
                                    <tr>
                                        <td>BANKNIFTY</td>
                                        <td>46,780.50</td>
                                        <td class="text-danger">-320.75</td>
                                        <td class="text-danger">-0.68%</td>
                                        <td>47,050.25</td>
                                        <td>47,125.80</td>
                                        <td>46,750.20</td>

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
                console.log('Refreshing market data...');
            }, 30000);
        });
    </script>
@endsection
