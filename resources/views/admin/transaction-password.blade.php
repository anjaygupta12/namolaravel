@extends('layouts.admin')

@section('title', 'Transaction Password')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Change Transaction Password</h4>
                    <p class="card-category">Update your transaction security password</p>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Current Transaction Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">New Transaction Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Confirm New Transaction Password</label>
                                    <input type="password" class="form-control" required>
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
                <div class="card-header card-header-warning">
                    <h4 class="card-title">Security Notice</h4>
                    <p class="card-category">Important information</p>
                </div>
                <div class="card-body">
                    <p>The transaction password is used for:</p>
                    <ul>
                        <li>Approving fund transfers</li>
                        <li>Confirming withdrawals</li>
                        <li>Authorizing large trades</li>
                        <li>Changing account settings</li>
                    </ul>
                    <p class="text-warning">
                        <strong>Note:</strong> Keep your transaction password different from your login password for enhanced security.
                    </p>
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
            alert('Transaction password updated successfully!');
        });
    });
</script>
@endsection
