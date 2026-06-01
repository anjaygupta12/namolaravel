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
                                    @foreach ($positions as $val)
                                        <tr>
                                            <td><a class="badge badge-pill badge-success"
                                                    href="{{ route('admin.closed-users',$val->Pk_id) }}">{{ $val->Symbol }}</a>
                                            </td>
                                            <td>{{ $val->Lots }}</td>
                                            <td>{{ number_format($val->BUYPRICE,2) }}</td>
                                            <td>{{ number_format($val->SELLPRICE,2) }}</td>
                                            <td>{{ number_format($val->netpl,2) }}</td>
                                            <td>{{ number_format($val->brokrage,2)}}</td>
                                            <td>{{ number_format($val->netpl - $val->brokrage,2) }}</td>
                                        </tr>
                                    @endforeach()
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
