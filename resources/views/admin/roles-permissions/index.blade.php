
@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Roles & Permissions</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Add New Role -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add New Role</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.roles.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Role Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Create Role</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Permission -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add New Permission</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.permissions.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Permission Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Slug (Route Name)</label>
                                            <input type="text" name="slug" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Create Permission</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Assign Permissions to Roles -->
                    </div>

                    <!-- Roles List -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Roles List</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th>Description</th>
                                                <th>Permissions</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roles as $role)
                                                <tr>
                                                    <td>{{ $role->name }}</td>
                                                    <td>{{ $role->description }}</td>
                                                    <td>
                                                        @foreach($role->permissions as $permission)
                                                            <span class="badge badge-primary">
                                                                {{ $permission->name }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                   <td>
                                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Edit Permissions
                                                    </a>
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
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // When role is selected, check its permissions
    $('#roleSelect').change(function() {
        let roleId = $(this).val();

        // Uncheck all permissions first
        $('.permission-checkbox').prop('checked', false);

        // Get role permissions via AJAX
        $.get(`/admin/roles/${roleId}/permissions`, function(data) {
            // Check the permissions this role has
            data.permissions.forEach(function(permissionId) {
                $(`#perm_${permissionId}`).prop('checked', true);
            });
        });
    });

    // Trigger change on page load to load first role's permissions
    $('#roleSelect').trigger('change');

    // Save permissions
    $('#savePermissionsBtn').click(function() {
        let roleId = $('#roleSelect').val();
        let permissionIds = [];

        $('.permission-checkbox:checked').each(function() {
            permissionIds.push($(this).val());
        });

        $.ajax({
            url: `/admin/roles/${roleId}/permissions`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                permissions: permissionIds
            },
            success: function(response) {
                toastr.success('Permissions updated successfully');
                // Reload the page to see changes
                location.reload();
            },
            error: function() {
                toastr.error('Error updating permissions');
            }
        });
    });

});
</script>
@endsection
