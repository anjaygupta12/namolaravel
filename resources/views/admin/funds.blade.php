@extends('layouts.admin')

@section('title', 'Trader Funds')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Trader Funds</h4>
                        <p class="card-category">Manage trader account balances and funds</p>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.funds-report') }}">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="from_date">From Date</label>
                                            <input type="date" name="from_date" value="" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="to_date">To Date</label>
                                            <input type="date" name="to_date" value="" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3 d-flex align-items-end">
                                            <button type="submit" name="export" class="btn btn-info w-100">Download Funds
                                                Report</button>
                                        </div>
                                    </div>
                                </form>

                                {{-- Form for Filter/Search --}}
                                <form method="GET" action="{{ route('admin.funds-wds') }}">
                                    <div class="row mt-2">
                                        <div class="col-md-4 mb-3">
                                            <label for="user_id">User ID</label>
                                            <input type="text" name="user_id" class="form-control" placeholder="User ID">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="amount">Amount</label>
                                            <input type="text" name="amount" class="form-control" placeholder="Amount">
                                        </div>

                                        <div class="col-md-4 mb-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-success w-100">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">

                                    <tr>
                                        <th>ID</th>
                                        <th>username</th>
                                        <th>name</th>
                                        <th>Amount</th>
                                        <th>Txn Type</th>
                                        <th>Notes</th>
                                        <th>Txn Mode</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($depositQ as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->UserName }}</td>
                                            <td>{{ $item->FullName }}</td>
                                            <td>{{ $item->AmountS }}</td>
                                            <td>{{ $item->Mode }}</td>
                                            <td>{{ $item->Mode }}</td>
                                            <td>{{ $item->Mode }}
                                            </td>
                                            <td>{{ $item->Timestamp }}</td>
                                        </tr>
                                    @endforeach
                                    @foreach ($withdrawQ as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->UserName }}</td>
                                            <td>{{ $item->FullName }}</td>
                                            <td>{{ $item->AmountS }}</td>
                                            <td>{{ $item->Mode }}</td>
                                            <td>{{ $item->Mode }}</td>
                                            <td>{{ $item->Mode }}
                                            </td>
                                            <td>{{ $item->Timestamp }}</td>
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

            // Fund transaction modal handlers
            $('.btn-success, .btn-warning').on('click', function() {
                $('#fundTransactionModal').modal('show');

                // Set transaction type based on button clicked
                if ($(this).hasClass('btn-success')) {
                    $('#transactionType').val('deposit');
                } else {
                    $('#transactionType').val('withdrawal');
                }
            });
        });
    </script>
@endsection
