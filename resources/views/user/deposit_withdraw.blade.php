@extends('layouts.user')

@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Funds</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="wallet-card">
                <div class="balance">
                    <div class="left">
                        <span class="title">Total Balance</span>
                        <h1 class="total">₹ 300,000.00</h1>
                    </div>
                    <div class="right">
                        <span class="title">Available Margin</span>
                        <h1 class="total">₹ 250,000.00</h1>
                    </div>
                </div>
                <div class="wallet-footer">
                    <div class="item">
                        <a href="{{ route('deposit.request.form') }}" class="btn btn-primary btn-block">
                            <ion-icon name="add-outline"></ion-icon>
                            Deposit
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('withdrawal.requests.form') }}" class="btn btn-secondary btn-block">
                            <ion-icon name="arrow-down-outline"></ion-icon>
                            Withdraw
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section mt-4">
    <div class="section-heading">
        <h2 class="title">Recent Transactions</h2>
        <a href="#" class="link">View All</a>
    </div>
    <div class="transactions">
        <!-- item -->
        <a href="#" class="item">
            <div class="detail">
                <img src="{{ asset('assets/img/sample/brand/1.jpg') }}" alt="img" class="image-block imaged w48">
                <div>
                    <strong>Deposit</strong>
                    <p>HDFC Bank</p>
                </div>
            </div>
            <div class="right">
                <div class="price text-success"> + ₹50,000</div>
                <p class="small">June 10, 2025</p>
            </div>
        </a>
        <!-- item -->
        <a href="#" class="item">
            <div class="detail">
                <img src="{{ asset('assets/img/sample/brand/1.jpg') }}" alt="img" class="image-block imaged w48">
                <div>
                    <strong>Withdrawal</strong>
                    <p>HDFC Bank</p>
                </div>
            </div>
            <div class="right">
                <div class="price text-danger"> - ₹25,000</div>
                <p class="small">June 5, 2025</p>
            </div>
        </a>
        <!-- item -->
        <a href="#" class="item">
            <div class="detail">
                <img src="{{ asset('assets/img/sample/brand/2.jpg') }}" alt="img" class="image-block imaged w48">
                <div>
                    <strong>Deposit</strong>
                    <p>ICICI Bank</p>
                </div>
            </div>
            <div class="right">
                <div class="price text-success"> + ₹100,000</div>
                <p class="small">May 28, 2025</p>
            </div>
        </a>
        <!-- item -->
        <a href="#" class="item">
            <div class="detail">
                <img src="{{ asset('assets/img/sample/brand/2.jpg') }}" alt="img" class="image-block imaged w48">
                <div>
                    <strong>Withdrawal</strong>
                    <p>ICICI Bank</p>
                </div>
            </div>
            <div class="right">
                <div class="price text-danger"> - ₹35,000</div>
                <p class="small">May 15, 2025</p>
            </div>
        </a>
    </div>
</div>

<div class="section mt-4 mb-3">
    <div class="section-heading">
        <h2 class="title">Transaction Status</h2>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pending" role="tab">
                        Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#completed" role="tab">
                        Completed
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#rejected" role="tab">
                        Rejected
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-1">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div class="transactions">
                        <!-- item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>Deposit Request</strong>
                                    <p>HDFC Bank - XXXX1234</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price"> ₹25,000</div>
                                <span class="badge badge-warning">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Completed Tab -->
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    <div class="transactions">
                        <!-- item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>Deposit</strong>
                                    <p>HDFC Bank - XXXX1234</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-success"> ₹50,000</div>
                                <span class="badge badge-success">Completed</span>
                            </div>
                        </div>
                        <!-- item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>Withdrawal</strong>
                                    <p>ICICI Bank - XXXX5678</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-danger"> ₹35,000</div>
                                <span class="badge badge-success">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Rejected Tab -->
                <div class="tab-pane fade" id="rejected" role="tabpanel">
                    <div class="transactions">
                        <!-- item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>Withdrawal Request</strong>
                                    <p>HDFC Bank - XXXX1234</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price"> ₹75,000</div>
                                <span class="badge badge-danger">Rejected</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <p class="text-danger">Insufficient funds in account</p>
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
        // Tab handling
        $('.nav-tabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection
