@extends('layouts.admin')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Withdrawal Requests</h4>
                    <p class="card-category">Manage client withdrawal requests</p>
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
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="processed">Processed</option>
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
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Account No.</th>
                                    <th>IFSC</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawalRequests as $val)
                                <tr>
                                    <td>{{$val->user->user_id}}</td>
                                    <td>{{$val->user->FullName}}</td>
                                    <td>₹{{$val->Amount}}</td>
                                    <td>{{$val->PaymentMethod}}</td>
                                   <td>{{ 'XXXX' . substr($val->AccountNo, -4) }}</td>
                                    <td>{{$val->IFSC}}</td>
                                    <td>{{ $val->created_at ? \Carbon\Carbon::parse($val->created_at)->format('d-M-Y h:i:s A') : '' }} </td>
                                    <td><span class="badge badge-warning">Pending</span></td>
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
    
    <!-- Withdrawal Details Modal -->
    <div class="modal fade" id="withdrawalDetailsModal" tabindex="-1" role="dialog" aria-labelledby="withdrawalDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawalDetailsModalLabel">Withdrawal Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Request Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Request ID</th>
                                    <td>WR001</td>
                                </tr>
                                <tr>
                                    <th>Client</th>
                                    <td>John Doe</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>₹5,000</td>
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
                            <h6>Bank Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Bank Name</th>
                                    <td>HDFC Bank</td>
                                </tr>
                                <tr>
                                    <th>Account Number</th>
                                    <td>XXXX1234</td>
                                </tr>
                                <tr>
                                    <th>IFSC Code</th>
                                    <td>HDFC0001234</td>
                                </tr>
                                <tr>
                                    <th>Account Holder</th>
                                    <td>John Doe</td>
                                </tr>
                                <tr>
                                    <th>Account Type</th>
                                    <td>Savings</td>
                                </tr>
                            </table>
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
                    <button type="button" class="btn btn-success">Approve</button>
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
            "order": [[ 6, "desc" ]]
        });
        
        // View withdrawal details
        $('.btn-info').on('click', function() {
            $('#withdrawalDetailsModal').modal('show');
        });
    });

            function approove(Id) {
           
            if (!confirm(`want to Approve this withdrawal?`)) return;
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
            if (!confirm(`want to Reject this withdrawal?`)) return;

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
