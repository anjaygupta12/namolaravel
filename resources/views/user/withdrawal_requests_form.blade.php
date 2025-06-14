@extends('layouts.user')

@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Withdraw Funds</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="withdrawalForm">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="amount">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">₹</span>
                            <input type="number" class="form-control" id="amount" placeholder="Enter amount" min="1000" required>
                        </div>
                        <div class="input-info">Minimum withdrawal: ₹1,000</div>
                        <div class="input-info">Available balance: ₹250,000</div>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="bankAccount">Select Bank Account</label>
                        <select class="form-control custom-select" id="bankAccount" required>
                            <option value="">Select your bank account</option>
                            <option value="1">HDFC Bank - XXXX1234 (Primary)</option>
                            <option value="2">ICICI Bank - XXXX5678</option>
                        </select>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="transactionPassword">Transaction Password</label>
                        <input type="password" class="form-control" id="transactionPassword" placeholder="Enter transaction password" required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="remarks">Remarks (Optional)</label>
                        <textarea class="form-control" id="remarks" rows="2" placeholder="Any additional information"></textarea>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                    </div>
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary btn-block">Submit Withdrawal Request</button>
                </div>
            </form>
        </div>
    </div>

    <div class="section-title mt-2">Important Notes</div>
    <div class="card">
        <div class="card-body">
            <ul class="listview simple-listview">
                <li>
                    <div class="text-small">
                        <ion-icon name="alert-circle-outline" class="text-warning"></ion-icon>
                        Withdrawal requests are processed within 24 hours during working days (Mon-Fri).
                    </div>
                </li>
                <li>
                    <div class="text-small">
                        <ion-icon name="alert-circle-outline" class="text-warning"></ion-icon>
                        Funds will only be transferred to your registered bank accounts.
                    </div>
                </li>
                <li>
                    <div class="text-small">
                        <ion-icon name="alert-circle-outline" class="text-warning"></ion-icon>
                        A processing fee of ₹10 may be charged by the bank for IMPS transfers.
                    </div>
                </li>
                <li>
                    <div class="text-small">
                        <ion-icon name="alert-circle-outline" class="text-warning"></ion-icon>
                        Ensure you have sufficient available balance before requesting a withdrawal.
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="section-title mt-2">Recent Withdrawals</div>
    <div class="transactions">
        <!-- item -->
        <a href="#" class="item">
            <div class="detail">
                <img src="{{ asset('assets/img/sample/brand/1.jpg') }}" alt="img" class="image-block imaged w48">
                <div>
                    <strong>₹25,000</strong>
                    <p>HDFC Bank - XXXX1234</p>
                </div>
            </div>
            <div class="right">
                <div class="price text-success">Completed</div>
                <p class="small">June 5, 2025</p>
            </div>
        </a>
        <!-- item -->
        <a href="#" class="item">
            <div class="detail">
                <img src="{{ asset('assets/img/sample/brand/2.jpg') }}" alt="img" class="image-block imaged w48">
                <div>
                    <strong>₹35,000</strong>
                    <p>ICICI Bank - XXXX5678</p>
                </div>
            </div>
            <div class="right">
                <div class="price text-success">Completed</div>
                <p class="small">May 15, 2025</p>
            </div>
        </a>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Request Submitted</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <ion-icon name="checkmark-circle" class="text-success" style="font-size: 48px;"></ion-icon>
                    <h2 class="text-success mt-2">Success!</h2>
                    <p>Your withdrawal request has been submitted successfully.</p>
                    <p>Request ID: <strong id="requestId">WRF123456</strong></p>
                    <p>Amount: <strong id="requestAmount">₹10,000</strong></p>
                    <p class="text-muted">You will be notified once your withdrawal is processed.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('deposit.withdraw') }}" class="btn btn-primary">View All Transactions</a>
            </div>
        </div>
    </div>
</div>

<!-- Insufficient Balance Modal -->
<div class="modal fade" id="insufficientModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insufficient Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <ion-icon name="alert-circle" class="text-danger" style="font-size: 48px;"></ion-icon>
                    <h2 class="text-danger mt-2">Error!</h2>
                    <p>You don't have sufficient balance to make this withdrawal.</p>
                    <p>Available Balance: <strong>₹250,000</strong></p>
                    <p>Requested Amount: <strong id="requestedAmount">₹300,000</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Form submission
        $('#withdrawalForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!$(this)[0].checkValidity()) {
                $(this)[0].reportValidity();
                return false;
            }
            
            // Check if amount is valid
            var amount = parseFloat($('#amount').val());
            var availableBalance = 250000; // This would come from the server in a real app
            
            if (amount > availableBalance) {
                $('#requestedAmount').text('₹' + amount);
                $('#insufficientModal').modal('show');
                return false;
            }
            
            // Set request details in modal
            $('#requestId').text('WRF' + Math.floor(Math.random() * 1000000));
            $('#requestAmount').text('₹' + amount);
            
            // Show success modal
            $('#successModal').modal('show');
            
            // Reset form
            $(this)[0].reset();
        });
    });
</script>
@endsection
