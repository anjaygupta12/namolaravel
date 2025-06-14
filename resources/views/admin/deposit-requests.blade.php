@extends('layouts.admin')

@section('title', 'Deposit Requests')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Deposit Requests</h4>
                        <p class="card-category">Manage client deposit requests</p>
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
                                        <label for="statusFilter" class="mr-2">Status:</label>
                                        <select class="form-control" id="statusFilter">
                                            <option value="">All Statuses</option>
                                            <option value="pending">Pending</option>
                                            <option value="verified">Verified</option>
                                            <option value="rejected">Rejected</option>
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
                                        <th style="width: 5%;">ID</th>
                                        <th style="width: 20%;">User Details</th>
                                        <th style="width: 15%;">Broker</th>
                                        <th style="width: 10%;">File</th>
                                        <th style="width: 10%;">Amount</th>
                                        <th style="width: 10%;">Time</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 20%;">Actions</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($data as $val)
                                        <tr>
                                            <td>{{ $val->PK_Id }}</td>
                                            <td>{{ $val->user->Username }}</td>
                                            <td>{{ $val->Amount }}</td>
                                            <td>{{ $val->Amount }}</td>
                                            <td>{{ $val->Amount }}6</td>
                                            <td>{{ $val->Timestamp }}</td>
                                            <td><span class="badge badge-warning">{{ $val->Approve_Status }}</span></td>
                                            <td>
                                                <div class="d-flex">
                                                    <button onclick="approove({{ $val->PK_Id }})"
                                                        class="btn btn-success btn-sm me-1">Verify</button>
                                                    <button onclick="Reject({{ $val->PK_Id }})"
                                                        class="btn btn-danger btn-sm">Reject</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit Details Modal -->
        <div class="modal fade" id="depositDetailsModal" tabindex="-1" role="dialog"
            aria-labelledby="depositDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositDetailsModalLabel">Deposit Request Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Request Information</h6>
                                <table class="table table-sm deposit-tbl">
                                    <tr>
                                        <th>Request ID</th>
                                        <td>DR001</td>
                                    </tr>
                                    <tr>
                                        <th>Client</th>
                                        <td>John Doe</td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td>₹10,000</td>
                                    </tr>
                                    <tr>
                                        <th>Request Date</th>
                                        <td>2023-05-15 10:30:25</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Payment Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Payment Method</th>
                                        <td>Bank Transfer</td>
                                    </tr>
                                    <tr>
                                        <th>Reference Number</th>
                                        <td>HDFC123456</td>
                                    </tr>
                                    <tr>
                                        <th>Bank Name</th>
                                        <td>HDFC Bank</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction Date</th>
                                        <td>2023-05-15</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h6>Payment Proof</h6>
                                <div class="text-center">
                                    <img src="https://via.placeholder.com/400x200?text=Payment+Receipt" class="img-fluid"
                                        alt="Payment Receipt">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h6>Notes</h6>
                                <textarea class="form-control" rows="3" placeholder="Add admin notes here"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Verify</button>
                        <button type="button" class="btn btn-danger">Reject</button>
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
                "order": [
                    [5, "desc"]
                ]
            });

            // View deposit details
            $('.btn-info').on('click', function() {
                $('#depositDetailsModal').modal('show');
            });



        });

        function approove(Id) {
           
            if (!confirm(`want to Approve this deposit?`)) return;
            $.ajax({
                type: "POST",
                url: "{{ route('admin.deposit-status') }}",
                data: {
                    ID: Id,
                    type: 'APPROVED',
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(data) {
                    location.reload();
                     toastr.success(data.message);
                },
                error: function(xhr) {
                        location.reload();
                     toastr.error('Error: ' + xhr.responseText);
                 
                }
            });

        }

        function Reject(Id) {
            if (!confirm(`want to Reject this deposit?`)) return;

               $.ajax({
                type: "POST",
                url: "{{ route('admin.deposit-status') }}",
                data: {
                    ID: Id,
                    type: 'REJECTED',
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(data) {
                   location.reload();
                     toastr.success(data.message);
                },
                error: function(xhr) {
                    location.reload();
                     toastr.error('Error: ' + xhr.responseText);
                 
                }
            });
        }
    </script>
@endsection
