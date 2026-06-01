@extends('layouts.user')

@section('content')
<div class="account-page">
    <!-- Profile Section -->
    <div class="profile-section">
        <div class="profile-pic">
            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="Profile" class="profile-image">
        </div>
        <div class="profile-details">
            <h2 class="profile-name">{{Auth::guard('tradeuser')->user()->FullName }}</h2>
            <p class="profile-id">ID: {{Auth::guard('tradeuser')->user()->user_id }}</p>
        </div>
    </div>

    <!-- Account Menu -->
    <div class="account-menu-section">
        <h3 class="section-title">My Account</h3>
        
        <div class="menu-grid">
            <a href="#" class="menu-item" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                <div class="menu-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <span class="menu-text">Notifications</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="#" class="menu-item" data-bs-toggle="modal" data-bs-target="#fundsModal">
                <div class="menu-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <span class="menu-text">Funds</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="#" class="menu-item" data-bs-toggle="modal" data-bs-target="#profileModal">
                <div class="menu-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="menu-text">Profile</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="{{route('deposit.request.form') }}" class="menu-item" >
            {{-- <a href="{{route('deposit.request.form') }}" class="menu-item" data-bs-toggle="modal" data-bs-target="#depositModal"> --}}

                <div class="menu-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <span class="menu-text">Deposit Funds</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="{{route('withdrawal.requests.form') }}" class="menu-item" >
            {{-- <a href="{{route('withdrawal.requests.form') }}" class="menu-item" data-bs-toggle="modal" data-bs-target="#withdrawalModal"> --}}

                <div class="menu-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <span class="menu-text">Withdraw Funds</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="#" class="menu-item" data-bs-toggle="modal" data-bs-target="#withdrawalRequestsModal">
                <div class="menu-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <span class="menu-text">Withdrawal Requests</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="#" class="menu-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <div class="menu-icon">
                    <i class="fas fa-key"></i>
                </div>
                <span class="menu-text">Change Password</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="https://namotraders.in/namo.apk" target="_blank" class="menu-item">
                <div class="menu-icon">
                    <i class="fas fa-download"></i>
                </div>
                <span class="menu-text">Download APK</span>
                <i class="fas fa-chevron-right"></i>
            </a>
           
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a href="#" class="menu-item logout-btn" data-bs-toggle="modal" data-bs-target="#logoutConfirmation">
                <div class="menu-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="menu-text">Log Out</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Full Width Modals -->
