@extends('layouts.admin')

@section('title', 'Accounts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Accounts</h4>
                    <p class="card-category">Manage system accounts and permissions</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success">Add New Account</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>admin</td>
                                    <td>admin@namotraders.com</td>
                                    <td>Administrator</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>2023-05-15 10:30:25</td>
                                    <td>2023-01-01</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Deactivate</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>manager</td>
                                    <td>manager@namotraders.com</td>
                                    <td>Manager</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>2023-05-14 15:45:20</td>
                                    <td>2023-01-15</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Deactivate</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>support</td>
                                    <td>support@namotraders.com</td>
                                    <td>Support</td>
                                    <td><span class="badge badge-warning">Inactive</span></td>
                                    <td>2023-05-10 09:15:30</td>
                                    <td>2023-02-01</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-success btn-sm">Activate</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Role Permissions Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Role Permissions</h4>
                    <p class="card-category">Manage role-based permissions</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success">Add New Role</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Role Name</th>
                                    <th>Description</th>
                                    <th>Users Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Administrator</td>
                                    <td>Full system access</td>
                                    <td>1</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View Permissions</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Manager</td>
                                    <td>Manage trades and users</td>
                                    <td>1</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View Permissions</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Support</td>
                                    <td>Customer support access</td>
                                    <td>1</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View Permissions</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
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
