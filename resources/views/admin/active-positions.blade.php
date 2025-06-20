@extends('layouts.admin')

@section('title', 'Active Positions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Active Positions</h4>
                        <p class="card-category">Hs01's Active Positions</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Scrip</th>
                                        <th>Active Buy</th>
                                        <th>Active Sell</th>
                                        <th>Avg buy rate</th>
                                        <th>Avg sell rate</th>
                                        <th>Total</th>
                                        <th>Net</th>
                                        <th>M2m</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($positions as $val)
                                    <tr>
                                        <td id="COPPER25FEBFUT"><a class="badge badge-pill badge-success"
                                                href="">{{ $val->Symbol}}</a>
                                        </td>
                                        <td>{{$val->TotalUser}} ({{ $val->BuyPrice}})</td>
                                        <td>>{{$val->TotalUser}} ({{ $val->SalePrice}})</td>
                                        <td id="COPPER25FEBFUT_buy_rate">{{ $val->SalePrice}}</td>
                                        <td id="COPPER25FEBFUT_sell_rate">0</td>
                                        <td id="COPPER25FEBFUT_total_lots">2</td>
                                        <td id="COPPER25FEBFUT_net_lots">2</td>
                                        <td id="COPPER25FEBFUT_m2m">-45000</td>
                                    </tr>
                                    @endforeach
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
                console.log('Refreshing position data...');
            }, 30000);
        });
    </script>
@endsection
