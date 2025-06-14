@extends('layouts.admin')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
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
                                    <th>Bank</th>
                                    <th>Account No.</th>
                                    <th>IFSC</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>WR001</td>
                                    <td>John Doe</td>
                                    <td>₹5,000</td>
                                    <td>HDFC Bank</td>
                                    <td>XXXX1234</td>
                                    <td>HDFC0001234</td>
                                    <td>2023-05-15 10:30:25</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-success btn-sm">Approve</button>
                                        <button class="btn btn-danger btn-sm">Reject</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>WR002</td>
                                    <td>Jane Smith</td>
                                    <td>₹10,000</td>
                                    <td>ICICI Bank</td>
                                    <td>XXXX5678</td>
                                    <td>ICIC0001234</td>
                                    <td>2023-05-14 15:45:20</td>
                                    <td><span class="badge badge-success">Approved</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Mark Processed</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>WR003</td>
                                    <td>Robert Johnson</td>
                                    <td>₹7,500</td>
                                    <td>SBI</td>
                                    <td>XXXX9012</td>
                                    <td>SBIN0001234</td>
                                    <td>2023-05-13 11:20:15</td>
                                    <td><span class="badge badge-danger">Rejected</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>WR004</td>
                                    <td>Emily Davis</td>
                                    <td>₹15,000</td>
                                    <td>Axis Bank</td>
                                    <td>XXXX3456</td>
                                    <td>UTIB0001234</td>
                                    <td>2023-05-12 09:15:30</td>
                                    <td><span class="badge badge-info">Processed</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                    </td>
                                </tr>
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
</script>
@endsection
