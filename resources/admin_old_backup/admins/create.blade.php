@extends('layouts.admin')

@section('title', 'Create Admin User')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create New Admin</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.admin-users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="UserName">Username *</label>
                            <input type="text" class="form-control" id="UserName" name="UserName"
                                   value="{{ old('UserName') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Name">Full Name *</label>
                            <input type="text" class="form-control" id="Name" name="Name"
                                   value="{{ old('Name') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Password">Password *</label>
                            <input type="password" class="form-control" id="Password" name="Password" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Password_confirmation">Confirm Password *</label>
                            <input type="password" class="form-control" id="Password_confirmation"
                                   name="Password_confirmation" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email" class="form-control" id="Email" name="Email"
                                   value="{{ old('Email') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Mobile">Mobile</label>
                            <input type="text" class="form-control" id="Mobile" name="Mobile"
                                   value="{{ old('Mobile') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role_id">Role *</label>
                            <select class="form-control" id="role_id" name="role_id" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Isactive">Status</label>
                            <select class="form-control" id="Isactive" name="Isactive">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Admin</button>
                    <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
