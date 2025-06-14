@extends('layouts.user')

@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Deposit Funds</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="depositForm">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="amount">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">₹</span>
                            <input type="number" class="form-control" id="amount" placeholder="Enter amount" min="1000" required>
                        </div>
                        <div class="input-info">Minimum deposit: ₹1,000</div>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="bankAccount">Select Bank Account</label>
                        <select class="form-control custom-select" id="bankAccount" required>
                            <option value="">Select your bank account</option>
                            <option value="1">HDFC Bank - XXXX1234</option>
                            <option value="2">ICICI Bank - XXXX5678</option>
                        </select>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label">Payment Method</label>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                                <input type="radio" name="paymentMethod" id="netBanking" value="netBanking" checked> Net Banking
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="paymentMethod" id="upi" value="upi"> UPI
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="paymentMethod" id="imps" value="imps"> IMPS/NEFT
                            </label>
                        </div>
                    </div>
                </div>

                <div id="netBankingInfo" class="payment-info">
                    <div class="section-title">Company Bank Details</div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="listview simple-listview">
                                <li>
                                    <span>Bank Name</span>
                                    <strong>HDFC Bank</strong>
                                </li>
                                <li>
                                    <span>Account Name</span>
                                    <strong>Namo Traders Pvt Ltd</strong>
                                </li>
                                <li>
                                    <span>Account Number</span>
                                    <strong>50100123456789</strong>
                                </li>
                                <li>
                                    <span>IFSC Code</span>
                                    <strong>HDFC0001234</strong>
                                </li>
                                <li>
                                    <span>Branch</span>
                                    <strong>Mumbai Main Branch</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="upiInfo" class="payment-info" style="display: none;">
                    <div class="section-title">UPI Details</div>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/qr-code.png') }}" alt="QR Code" class="imaged w200">
                                <p class="mt-2">Scan QR code or use UPI ID</p>
                                <div class="chip chip-media">
                                    <i class="chip-icon bg-primary">
                                        <ion-icon name="copy-outline"></ion-icon>
                                    </i>
                                    <span class="chip-label">namotraders@hdfcbank</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="impsInfo" class="payment-info" style="display: none;">
                    <div class="section-title">IMPS/NEFT Details</div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="listview simple-listview">
                                <li>
                                    <span>Bank Name</span>
                                    <strong>HDFC Bank</strong>
                                </li>
                                <li>
                                    <span>Account Name</span>
                                    <strong>Namo Traders Pvt Ltd</strong>
                                </li>
                                <li>
                                    <span>Account Number</span>
                                    <strong>50100123456789</strong>
                                </li>
                                <li>
                                    <span>IFSC Code</span>
                                    <strong>HDFC0001234</strong>
                                </li>
                                <li>
                                    <span>Branch</span>
                                    <strong>Mumbai Main Branch</strong>
                                </li>
                                <li>
                                    <span>Account Type</span>
                                    <strong>Current Account</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="form-group basic mt-2">
                    <div class="input-wrapper">
                        <label class="label" for="transactionId">Transaction ID/Reference Number</label>
                        <input type="text" class="form-control" id="transactionId" placeholder="Enter transaction ID" required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="transactionDate">Transaction Date</label>
                        <input type="date" class="form-control" id="transactionDate" required>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="screenshot">Payment Screenshot (Optional)</label>
                        <input type="file" class="form-control" id="screenshot">
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
                    <button type="submit" class="btn btn-primary btn-block">Submit Deposit Request</button>
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
                        Deposit requests are processed within 1-2 business hours during working days (Mon-Fri, 9 AM - 5 PM).
                    </div>
                </li>
                <li>
                    <div class="text-small">
                        <ion-icon name="alert-circle-outline" class="text-warning"></ion-icon>
                        Always use your registered bank accounts for deposits to avoid delays.
                    </div>
                </li>
                <li>
                    <div class="text-small">
                        <ion-icon name="alert-circle-outline" class="text-warning"></ion-icon>
                        Keep the transaction ID/reference number handy for faster processing.
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deposit Request Submitted</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <ion-icon name="checkmark-circle" class="text-success" style="font-size: 48px;"></ion-icon>
                    <h2 class="text-success mt-2">Success!</h2>
                    <p>Your deposit request has been submitted successfully.</p>
                    <p>Request ID: <strong id="requestId">DRF123456</strong></p>
                    <p>Amount: <strong id="requestAmount">₹10,000</strong></p>
                    <p class="text-muted">You will be notified once your deposit is processed.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('deposit.withdraw') }}" class="btn btn-primary">View All Transactions</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Payment method toggle
        $('input[name="paymentMethod"]').on('change', function() {
            $('.payment-info').hide();
            $('#' + $(this).val() + 'Info').show();
        });
        
        // Form submission
        $('#depositForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!$(this)[0].checkValidity()) {
                $(this)[0].reportValidity();
                return false;
            }
            
            // Set request details in modal
            $('#requestId').text('DRF' + Math.floor(Math.random() * 1000000));
            $('#requestAmount').text('₹' + $('#amount').val());
            
            // Show success modal
            $('#successModal').modal('show');
            
            // Reset form
            $(this)[0].reset();
        });
    });
</script>
@endsection
