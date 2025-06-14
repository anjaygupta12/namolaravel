@extends('layouts.user')

@section('styles')
<style>
    .portfolio-summary {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .portfolio-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }
    .portfolio-item:last-child {
        border-bottom: none;
    }
    .profit {
        color: #28a745;
    }
    .loss {
        color: #dc3545;
    }
    .portfolio-symbol {
        font-weight: bold;
        font-size: 1rem;
    }
    .portfolio-details {
        font-size: 0.85rem;
    }
    .chart-container {
        height: 200px;
        margin-bottom: 20px;
    }
    .allocation-chart {
        height: 150px;
        width: 150px;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Portfolio Summary</h2>
                
                <div class="portfolio-summary">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="mb-0">₹2,45,678.50</h3>
                            <p class="text-muted">Total Value</p>
                        </div>
                        <div class="col-6 text-end">
                            <h3 class="profit mb-0">+₹12,450.75</h3>
                            <p class="text-muted">Overall P/L (+5.3%)</p>
                        </div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="portfolioChart"></canvas>
                </div>
                
                <div class="section-title">Asset Allocation</div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="allocation-chart">
                            <canvas id="allocationChart"></canvas>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="allocation-legend">
                            <div class="d-flex align-items-center mb-1">
                                <span class="dot bg-primary me-2"></span>
                                <span>Equities (65%)</span>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <span class="dot bg-success me-2"></span>
                                <span>Commodities (20%)</span>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <span class="dot bg-warning me-2"></span>
                                <span>Forex (10%)</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="dot bg-danger me-2"></span>
                                <span>Cash (5%)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="section-title">Holdings</div>
                <div class="portfolio-list">
                    <!-- Portfolio Item 1 -->
                    <div class="portfolio-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="portfolio-symbol">RELIANCE</span>
                                <small class="text-muted">NSE</small>
                            </div>
                            <div class="text-end">
                                <div class="profit">+₹6,225.50</div>
                                <small class="text-muted">+5.1%</small>
                            </div>
                        </div>
                        <div class="portfolio-details mt-1">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Qty: </small>
                                    <small>5</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Avg Price: </small>
                                    <small>₹2,430.25</small>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <small class="text-muted">Current: </small>
                                    <small>₹2,456.75</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Value: </small>
                                    <small>₹12,283.75</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Portfolio Item 2 -->
                    <div class="portfolio-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="portfolio-symbol">HDFCBANK</span>
                                <small class="text-muted">NSE</small>
                            </div>
                            <div class="text-end">
                                <div class="profit">+₹1,734.90</div>
                                <small class="text-muted">+2.1%</small>
                            </div>
                        </div>
                        <div class="portfolio-details mt-1">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Qty: </small>
                                    <small>3</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Avg Price: </small>
                                    <small>₹1,670.20</small>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <small class="text-muted">Current: </small>
                                    <small>₹1,678.50</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Value: </small>
                                    <small>₹5,035.50</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Portfolio Item 3 -->
                    <div class="portfolio-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="portfolio-symbol">GOLD</span>
                                <small class="text-muted">MCX</small>
                            </div>
                            <div class="text-end">
                                <div class="profit">+₹4,095.00</div>
                                <small class="text-muted">+3.5%</small>
                            </div>
                        </div>
                        <div class="portfolio-details mt-1">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Qty: </small>
                                    <small>2</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Avg Price: </small>
                                    <small>₹56,450.00</small>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <small class="text-muted">Current: </small>
                                    <small>₹58,450.00</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Value: </small>
                                    <small>₹116,900.00</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Portfolio Item 4 -->
                    <div class="portfolio-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="portfolio-symbol">TCS</span>
                                <small class="text-muted">NSE</small>
                            </div>
                            <div class="text-end">
                                <div class="loss">-₹1,240.50</div>
                                <small class="text-muted">-1.2%</small>
                            </div>
                        </div>
                        <div class="portfolio-details mt-1">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Qty: </small>
                                    <small>2</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Avg Price: </small>
                                    <small>₹3,560.75</small>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <small class="text-muted">Current: </small>
                                    <small>₹3,540.50</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Value: </small>
                                    <small>₹7,081.00</small>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Portfolio Performance Chart
        var ctx = document.getElementById('portfolioChart').getContext('2d');
        var portfolioChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Portfolio Value',
                    data: [210000, 215000, 225000, 218000, 230000, 245678],
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Asset Allocation Chart
        var allocationCtx = document.getElementById('allocationChart').getContext('2d');
        var allocationChart = new Chart(allocationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Equities', 'Commodities', 'Forex', 'Cash'],
                datasets: [{
                    data: [65, 20, 10, 5],
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
