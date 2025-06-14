@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Change Password</h4>
                    <p class="card-category">Update your login password</p>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Current Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">New Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Confirm New Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">Update Password</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-info">
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
        $('form').submit(function(e) {
            e.preventDefault();
            
            // Get form values
            var currentPassword = $('input[type="password"]').eq(0).val();
            var newPassword = $('input[type="password"]').eq(1).val();
            var confirmPassword = $('input[type="password"]').eq(2).val();
            
            // Validate
            if (!currentPassword || !newPassword || !confirmPassword) {
                alert('All fields are required');
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                alert('New password and confirmation do not match');
                return false;
            }
            
            // Show success message (in a real app, this would be an AJAX call)
            alert('Password updated successfully!');
        });
    });
</script>
@endsection
