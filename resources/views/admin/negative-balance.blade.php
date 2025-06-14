@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Negative Balance Transactions</h4>
                        <p class="card-category">View and manage negative balance transactions</p>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" id="from_date" class="form-control" name="from_date"
                                    placeholder="From Date" required="">
                            </div>
                            <div class="col-md-4">
                                <input type="date" id="to_date" class="form-control" name="to_date"
                                    placeholder="To Date" required="">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="export" class="btn btn-info">Download Report</button>
                            </div>
                        </div>
   
                        <div class="funds-wd-search">
                            <form id="funds_search" name="funds_search" action="" method="get">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group field-fundswdsearch-user_id">
                                            <label class="control-label" for="fundswdsearch-user_id">User ID</label>
                                            <input type="text" id="fundswdsearch-user_id" class="form-control"
                                                value="" name="userid">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group field-fundswdsearch-wd">
                                            <label class="control-label" for="fundswdsearch-wd">Amount</label>
                                            <input type="text" id="fundswdsearch-wd" value="" class="form-control"
                                                name="amount">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a href="javascript:void(0);"
                                                onclick="document.getElementById('funds_search').reset();" title="delete">
                                                <div class="btn btn-primary"> <i class="fa-solid fa-arrows-rotate"> </i>
                                                    Reset</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Txn Type</th>
                                        <th>Notes</th>
                                        <th>created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Test12</td>
                                        <td>TEst</td>
                                        <td>123</td>
                                        <td>Debit</td>
                                        <td>Test Note</td>
                                        <td>29-1-2025</td>
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
