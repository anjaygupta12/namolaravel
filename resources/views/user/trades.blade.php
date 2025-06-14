@extends('layouts.user')

@section('styles')
<style>
    .trade-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }
    .trade-item:last-child {
        border-bottom: none;
    }
    .profit {
        color: #28a745;
    }
    .loss {
        color: #dc3545;
    }
    .trade-tabs .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
    .trade-details {
        font-size: 0.85rem;
    }
    .trade-symbol {
        font-weight: bold;
        font-size: 1rem;
    }
    .trade-type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .buy {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    .sell {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
</style>
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs trade-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#activeTrades" role="tab">
                            Active
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#pendingOrders" role="tab">
                            Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#closedTrades" role="tab">
                            Closed
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-1">
                    <!-- Active Trades Tab -->
                    <div class="tab-pane fade show active" id="activeTrades" role="tabpanel">
                        <div class="trade-list">
                            <!-- Trade Item 1 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">RELIANCE</span>
                                        <span class="trade-type buy">BUY</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="profit">+₹1,245.50</div>
                                        <small class="text-muted">+2.1%</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Open: </small>
                                            <small>₹2,430.25</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Current: </small>
                                            <small>₹2,456.75</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>5</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Date: </small>
                                            <small>10 Jun 2023</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button class="btn btn-sm btn-outline-secondary me-2">Edit SL/TP</button>
                                    <button class="btn btn-sm btn-danger">Close</button>
                                </div>
                            </div>
                            
                            <!-- Trade Item 2 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">TATAMOTORS</span>
                                        <span class="trade-type sell">SELL</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="loss">-₹345.20</div>
                                        <small class="text-muted">-1.5%</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Open: </small>
                                            <small>₹450.75</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Current: </small>
                                            <small>₹456.30</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>10</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Date: </small>
                                            <small>09 Jun 2023</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button class="btn btn-sm btn-outline-secondary me-2">Edit SL/TP</button>
                                    <button class="btn btn-sm btn-danger">Close</button>
                                </div>
                            </div>
                            
                            <!-- Trade Item 3 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">HDFCBANK</span>
                                        <span class="trade-type buy">BUY</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="profit">+₹578.30</div>
                                        <small class="text-muted">+0.7%</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Open: </small>
                                            <small>₹1,670.20</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Current: </small>
                                            <small>₹1,678.50</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>3</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Date: </small>
                                            <small>08 Jun 2023</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button class="btn btn-sm btn-outline-secondary me-2">Edit SL/TP</button>
                                    <button class="btn btn-sm btn-danger">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Orders Tab -->
                    <div class="tab-pane fade" id="pendingOrders" role="tabpanel">
                        <div class="trade-list">
                            <!-- Pending Order 1 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">INFY</span>
                                        <span class="trade-type buy">BUY LIMIT</span>
                                    </div>
                                    <div class="text-end">
                                        <div>₹1,520.00</div>
                                        <small class="text-muted">Current: ₹1,545.30</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>5</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Date: </small>
                                            <small>10 Jun 2023</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">SL: </small>
                                            <small>₹1,500.00</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">TP: </small>
                                            <small>₹1,580.00</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button class="btn btn-sm btn-outline-secondary me-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Cancel</button>
                                </div>
                            </div>
                            
                            <!-- Pending Order 2 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">SBIN</span>
                                        <span class="trade-type sell">SELL STOP</span>
                                    </div>
                                    <div class="text-end">
                                        <div>₹580.00</div>
                                        <small class="text-muted">Current: ₹595.40</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>10</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Date: </small>
                                            <small>09 Jun 2023</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">SL: </small>
                                            <small>₹610.00</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">TP: </small>
                                            <small>₹550.00</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button class="btn btn-sm btn-outline-secondary me-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Closed Trades Tab -->
                    <div class="tab-pane fade" id="closedTrades" role="tabpanel">
                        <div class="trade-list">
                            <!-- Closed Trade 1 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">TCS</span>
                                        <span class="trade-type buy">BUY</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="profit">+₹1,850.00</div>
                                        <small class="text-muted">+3.2%</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Open: </small>
                                            <small>₹3,450.25</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Close: </small>
                                            <small>₹3,560.75</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>2</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Closed: </small>
                                            <small>05 Jun 2023</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Closed Trade 2 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">ICICIBANK</span>
                                        <span class="trade-type sell">SELL</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="loss">-₹420.50</div>
                                        <small class="text-muted">-0.8%</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Open: </small>
                                            <small>₹920.50</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Close: </small>
                                            <small>₹928.70</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>5</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Closed: </small>
                                            <small>04 Jun 2023</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Closed Trade 3 -->
                            <div class="trade-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="trade-symbol">WIPRO</span>
                                        <span class="trade-type buy">BUY</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="profit">+₹675.20</div>
                                        <small class="text-muted">+1.5%</small>
                                    </div>
                                </div>
                                <div class="trade-details mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Open: </small>
                                            <small>₹410.30</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Close: </small>
                                            <small>₹416.45</small>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <small class="text-muted">Qty: </small>
                                            <small>10</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted">Closed: </small>
                                            <small>02 Jun 2023</small>
                                        </div>
                                    </div>
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
        // Initialize tabs
        $('.trade-tabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection
