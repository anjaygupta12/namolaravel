@extends('layouts.user')

@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Watchlist</h2>
        <a href="#" class="link text-primary" data-bs-toggle="modal" data-bs-target="#addToWatchlistModal">
            <ion-icon name="add-outline"></ion-icon>
            Add
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#watchlist1" role="tab">
                        Watchlist 1
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#watchlist2" role="tab">
                        Watchlist 2
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#watchlist3" role="tab">
                        Watchlist 3
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-1">
                <!-- Watchlist 1 Tab -->
                <div class="tab-pane fade show active" id="watchlist1" role="tabpanel">
                    <div class="transactions">
                        <!-- Stock Item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>RELIANCE</strong>
                                    <p>NSE</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-success"> ₹2,456.75 </div>
                                <div class="price text-success"> +1.25% </div>
                            </div>
                            <div class="action-button dropdown">
                                <button type="button" class="btn btn-link btn-icon" data-bs-toggle="dropdown">
                                    <ion-icon name="ellipsis-horizontal"></ion-icon>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="create-outline"></ion-icon>Buy
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="arrow-down-outline"></ion-icon>Sell
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="trash-outline"></ion-icon>Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Stock Item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>TATAMOTORS</strong>
                                    <p>NSE</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-danger"> ₹456.30 </div>
                                <div class="price text-danger"> -0.75% </div>
                            </div>
                            <div class="action-button dropdown">
                                <button type="button" class="btn btn-link btn-icon" data-bs-toggle="dropdown">
                                    <ion-icon name="ellipsis-horizontal"></ion-icon>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="create-outline"></ion-icon>Buy
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="arrow-down-outline"></ion-icon>Sell
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="trash-outline"></ion-icon>Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Stock Item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>HDFCBANK</strong>
                                    <p>NSE</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-success"> ₹1,678.50 </div>
                                <div class="price text-success"> +0.45% </div>
                            </div>
                            <div class="action-button dropdown">
                                <button type="button" class="btn btn-link btn-icon" data-bs-toggle="dropdown">
                                    <ion-icon name="ellipsis-horizontal"></ion-icon>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="create-outline"></ion-icon>Buy
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="arrow-down-outline"></ion-icon>Sell
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="trash-outline"></ion-icon>Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Watchlist 2 Tab -->
                <div class="tab-pane fade" id="watchlist2" role="tabpanel">
                    <div class="transactions">
                        <!-- Stock Item -->
                        <div class="item">
                            <div class="detail">
                                <div>
                                    <strong>TCS</strong>
                                    <p>NSE</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-success"> ₹3,540.50 </div>
                                <div class="price text-success"> +0.85% </div>
                            </div>
                            <div class="action-button dropdown">
                                <button type="button" class="btn btn-link btn-icon" data-bs-toggle="dropdown">
                                    <ion-icon name="ellipsis-horizontal"></ion-icon>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="create-outline"></ion-icon>Buy
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="arrow-down-outline"></ion-icon>Sell
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <ion-icon name="trash-outline"></ion-icon>Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Watchlist 3 Tab -->
                <div class="tab-pane fade" id="watchlist3" role="tabpanel">
                    <div class="transactions">
                        <div class="empty-state">
                            <div class="icon-box text-primary">
                                <ion-icon name="star-outline"></ion-icon>
                            </div>
                            <h4 class="title">No stocks added yet</h4>
                            <p class="text-muted">
                                Add stocks to your watchlist to track their performance
                            </p>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addToWatchlistModal">
                                <ion-icon name="add-outline"></ion-icon>
                                Add Stocks
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add to Watchlist Modal -->
<div class="modal fade" id="addToWatchlistModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add to Watchlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="watchlistSelect">Select Watchlist</label>
                        <select class="form-control custom-select" id="watchlistSelect">
                            <option value="1">Watchlist 1</option>
                            <option value="2">Watchlist 2</option>
                            <option value="3">Watchlist 3</option>
                        </select>
                    </div>
                </div>
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="stockSearch">Search Stock</label>
                        <input type="text" class="form-control" id="stockSearch" placeholder="Enter stock symbol or name">
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="search-results mt-2">
                    <div class="item">
                        <div class="detail">
                            <div>
                                <strong>INFY</strong>
                                <p>Infosys Ltd. - NSE</p>
                            </div>
                        </div>
                        <div class="right">
                            <button class="btn btn-sm btn-primary">Add</button>
                        </div>
                    </div>
                    <div class="item">
                        <div class="detail">
                            <div>
                                <strong>WIPRO</strong>
                                <p>Wipro Ltd. - NSE</p>
                            </div>
                        </div>
                        <div class="right">
                            <button class="btn btn-sm btn-primary">Add</button>
                        </div>
                    </div>
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
        // Tab handling
        $('.nav-tabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        // Stock search functionality
        $('#stockSearch').on('keyup', function() {
            // Implement search functionality here
            // This is just a placeholder for demonstration
            var searchTerm = $(this).val().toLowerCase();
            $('.search-results .item').each(function() {
                var stockName = $(this).find('strong').text().toLowerCase();
                var stockDesc = $(this).find('p').text().toLowerCase();
                if (stockName.indexOf(searchTerm) > -1 || stockDesc.indexOf(searchTerm) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection
