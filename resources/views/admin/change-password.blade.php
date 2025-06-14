@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">


                        <h4 class="card-title">Change Password</h4>
                        <p class="card-category">Update your login password</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.update-transaction-password') }}">
                            @csrf
                            <input type="hidden" name="Login" value="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Current Transaction Password</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                        @error('current_password')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">New Transaction Password</label>
                                        <input type="password" name="new_password" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Confirm New Transaction Password</label>
                                        <input type="password" name="new_password_confirmation" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">Update Transaction Password</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Password Guidelines</h4>
                        <p class="card-category">Security recommendations</p>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Use at least 8 characters</li>
                            <li>Include uppercase and lowercase letters</li>
                            <li>Include at least one number</li>
                            <li>Include at least one special character</li>
                            <li>Avoid using personal information</li>
                            <li>Don't reuse passwords from other sites</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Form validation
            // $('form').submit(function(e) {
            //     e.preventDefault();

            //     // Get form values
            //     var currentPassword = $('input[type="password"]').eq(0).val();
            //     var newPassword = $('input[type="password"]').eq(1).val();
            //     var confirmPassword = $('input[type="password"]').eq(2).val();

            //     // Validate
            //     if (!currentPassword || !newPassword || !confirmPassword) {
            //         alert('All fields are required');
            //         return false;
            //     }

            //     if (newPassword !== confirmPassword) {
            //         alert('New password and confirmation do not match');
            //         return false;
            //     }

            // });
        });
    </script>
@endsection
