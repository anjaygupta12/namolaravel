@extends('layouts.admin')

@section('title', 'Bank Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header " >
                    <h4 class="card-title">Bank Details</h4>
                    <p class="card-category">Manage bank account details</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>IFSC Code</th>
                                    <th>Account Holder</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ $bankDetails->bank_name }}</td>
                                    <<td>{{ 'XXXX' . substr($bankDetails->account_number, -4) }}</td>
                                    <td>{{ $bankDetails->ifsc }}</td>
                                    <td>{{ $bankDetails->account_holder }}</td>
                                    <td>
                                        <a href="{{ route('admin.bank-details-edit', Session::get('admin_id') ) }}" class="btn btn-primary btn-sm">Edit</a>
                                      
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
