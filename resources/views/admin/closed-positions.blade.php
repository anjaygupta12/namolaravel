@extends('layouts.admin')

@section('title', 'Closed Positions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Closed Positions</h4>
                        <p class="card-category">History of closed trading positions</p>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Scrip</th>
                                        <th>Lots</th>
                                        <th>Avg buy rate</th>
                                        <th>Avg sell rate</th>
                                        <th>Profit / Loss</th>
                                        <th>Brokerage</th>
                                        <th>Net P/L</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td><a class="badge badge-pill badge-success"
                                                href="closedtrades-user.aspx?scrip_id=AMBUJACEM25JAN540PE">NSE:INDUSINDBK-EQ</a>
                                        </td>
                                        <td>1.000000</td>
                                        <td>0.000000</td>
                                        <td>0.000000</td>
                                        <td>-1890</td>
                                        <td>40</td>
                                        <td>-1930</td>
                                    </tr>
                                    <tr>
                                        <td><a class="badge badge-pill badge-success"
                                                href="closedtrades-user.aspx?scrip_id=AMBUJACEM25JAN540PE">NSE:INDUSINDBK-EQ</a>
                                        </td>
                                        <td>1.000000</td>
                                        <td>0.000000</td>
                                        <td>0.000000</td>
                                        <td>-1890</td>
                                        <td>40</td>
                                        <td>-1930</td>
                                    </tr>
                                    <tr>
                                        <td><a class="badge badge-pill badge-success"
                                                href="closedtrades-user.aspx?scrip_id=AMBUJACEM25JAN540PE">NSE:INDUSINDBK-EQ</a>
                                        </td>
                                        <td>1.000000</td>
                                        <td>0.000000</td>
                                        <td>0.000000</td>
                                        <td>-1890</td>
                                        <td>40</td>
                                        <td>-1930</td>
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
