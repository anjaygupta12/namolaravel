@extends('layouts.user')

@section('content')
<div class="section mt-2">
    <div class="profile-head">
        <div class="avatar">
            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
        </div>
        <div class="in">
            <h3 class="name">John Doe</h3>
            <h5 class="subtext">ID: NM123456</h5>
        </div>
    </div>
</div>

<div class="section full mt-2">
    <div class="profile-stats pl-2 pr-2">
        <a href="#" class="item">
            <strong>₹250,000</strong>Available Margin
        </a>
        <a href="#" class="item">
            <strong>₹50,000</strong>Used Margin
        </a>
        <a href="#" class="item">
            <strong>₹300,000</strong>Total Balance
        </a>
    </div>
</div>

<div class="section full mt-1">
    <div class="section-title">Account Information</div>
    <div class="wide-block pt-2 pb-2">
        <div class="row">
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Account Type</div>
                    <div class="value text-primary">Premium</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Account Status</div>
                    <div class="value text-success">Active</div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">KYC Status</div>
                    <div class="value text-success">Verified</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Leverage</div>
                    <div class="value">5x</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section full mt-1">
    <div class="section-title">Personal Information</div>
    <div class="wide-block pt-2 pb-2">
        <form>
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" value="John Doe" readonly>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" value="johndoe@example.com">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="phone">Phone</label>
                    <input type="tel" class="form-control" id="phone" value="+91 9876543210">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="address">Address</label>
                    <textarea class="form-control" id="address" rows="2">123 Main Street, Mumbai, Maharashtra 400001</textarea>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<div class="section full mt-1">
    <div class="section-title">Security</div>
    <div class="wide-block pt-2 pb-2">
        <div class="form-links mt-2">
            <div class="row">
                <div class="col-6">
                    <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <div class="icon-box bg-primary">
                            <ion-icon name="key-outline"></ion-icon>
                        </div>
                        <strong>Change Password</strong>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#changeTxnPasswordModal">
                        <div class="icon-box bg-warning">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </div>
                        <strong>Transaction Password</strong>
                    </a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <a href="#" class="item">
                        <div class="icon-box bg-success">
                            <ion-icon name="shield-checkmark-outline"></ion-icon>
                        </div>
                        <strong>Two-Factor Auth</strong>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" class="item">
                        <div class="icon-box bg-danger">
                            <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                        <strong>Notifications</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section full mt-1 mb-2">
    <div class="section-title">Bank Accounts</div>
    <div class="wide-block pt-2 pb-2">
        <div class="transactions">
            <div class="item">
                <div class="detail">
                    <img src="{{ asset('assets/img/sample/brand/1.jpg') }}" alt="img" class="image-block imaged w48">
                    <div>
                        <strong>HDFC Bank</strong>
                        <p>XXXX XXXX XXXX 1234</p>
                    </div>
                </div>
                <div class="right">
                    <div class="price text-primary">Primary</div>
                </div>
            </div>
            <div class="item">
                <div class="detail">
                    <img src="{{ asset('assets/img/sample/brand/2.jpg') }}" alt="img" class="image-block imaged w48">
                    <div>
                        <strong>ICICI Bank</strong>
                        <p>XXXX XXXX XXXX 5678</p>
                    </div>
                </div>
                <div class="right">
                    <a href="#" class="btn btn-sm btn-text-primary">Set Primary</a>
                </div>
            </div>
            <div class="item">
                <div class="detail">
                    <div class="icon-box bg-primary">
                        <ion-icon name="add-outline"></ion-icon>
                    </div>
                    <div>
                        <strong>Add New Bank Account</strong>
                    </div>
                </div>
                <div class="right">
                    <a href="#" class="btn btn-sm btn-text-secondary">Add</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="currentPassword">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Change Password</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Transaction Password Modal -->
<div class="modal fade" id="changeTxnPasswordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Transaction Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="currentTxnPassword">Current Transaction Password</label>
                            <input type="password" class="form-control" id="currentTxnPassword" placeholder="Enter current transaction password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="newTxnPassword">New Transaction Password</label>
                            <input type="password" class="form-control" id="newTxnPassword" placeholder="Enter new transaction password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="confirmTxnPassword">Confirm Transaction Password</label>
                            <input type="password" class="form-control" id="confirmTxnPassword" placeholder="Confirm new transaction password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Change Password</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Form validation
    $(document).ready(function() {
        // Password validation
        $('#changePasswordModal form').on('submit', function(e) {
            e.preventDefault();
            
            var currentPassword = $('#currentPassword').val();
            var newPassword = $('#newPassword').val();
            var confirmPassword = $('#confirmPassword').val();
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                alert('Please fill all fields');
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                alert('New password and confirm password do not match');
                return false;
            }
            
            // Submit form logic would go here
            alert('Password changed successfully');
            $('#changePasswordModal').modal('hide');
        });
        
        // Transaction password validation
        $('#changeTxnPasswordModal form').on('submit', function(e) {
            e.preventDefault();
            
            var currentTxnPassword = $('#currentTxnPassword').val();
            var newTxnPassword = $('#newTxnPassword').val();
            var confirmTxnPassword = $('#confirmTxnPassword').val();
            
            if (!currentTxnPassword || !newTxnPassword || !confirmTxnPassword) {
                alert('Please fill all fields');
                return false;
            }
            
            if (newTxnPassword !== confirmTxnPassword) {
                alert('New password and confirm password do not match');
                return false;
            }
            
            // Submit form logic would go here
            alert('Transaction password changed successfully');
            $('#changeTxnPasswordModal').modal('hide');
        });
    });
</script>
@endsection