<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="notification-list">
                    <!-- Sample notifications -->
                    @foreach($notifaction as $noti)
                    <div class="notification-item">
                        <div class="notification-icon">
                            @if($noti->is_read==0)
                            <i class="fas fa-info-circle text-primary"></i>
                            @else
                             <i class="fas fa-check-circle text-success"></i>
                            @endif
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">{{  $noti->message }}</p>
                           <p class="notification-time">{{ $noti->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                    {{-- <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">Your withdrawal request has been approved.</p>
                            <p class="notification-time">1 day ago</p>
                        </div>
                    </div> --}}
                    <!-- Add more notifications as needed -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Funds Modal -->
<div class="modal fade" id="fundsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Funds Overview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- <div class="balance-cards">
                    <div class="balance-card">
                        <h6>Available Balance</h6>
                        <h3>₹ {{Auth::guard('tradeuser')->user()->balance }} </h3>
                    </div>
                    <div class="balance-card">
                        <h6>Invested Amount</h6>
                        <h3>₹{{$invest}}</h3>
                    </div>
                    <div class="balance-card">
                        <h6>Total Profit</h6>
                        <h3>₹ {{ Auth::guard('tradeuser')->user()->net_p_l }}</h3>
                    </div>
                </div> --}}
                <div class="transaction-history mt-4">
                    {{-- <h6>Recent Transactions</h6> --}}
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                {{-- <th>Description</th> --}}
                                <th>Amount</th>
                                {{-- <th>Status</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $val)
                            <tr>
                                <td>{{ $val->created_at ? \Carbon\Carbon::parse($val->LastModify)->format('d-M-Y h:i:s A') : '' }}</td>
                                {{-- <td>{{ ($val->type==1)? 'Deposit': 'Withdrawal' }}</td> --}}
                                <td><span class="badge badge-success" > + {{$val->Amount }} </span></td>
                                {{-- <td>
                                   <p class="detail mt-1 mb-1">
                                    <span class="badge {{$val->Approve_Status === 'APPROVED' ? 'badge-success' : 'badge-danger'}}">
                                    {{ $val->Approve_Status}}
                                    </span>
                                </p>
                                </td> --}}
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="profile-form">
                    <div class="profile-picture-section text-center mb-4">
                        <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="Profile" class="profile-image-lg">
                        <button class="btn btn-sm btn-primary mt-2">Change Photo</button>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" value="{{Auth::guard('tradeuser')->user()->FullName }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">User Name</label>
                            <input type="email" class="form-control" id="email" value="{{Auth::guard('tradeuser')->user()->Username }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" value="{{Auth::guard('tradeuser')->user()->Mobile }}">
                        </div>
                       
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deposit Funds</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="deposit-options">
                    <h6>Select Deposit Method</h6>
                    <div class="payment-methods">
                        <div class="payment-method active">
                            <img src="{{ asset('assets/img/payment/upi.png') }}" alt="UPI">
                            <span>UPI</span>
                        </div>
                        <div class="payment-method">
                            <img src="{{ asset('assets/img/payment/bank-transfer.png') }}" alt="Bank Transfer">
                            <span>Bank Transfer</span>
                        </div>
                        <div class="payment-method">
                            <img src="{{ asset('assets/img/payment/paytm.png') }}" alt="Paytm">
                            <span>Paytm</span>
                        </div>
                    </div>
                    
                    <div class="deposit-form mt-4">
                        <form>
                            <div class="mb-3">
                                <label for="depositAmount" class="form-label">Amount (₹)</label>
                                <input type="number" class="form-control" id="depositAmount" placeholder="Enter amount">
                            </div>
                            <div class="mb-3">
                                <label for="upiId" class="form-label">UPI ID</label>
                                <input type="text" class="form-control" id="upiId" placeholder="Enter UPI ID">
                            </div>
                            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Modal -->
<div class="modal fade" id="withdrawalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdraw Funds</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="withdrawal-form">
                    <form>
                        <div class="mb-3">
                            <label for="withdrawalAmount" class="form-label">Amount (₹)</label>
                            <input type="number" class="form-control" id="withdrawalAmount" placeholder="Enter amount">
                            <small class="text-muted">Minimum withdrawal amount: ₹500</small>
                        </div>
                        <div class="mb-3">
                            <label for="bankAccount" class="form-label">Bank Account</label>
                            <select class="form-select" id="bankAccount">
                                <option selected>Select Bank Account</option>
                                <option>HDFC Bank - XXXX5678</option>
                                <option>ICICI Bank - XXXX1234</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="withdrawalNote" class="form-label">Note (Optional)</label>
                            <textarea class="form-control" id="withdrawalNote" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Request Withdrawal</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Requests Modal -->
<div class="modal fade" id="withdrawalRequestsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Requests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="requests-list">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Bank Account</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2023-06-15</td>
                                <td>₹5,000</td>
                                <td>HDFC - XXXX5678</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td><button class="btn btn-sm btn-outline-primary">View</button></td>
                            </tr>
                            <tr>
                                <td>2023-06-10</td>
                                <td>₹3,000</td>
                                <td>ICICI - XXXX1234</td>
                                <td><span class="badge bg-warning">Processing</span></td>
                                <td><button class="btn btn-sm btn-outline-primary">View</button></td>
                            </tr>
                            <tr>
                                <td>2023-06-05</td>
                                <td>₹7,000</td>
                                <td>HDFC - XXXX5678</td>
                                <td><span class="badge bg-danger">Rejected</span></td>
                                <td><button class="btn btn-sm btn-outline-primary">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="password-form">
                    @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="new_password" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade custom-centered" id="logoutConfirmation" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 bg-danger">
                <h5 class="modal-title text-white fw-bold">
                    Are you sure you want to logout?
                </h5>
            </div>
            <div class="modal-body">
                <div class="card bg-transparent border-0">
                    <div class="card-body pt-0">
                        <div class="form-group basic">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-success" onclick="document.getElementById('logout-form').submit();" style="border-radius: 0; min-width: 130px;">
                                    Confirm
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="border-radius: 0; min-width: 130px;">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Base Styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f5f5f5;
    }
    
    .account-page {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
    }
    
    /* Profile Section */
    .profile-section {
        margin-top:68px;
        display: flex;
        align-items: center;
        padding: 20px;
        background-color: #fff;
        width: 100%;
    }
    
    .profile-pic {
        margin-right: 15px;
    }
    
    .profile-image {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e0e0e0;
    }

    .profile-image-lg {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e0e0e0;
    }
    
    .profile-details {
        flex: 1;
    }
    
    .profile-name {
        font-size: 1.4rem;
        color: #333;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .profile-id {
        font-size: 0.9rem;
        color: #666;
    }
    
    /* Account Menu */
    .account-menu-section {
        width: 100%;
        padding: 0 15px 20px;
        background-color: #fff;
        margin-top: 10px;
    }
    
    .section-title {
        font-size: 1.2rem;
        color: #333;
        padding: 15px 0;
        font-weight: 600;
    }
    
    .menu-grid {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-bottom: 42px;
    }
    
    .menu-item {
        display: flex;
        align-items: center;
        padding: 15px 10px;
        text-decoration: none;
        color: #333;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
    }
    
    .menu-item:last-child {
        border-bottom: none;
    }
    
    .menu-item:hover {
        background-color: #040303;
    }
    
    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        background-color: #4285f4;
    }
    
    .menu-icon i {
        font-size: 1rem;
    }
    
    .menu-text {
        flex: 1;
        font-size: 1rem;
    }
    
    .menu-item i.fa-chevron-right {
        color: #aaa;
        font-size: 0.9rem;
    }
    
    /* Special Menu Items */
    .menu-item:nth-child(1) .menu-icon { background-color: #fbbc05; } /* Notifications */
    .menu-item:nth-child(2) .menu-icon { background-color: #34a853; } /* Funds */
    .menu-item:nth-child(3) .menu-icon { background-color: #4285f4; } /* Profile */
    .menu-item:nth-child(4) .menu-icon { background-color: #673ab7; } /* Deposit */
    .menu-item:nth-child(5) .menu-icon { background-color: #ea4335; } /* Withdraw */
    .menu-item:nth-child(6) .menu-icon { background-color: #ff9800; } /* Requests */
    .menu-item:nth-child(7) .menu-icon { background-color: #607d8b; } /* Password */
    .menu-item:nth-child(8) .menu-icon { background-color: #009688; } /* Download */
    .logout-btn .menu-icon { background-color: #f44336; } /* Logout */

    /* Modal Styles */
    .modal-fullscreen .modal-content {
        border-radius: 0;
    }

    .notification-list {
        padding: 0;
    }

    .notification-item {
        display: flex;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .notification-icon {
        font-size: 1.5rem;
        margin-right: 15px;
    }

    .notification-content {
        flex: 1;
    }

    .notification-text {
        margin-bottom: 5px;
    }

    .notification-time {
        font-size: 0.8rem;
        color: #666;
    }

    .balance-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .balance-card {
        flex: 1;
        min-width: 200px;
        padding: 20px;
        background: #d2e9ff;
        border-radius: 10px;
        text-align: center;
    }

    .balance-card h6 {
        color: #666;
        font-size: 0.9rem;
    }

    .balance-card h3 {
        font-weight: bold;
        margin-top: 10px;
    }

    .payment-methods {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .payment-method {
        flex: 1;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-method.active {
        border-color: #0d6efd;
        background-color: #f0f7ff;
    }

    .payment-method img {
        width: 50px;
        height: 50px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .profile-section {
            padding: 15px;
        }
        
        .profile-image {
            width: 60px;
            height: 60px;
        }
        
        .profile-name {
            font-size: 1.2rem;
        }
        
        .menu-item {
            padding: 12px 8px;
        }
        
        .menu-icon {
            width: 36px;
            height: 36px;
            margin-right: 12px;
        }
        
        .menu-text {
            font-size: 0.95rem;
        }

        .balance-card {
            min-width: calc(50% - 15px);
        }

        .payment-methods {
            flex-direction: column;
        }
    }
    
    @media (max-width: 576px) {
        .profile-section {
            padding: 12px;
        }
        
        .profile-image {
            width: 50px;
            height: 50px;
        }
        
        .profile-name {
            font-size: 1.1rem;
        }
        
        .profile-id {
            font-size: 0.8rem;
        }
        
        .menu-item {
            padding: 12px 8px;
        }
        
        .menu-icon {
            width: 32px;
            height: 32px;
        }
        
        .menu-text {
            font-size: 0.9rem;
        }

        .balance-card {
            min-width: 100%;
        }
    }
</style>

<script>
    // Initialize modals and other functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Payment method selection
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                paymentMethods.forEach(m => m.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // You can add more JavaScript functionality here as needed
    });
</script>
@endsection