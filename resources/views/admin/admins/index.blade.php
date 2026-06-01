@extends('layouts.admin')

@section('title', 'Admin Users')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Admin Users</h6>
            <a href="{{ route('admin.admin-users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Admin
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $admin->UserName }}</td>
                            <td>{{ $admin->Name }}</td>
                            <td>{{ $admin->Email }}</td>
                            <td>{{ $admin->role->name ?? 'No Role' }}</td>
                            <td>
                                <span class="badge badge-{{ $admin->Isactive ? 'success' : 'danger' }}">
                                    {{ $admin->Isactive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.admin-users.edit', $admin->PK_ID) }}"
                                   class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.admin-users.destroy', $admin->PK_ID) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            title="Delete" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
