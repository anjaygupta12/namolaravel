@extends('layouts.user')

@section('styles')
<style>
    .card-block {
        padding: 15px;
    }
    .market-tabs .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
    .search-box {
        margin-bottom: 15px;
    }
    .stock-item {
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }
    .stock-item:last-child {
        border-bottom: none;
    }
    .price-up {
        color: #28a745;
    }
    .price-down {
        color: #dc3545;
    }
</style>
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="card">
            <div class="card-body">
                <div class="search-box">
                    <form>
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <input type="text" class="form-control" id="searchStocks" placeholder="Search stocks...">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                    </form>
                </div>

                <ul class="nav nav-tabs market-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#nse" role="tab">
                            NSE
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#bse" role="tab">
                            BSE
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#mcx" role="tab">
                            MCX
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#forex" role="tab">
                            FOREX
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-1">
                    <!-- NSE Tab -->
                    <div class="tab-pane fade show active" id="nse" role="tabpanel">
                        <div class="stock-list">
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>RELIANCE</strong>
                                    <p class="text-muted mb-0">NSE</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-up">₹2,456.75 <i class="fas fa-arrow-up"></i></div>
                                    <small class="text-muted">+1.2%</small>
                                </div>
                            </div>
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>TATAMOTORS</strong>
                                    <p class="text-muted mb-0">NSE</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-down">₹456.30 <i class="fas fa-arrow-down"></i></div>
                                    <small class="text-muted">-0.8%</small>
                                </div>
                            </div>
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>HDFCBANK</strong>
                                    <p class="text-muted mb-0">NSE</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-up">₹1,678.50 <i class="fas fa-arrow-up"></i></div>
                                    <small class="text-muted">+0.5%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- BSE Tab -->
                    <div class="tab-pane fade" id="bse" role="tabpanel">
                        <div class="stock-list">
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>RELIANCE</strong>
                                    <p class="text-muted mb-0">BSE</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-up">₹2,455.80 <i class="fas fa-arrow-up"></i></div>
                                    <small class="text-muted">+1.1%</small>
                                </div>
                            </div>
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>TATAMOTORS</strong>
                                    <p class="text-muted mb-0">BSE</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-down">₹455.90 <i class="fas fa-arrow-down"></i></div>
                                    <small class="text-muted">-0.9%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- MCX Tab -->
                    <div class="tab-pane fade" id="mcx" role="tabpanel">
                        <div class="stock-list">
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>GOLD</strong>
                                    <p class="text-muted mb-0">MCX</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-up">₹58,450 <i class="fas fa-arrow-up"></i></div>
                                    <small class="text-muted">+0.7%</small>
                                </div>
                            </div>
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>SILVER</strong>
                                    <p class="text-muted mb-0">MCX</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-up">₹72,345 <i class="fas fa-arrow-up"></i></div>
                                    <small class="text-muted">+1.3%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FOREX Tab -->
                    <div class="tab-pane fade" id="forex" role="tabpanel">
                        <div class="stock-list">
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>USD/INR</strong>
                                    <p class="text-muted mb-0">FOREX</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-down">₹82.45 <i class="fas fa-arrow-down"></i></div>
                                    <small class="text-muted">-0.2%</small>
                                </div>
                            </div>
                            <div class="stock-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>EUR/INR</strong>
                                    <p class="text-muted mb-0">FOREX</p>
                                </div>
                                <div class="text-end">
                                    <div class="price-up">₹90.78 <i class="fas fa-arrow-up"></i></div>
                                    <small class="text-muted">+0.3%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Sheet -->
    <div class="modal fade action-sheet" id="actionSheet" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RELIANCE</h5>
                    <a href="javascript:;" data-bs-dismiss="modal" class="close">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h3 class="mb-0">₹2,456.75</h3>
                                <span class="text-success">+1.2% (₹29.45)</span>
                            </div>
                            <div>
                                <button class="btn btn-success">BUY</button>
                                <button class="btn btn-danger">SELL</button>
                            </div>
                        </div>
                        
                        <div class="divider bg-secondary mt-2 mb-2"></div>
                        
                        <div class="stock-details">
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted">Open</p>
                                    <p>₹2,430.50</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted">Close</p>
                                    <p>₹2,427.30</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted">High</p>
                                    <p>₹2,460.75</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted">Low</p>
                                    <p>₹2,425.10</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted">Volume</p>
                                    <p>3.2M</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted">P/E Ratio</p>
                                    <p>24.5</p>
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
        // Stock item click to show action sheet
        $('.stock-item').on('click', function() {
            $('#actionSheet').modal('show');
        });
        
        // Search functionality
        $('#searchStocks').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.stock-item').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
@endsection
