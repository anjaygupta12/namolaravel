@extends('layouts.admin')

@section('title', 'Market Watch')

@section('content')
<div class="container">
    
    <div id="ws-status" class="status disconnected" style="display:none;">
        WebSocket Status: Disconnected
    </div>
    
    <!-- <div class="controls">
        <button id="connect-btn">Connect</button>
        <button id="disconnect-btn">Disconnect</button>
        <button id="clear-btn">Clear Data</button>
        <input type="text" id="search-box" class="search-box" placeholder="Search symbols...">
    </div>
    
    <div class="stats">
        <div class="stats-item">Total Symbols: <span id="total-symbols">0</span></div>
        <div class="stats-item">NSE: <span id="nse-symbols">0</span></div>
        <div class="stats-item">BSE: <span id="bse-symbols">0</span></div>
        <div class="stats-item">MCX: <span id="mcx-symbols">0</span></div>
    </div> -->
    
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs market-tabs" id="marketTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="mcx-tab" data-toggle="tab" href="#mcx-content" role="tab" aria-controls="mcx-content" aria-selected="false">MCX</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="nse-tab" data-toggle="tab" href="#nse-content" role="tab" aria-controls="nse-content" aria-selected="true">NSE</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="bse-tab" data-toggle="tab" href="#bse-content" role="tab" aria-controls="bse-content" aria-selected="false">Option</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="forex-tab" data-toggle="tab" href="#forex-content" role="tab" aria-controls="forex-content" aria-selected="false">Forex & Crypto</a>
        </li>
    </ul>
    
    <!-- Full-width Search Bar -->
    <div class="search-container mb-3" style="display: none;">
        <input type="text" id="search-box" class="form-control form-control-lg" placeholder="Search symbols..." />
    </div>
    
    <!-- Tabs Content -->
    <div class="tab-content" id="marketTabsContent">

            <!-- MCX Tab -->
        <div class="tab-pane fade" id="mcx-content" role="tabpanel" aria-labelledby="mcx-tab">
            <div class="table-container">
                <table class="market-data-table">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Short Name</th>
                            <th>Expiry Date</th>
                            <th>LTP</th>
                            <th>Bid Price</th>
                            <th>Ask Price</th>
                            <th>Change</th>
                            <th>Change %</th>
                            <th>Volume</th>
                            <th>Open</th>
                        </tr>
                    </thead>
                    <tbody id="mcx-data-body">
                        <!-- MCX market data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- NSE Tab -->
        <div class="tab-pane fade show active" id="nse-content" role="tabpanel" aria-labelledby="nse-tab">
            <div class="table-container">
                <table class="market-data-table">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Short Name</th>
                            <th>Expiry Date</th>
                            <th>LTP</th>
                            <th>Bid Price</th>
                            <th>Ask Price</th>
                            <th>Change</th>
                            <th>Change %</th>
                            <th>Volume</th>
                            <th>Open</th>
                        </tr>
                    </thead>
                    <tbody id="nse-data-body">
                        <!-- NSE market data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
        
       
        
    

         <!-- BSE Tab -->
        <div class="tab-pane fade" id="bse-content" role="tabpanel" aria-labelledby="bse-tab">
            <div class="table-container">
                <table class="market-data-table">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Short Name</th>
                            <th>Expiry Date</th>
                            <th>LTP</th>
                            <th>Bid Price</th>
                            <th>Ask Price</th>
                            <th>Change</th>
                            <th>Change %</th>
                            <th>Volume</th>
                            <th>Open</th>
                        </tr>
                    </thead>
                    <tbody id="bse-data-body">
                        <!-- BSE market data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>


         <!-- Forex & Crypto Tab -->
        <div class="tab-pane fade" id="forex-content" role="tabpanel" aria-labelledby="forex-tab">
            <div class="table-container">
                <table class="market-data-table">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Short Name</th>
                            <th>Expiry Date</th>
                            <th>LTP</th>
                            <th>Bid Price</th>
                            <th>Ask Price</th>
                            <th>Change</th>
                            <th>Change %</th>
                            <th>Volume</th>
                            <th>Open</th>
                        </tr>
                    </thead>
                    <tbody id="forex-data-body">
                        <!-- BSE market data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Search Results Action Sheet -->
<div class="modal fade action-sheet fullscreen-modal" id="searchActionSheet" tabindex="-1" role="dialog">
    <div class="modal-dialog fullscreen-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="close-search-sheet" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="search-bar-container">
                <input type="text" id="modal-search-box" class="form-control form-control-lg" placeholder="Search symbols..." />
                <div class="active-tab-indicator">Active Tab: <span id="active-tab-name">NSE</span></div>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <div class="search-results-container">
                        <table class="table search-results-table">
                            <tbody id="search-results-body">
                                <!-- Search results will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buy/Sell Action Sheet -->
<div class="modal fade action-sheet" id="buyActionSheet" tabindex="-1" role="dialog">
    <div class="modal-dialog action-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selected-symbol">Symbol</h5>
                <button type="button" class="close" id="close-action-sheet" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="price-card">
                                <p class="text-muted">Bid Price</p>
                                <h3 id="bid-price-value" class="text-success">0.00</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="price-card">
                                <p class="text-muted">Ask Price</p>
                                <h3 id="ask-price-value" class="text-danger">0.00</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label">Quantity</label>
                            <input type="number" class="form-control" id="trade-quantity" value="1">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-success btn-lg btn-block" id="buy-button">BUY</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger btn-lg btn-block" id="sell-button">SELL</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 4px;
    }
    .connected {
        background-color: #d4edda;
        color: #155724;
    }
    .disconnected {
        background-color: #f8d7da;
        color: #721c24;
    }
    .error {
        background-color: #f8d7da;
        color: #721c24;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }
    th {
        background-color: #4CAF50;
        color: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    .mcx-symbol {
        background-color: #fff3cd;
    }
    .nse-symbol {
        background-color: #e3f2fd;
    }
    .bse-symbol {
        background-color: #e8f5e9;
    }
    .zero-value {
        color: red;
        font-weight: bold;
    }
    .controls {
        margin: 20px 0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    button {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
    .filter-btn {
        background-color: #e9ecef;
        color: #333;
    }
    .filter-btn.active {
        background-color: #4CAF50;
        color: white;
    }
    .positive-change {
        color: green;
        font-weight: bold;
    }
    .negative-change {
        color: red;
        font-weight: bold;
    }
    .search-box {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 200px;
    }
    .filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: 10px;
    }
    .filter-label {
        font-weight: bold;
    }
    .stats {
        margin: 10px 0;
        padding: 10px;
        background-color: #e9ecef;
        border-radius: 4px;
        display: flex;
        gap: 20px;
    }
    .stats-item {
        font-weight: bold;
    }
    .table-container {
        max-height: 600px;
        overflow-y: auto;
    }
    
    /* Action Sheet Styles */
    .action-sheet {
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    .action-sheet.show {
        transform: translateY(0);
    }
    .modal-dialog.action-sheet-modal {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        margin: 0;
        width: 100%;
        border-radius: 15px 15px 0 0;
    }
    .price-card {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    /* Search Results Styling */
    .fullscreen-modal {
        padding: 0 !important;
    }
    
    .fullscreen-dialog {
        max-width: 100%;
        width: 100%;
        height: 100%;
        margin: 0;
    }
    
    .fullscreen-dialog .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0;
    }
    
    .search-bar-container {
        padding: 15px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }
    
    .active-tab-indicator {
        margin-top: 10px;
        font-size: 14px;
        color: #666;
    }
    
    .search-results-container {
        max-height: calc(100vh - 180px);
        overflow-y: auto;
    }
    
    .search-results-table tr {
        border-bottom: 1px solid #eee;
    }
    
    .list_cntnt {
        padding: 5px 0;
    }
    
    .list_cntnt .title {
        font-weight: bold;
        margin-bottom: 2px;
        font-size: 14px;
    }
    
    .list_cntnt .id {
        color: #666;
        font-size: 12px;
        margin-bottom: 2px;
    }
    
    .list_cntnt .chg {
        color: #666;
        font-size: 12px;
        margin-bottom: 0;
    }
    
    .list_cntnt .title_number {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 2px;
        color: #333;
    }
    
    .check_box {
        margin-right: 5px;
    }
    
    .check_mark {
        display: inline-block;
        width: 18px;
        height: 18px;
        vertical-align: middle;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Bootstrap tabs
        $('#marketTabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        // Fix for Bootstrap 4 tab functionality
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // Update tables when tab is shown
            updateMarketDataTables();
            
            // Track active tab
            const activeTabId = $(e.target).attr('id');
            let activeTabName = 'NSE';
            
            if (activeTabId === 'nse-tab') {
                activeTabName = 'NSE';
            } else if (activeTabId === 'bse-tab') {
                activeTabName = 'Option';
            } else if (activeTabId === 'mcx-tab') {
                activeTabName = 'MCX';
            } else if (activeTabId === 'forex-tab') {
                activeTabName = 'Forex & Crypto';
            }
            
            // Store active tab name in a global variable
            window.activeTab = activeTabName;
            
            // Update active tab name in search modal if it's open
            if (document.getElementById('active-tab-name')) {
                document.getElementById('active-tab-name').textContent = activeTabName;
            }
        });
        
        // Set initial active tab
        window.activeTab = 'NSE';
        
        // Bid/Ask WebSocket Functionality
        const statusElement = document.getElementById('ws-status');
        const nseDataBody = document.getElementById('nse-data-body');
        const bseDataBody = document.getElementById('bse-data-body');
        const mcxDataBody = document.getElementById('mcx-data-body');
        const forexDataBody = document.getElementById('forex-data-body');
        const connectBtn = document.getElementById('connect-btn');
        const disconnectBtn = document.getElementById('disconnect-btn');
        const clearBtn = document.getElementById('clear-btn');
        const searchBox = document.getElementById('search-box');
        
        let socket;
        let marketData = {};
        let searchTerm = '';
        
        // Connect to WebSocket
        function connect() {
            try {
                // Use the Node.js server WebSocket URL with explicit IP address
                const wsUrl = 'ws://namotraders.in:5005/ws?isAdmin=true';
                console.log('Attempting to connect to WebSocket at:', wsUrl);
                statusElement.textContent = 'Connecting to WebSocket...';
                
                // Close existing socket if it exists
                if (socket) {
                    console.log('Closing existing socket connection');
                    socket.close();
                }
                
                // Create new WebSocket connection
                socket = new WebSocket(wsUrl);
                
                socket.onopen = function() {
                    console.log('WebSocket connection established successfully');
                    statusElement.textContent = 'Connected to WebSocket';
                    statusElement.className = 'status connected';
                   
                };
                
                socket.onclose = function(event) {
                    console.log('WebSocket connection closed. Code:', event.code, 'Reason:', event.reason);
                    statusElement.textContent = 'Disconnected from WebSocket';
                    statusElement.className = 'status disconnected';
                   
                };
                
                socket.onerror = function(error) {
                    console.error('WebSocket error:', error);
                    statusElement.textContent = 'WebSocket connection error';
                    statusElement.className = 'status error';
                   
                };
                
                socket.onmessage = function(event) {
                    try {
                        console.log('%c WebSocket message received', 'background: #222; color: #bada55; font-size: 16px; font-weight: bold;');
                        
                        // Log the raw message data immediately
                        console.log('Raw WebSocket message:', event.data);
                        
                        // Initialize a debug counter if it doesn't exist
                        if (!window.messageCounter) window.messageCounter = 0;
                        window.messageCounter++;
                        
                        // Log every 10th message count for tracking
                        if (window.messageCounter % 10 === 0) {
                            console.log(`Received ${window.messageCounter} total WebSocket messages`);
                        }
                        
                        // Parse the JSON data
                        const data = JSON.parse(event.data);
                        
                        // Check if data is an array (as sent by the server)
                        if (Array.isArray(data)) {
                            console.log('Received array data with', data.length, 'items');
                            // Process each item in the array
                            data.forEach(function(item) {
                                if (item && item.Data) {
                                    // Always log data for debugging - regardless of whether rawData exists
                                    console.log('%c === FYERS DATA FOR ' + item.name + ' ===', 'background: #0066cc; color: white; font-size: 14px; font-weight: bold; padding: 5px;');
                                    
                                    // Log the complete item data
                                    console.log('%c Complete item:', 'color: green; font-weight: bold;');
                                    console.dir(item);
                                    
                                    // Check if rawData exists and log it
                                    if (item.Data && item.Data.rawData) {
                                        console.log('%c Raw Fyers Data:', 'color: blue; font-weight: bold;');
                                        console.dir(item.Data.rawData);
                                        
                                        // Add a debug counter to track how many messages we're receiving
                                        if (!window.fyersMessageCount) window.fyersMessageCount = 0;
                                        window.fyersMessageCount++;
                                        
                                        if (window.fyersMessageCount % 10 === 0) {
                                            console.log('%c Received ' + window.fyersMessageCount + ' Fyers messages with raw data', 'color: purple; font-weight: bold;');
                                        }
                                    } else {
                                        console.warn('No rawData found in the message for symbol:', item.name);
                                    }
                                    
                                    console.log('%c ===========================', 'background: #0066cc; color: white; font-size: 14px; font-weight: bold; padding: 5px;');
                                    
                                    // Store or update market data
                                    const symbol = item.name;
                                    
                                    // Format expiry date if it exists
                                    let formattedExpiryDate = '';
                                    if (item.Data.expiryDate) {
                                        const expiryDate = new Date(item.Data.expiryDate);
                                        if (!isNaN(expiryDate)) {
                                            const day = expiryDate.getDate().toString().padStart(2, '0');
                                            const month = (expiryDate.getMonth() + 1).toString().padStart(2, '0');
                                            const year = expiryDate.getFullYear();
                                            formattedExpiryDate = `${day}-${month}-${year}`;
                                        }
                                    }
                                    
                                    // Create market data object with all available fields
                                    marketData[symbol] = {
                                        symbol: symbol,
                                        symbolShortName: item.Data.symbolShortName || '',
                                        expiryDate: formattedExpiryDate,
                                        ltp: item.Data.ltp || 0,
                                        bid_price: item.Data.bid_price || 0,
                                        ask_price: item.Data.ask_price || 0,
                                        vol_traded_today: item.Data.vol_traded_today || 0,
                                        open_price: item.Data.open_price || 0,
                                        high_price: item.Data.high_price || 0,
                                        low_price: item.Data.low_price || 0,
                                        ch: item.Data.ch || 0,
                                        chp: item.Data.chp || 0,
                                        // Store all raw data fields for potential use
                                        rawData: item.Data.rawData
                                    };
                                }
                            });
                            
                            // Update the tables with the new data
                            updateMarketDataTables();
                        } else {
                            console.log('Received non-array data:', data);
                        }
                    } catch (error) {
                        console.error('Error parsing message:', error);
                    }
                };
            } catch (error) {
                console.error('Error setting up WebSocket:', error);
                statusElement.textContent = 'Error setting up WebSocket';
                statusElement.className = 'status error';
                connectBtn.disabled = false;
                disconnectBtn.disabled = true;
            }
        }
        
        // Filter market data based on search term and exchange
        function filterMarketData(exchange) {
            return Object.entries(marketData).filter(function(entry) {
                const symbol = entry[0];
                const data = entry[1];
                
                // Apply search filter if there is a search term
                if (searchTerm && !symbol.toLowerCase().includes(searchTerm.toLowerCase())) {
                    return false;
                }
                
                // Filter by exchange
                if (exchange === 'nse') {
                    return symbol.startsWith('NSE:');
                } else if (exchange === 'bse') {
                    return symbol.startsWith('BSE:');
                } else if (exchange === 'mcx') {
                    return symbol.startsWith('MCX:');
                } else if (exchange === 'forex') {
                    return symbol.startsWith('FOREX:') || symbol.startsWith('CRYPTO:');
                }
                
                return false; // Should not reach here
            });
        }
        
        // Update all market data tables
        function updateMarketDataTables() {
            // Clear all tables
            nseDataBody.innerHTML = '';
            bseDataBody.innerHTML = '';
            mcxDataBody.innerHTML = '';
            forexDataBody.innerHTML = '';
            
            // Update NSE table
            const nseData = filterMarketData('nse');
            nseData.sort(function(a, b) {
                return a[0].localeCompare(b[0]);
            });
            
            nseData.forEach(function(entry) {
                const symbol = entry[0];
                const data = entry[1];
                const row = createTableRow(symbol, data);
                nseDataBody.appendChild(row);
            });
            
            // Update BSE table (Option)
            const bseData = filterMarketData('bse');
            bseData.sort(function(a, b) {
                return a[0].localeCompare(b[0]);
            });
            
            bseData.forEach(function(entry) {
                const symbol = entry[0];
                const data = entry[1];
                const row = createTableRow(symbol, data);
                bseDataBody.appendChild(row);
            });
            
            // Update MCX table
            const mcxData = filterMarketData('mcx');
            mcxData.sort(function(a, b) {
                return a[0].localeCompare(b[0]);
            });
            
            mcxData.forEach(function(entry) {
                const symbol = entry[0];
                const data = entry[1];
                const row = createTableRow(symbol, data);
                mcxDataBody.appendChild(row);
            });
            
            // Update Forex & Crypto table
            const forexData = filterMarketData('forex');
            forexData.sort(function(a, b) {
                return a[0].localeCompare(b[0]);
            });
            
            forexData.forEach(function(entry) {
                const symbol = entry[0];
                const data = entry[1];
                const row = createTableRow(symbol, data);
                forexDataBody.appendChild(row);
            });
        }
        
        // Create a table row for market data
        function createTableRow(symbol, data) {
            const row = document.createElement('tr');
            
            // Add click event to show buy/sell action sheet
            row.addEventListener('click', function() {
                showBuySellActionSheet(symbol, data);
            });
            
            // Add cursor pointer style
            row.style.cursor = 'pointer';
                
            // Create table cells using standard DOM methods
            const cells = [
                createCell(symbol),
                createCell(data.symbolShortName || ''),
                createCell(data.expiryDate || ''),
                createCell(data.ltp || 0),
                createCell(data.bid_price || 0, data.bid_price == 0 ? 'zero-value' : ''),
                createCell(data.ask_price || 0, data.ask_price == 0 ? 'zero-value' : ''),
                createCell(data.ch || 0, parseFloat(data.ch || 0) > 0 ? 'positive-change' : parseFloat(data.ch || 0) < 0 ? 'negative-change' : ''),
                createCell((data.chp || 0) + '%', parseFloat(data.chp || 0) > 0 ? 'positive-change' : parseFloat(data.chp || 0) < 0 ? 'negative-change' : ''),
                createCell(data.vol_traded_today || 0),
                createCell(data.open_price || 0)
            ];
            
            cells.forEach(function(cell) {
                row.appendChild(cell);
            });
            
            return row;
        }
        
        // Show buy/sell action sheet for the selected symbol
        function showBuySellActionSheet(symbol, data) {
            // Update action sheet with symbol data
            document.getElementById('selected-symbol').textContent = symbol;
            document.getElementById('bid-price-value').textContent = data.bid_price || '0.00';
            document.getElementById('ask-price-value').textContent = data.ask_price || '0.00';
            
            // Show the action sheet
            $('#buyActionSheet').modal('show');
        }
        
        // Helper function to create table cell
        function createCell(content, className) {
            const cell = document.createElement('td');
            cell.textContent = content;
            if (className) {
                cell.className = className;
            }
            return cell;
        }
        
        // Helper functions for market data
        

        
        // Event listeners
        // connectBtn.addEventListener('click', function() {
        //     if (!socket || socket.readyState !== WebSocket.OPEN) {
        //         connect();
        //     }
        // });
        
        // disconnectBtn.addEventListener('click', function() {
        //     if (socket && socket.readyState === WebSocket.OPEN) {
        //         socket.close();
        //     }
        // });
        
        // clearBtn.addEventListener('click', function() {
        //     marketData = {};
        //     updateMarketDataTables();
        //     updateStats();
        // });
        
        // searchBox.addEventListener('input', function(e) {
        //     searchTerm = e.target.value;
        //     updateMarketDataTables();
        // });
        
        // Auto-connect when the page loads
        connect();
        
        // Handle buy button click
        document.getElementById('buy-button').addEventListener('click', function() {
            const symbol = document.getElementById('selected-symbol').textContent;
            const quantity = document.getElementById('trade-quantity').value;
            const price = document.getElementById('ask-price-value').textContent;
            
            // Here you would typically send the buy order to your backend
            console.log('BUY Order:', { symbol, quantity, price });
            
            // Show confirmation
            alert('Buy order placed for ' + quantity + ' ' + symbol + ' at price ' + price);
            
            // Close the action sheet
            $('#buyActionSheet').modal('hide');
        });
        
        // Handle sell button click
        document.getElementById('sell-button').addEventListener('click', function() {
            const symbol = document.getElementById('selected-symbol').textContent;
            const quantity = document.getElementById('trade-quantity').value;
            const price = document.getElementById('bid-price-value').textContent;
            
            // Here you would typically send the sell order to your backend
            console.log('SELL Order:', { symbol, quantity, price });
            
            // Show confirmation
            alert('Sell order placed for ' + quantity + ' ' + symbol + ' at price ' + price);
            
            // Close the action sheet
            $('#buyActionSheet').modal('hide');
        });
        
        // Handle close button click
        document.getElementById('close-action-sheet').addEventListener('click', function() {
            $('#buyActionSheet').modal('hide');
        });
        
        // Handle search close button click
        document.getElementById('close-search-sheet').addEventListener('click', function() {
            $('#searchActionSheet').modal('hide');
        });
        
        // Handle main search box click to open fullscreen search
        searchBox.addEventListener('click', function(e) {
            // Show the search action sheet
            $('#searchActionSheet').modal('show');
            
            // Clear the modal search box
            document.getElementById('modal-search-box').value = '';
            
            // Set focus on the modal search box
            setTimeout(() => {
                document.getElementById('modal-search-box').focus();
                
                // Update active tab name in search modal
                document.getElementById('active-tab-name').textContent = window.activeTab || 'NSE';
                
                // Show initial sample data for the active tab
                showSearchResults('');
            }, 300);
        });
        
        // Handle modal search input
        document.getElementById('modal-search-box').addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim();
            if (searchTerm.length >= 2) {
                // In a real implementation, you would fetch data from your API here
                // For now, we'll use sample data
                showSearchResults(searchTerm);
            } else {
                // Clear results but keep modal open
                document.getElementById('search-results-body').innerHTML = '';
            }
        });
        
        // Show search results
        function showSearchResults(searchTerm) {
            const searchResultsBody = document.getElementById('search-results-body');
            searchResultsBody.innerHTML = '';
            
            // Get current active tab
            const activeTab = window.activeTab || 'NSE';
            
            // Sample data - in a real implementation, this would come from your API based on active tab
            const sampleResults = [
                {
                    scriptName: 'NSE:RELIANCE',
                    timestamp: '22-06-2025',
                    lotsize: '100',
                    Yhigh: '2750.50',
                    Ylow: '2720.25',
                    bid_price: '2735.60',
                    ask_price: '2736.20',
                    open_price: '2725.00',
                    scriptId: 'NSE_RELIANCE',
                    marketType: 'NSE',
                    isActive: true
                },
                {
                    scriptName: 'NSE:TATASTEEL',
                    timestamp: '22-06-2025',
                    lotsize: '250',
                    Yhigh: '185.75',
                    Ylow: '182.30',
                    bid_price: '183.40',
                    ask_price: '183.55',
                    open_price: '184.20',
                    scriptId: 'NSE_TATASTEEL',
                    marketType: 'NSE',
                    isActive: false
                },
                {
                    scriptName: 'BSE:INFY',
                    timestamp: '22-06-2025',
                    lotsize: '50',
                    Yhigh: '1650.25',
                    Ylow: '1630.80',
                    bid_price: '1642.50',
                    ask_price: '1643.10',
                    open_price: '1635.00',
                    scriptId: 'BSE_INFY',
                    marketType: 'BSE',
                    isActive: true
                },
                {
                    scriptName: 'MCX:GOLD',
                    timestamp: '22-06-2025',
                    lotsize: '1',
                    Yhigh: '72450.00',
                    Ylow: '72100.00',
                    bid_price: '72350.00',
                    ask_price: '72375.00',
                    open_price: '72200.00',
                    scriptId: 'MCX_GOLD',
                    marketType: 'MCX',
                    isActive: false
                },
                {
                    scriptName: 'FOREX:USDINR',
                    timestamp: '22-06-2025',
                    lotsize: '1000',
                    Yhigh: '83.25',
                    Ylow: '83.05',
                    bid_price: '83.15',
                    ask_price: '83.18',
                    open_price: '83.10',
                    scriptId: 'FOREX_USDINR',
                    marketType: 'FOREX',
                    isActive: true
                }
            ];
            
            // Filter results based on active tab first
            let filteredResults = sampleResults.filter(item => {
                // Filter by active tab
                if (activeTab === 'NSE' && item.marketType === 'NSE') {
                    return true;
                } else if (activeTab === 'Option' && item.marketType === 'BSE') {
                    return true;
                } else if (activeTab === 'MCX' && item.marketType === 'MCX') {
                    return true;
                } else if (activeTab === 'Forex & Crypto' && 
                          (item.marketType === 'FOREX' || item.scriptName.startsWith('FOREX:') || 
                           item.marketType === 'CRYPTO' || item.scriptName.startsWith('CRYPTO:'))) {
                    return true;
                }
                return false;
            });
            
            // Then filter by search term if not empty
            if (searchTerm.trim() !== '') {
                filteredResults = filteredResults.filter(item => 
                    item.scriptName.toLowerCase().includes(searchTerm.toLowerCase()));
            }
            
            // Create HTML for each result
            filteredResults.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="list_cntnt">
                            <p class="title">${item.scriptName}</p>
                            <p class="id">${item.timestamp}</p>
                            <p class="chg">Lot Size: ${item.lotsize}</p>
                        </div>
                    </td>
                    <td>
                        <div class="list_cntnt">
                            <p class="chg">H:<span id="search_${item.scriptName}_High">${item.Yhigh}</span></p>
                        </div>
                    </td>
                    <td>
                        <div class="list_cntnt">
                            <p class="title_number" id="search_${item.scriptName}_Bid">${item.bid_price}</p>
                            <p class="chg">L: <span id="search_${item.scriptName}_Low">${item.Ylow}</span></p>
                        </div>
                    </td>
                    <td>
                        <div class="list_cntnt">
                            <p class="title_number" id="search_${item.scriptName}_Ask">${item.ask_price}</p>
                            <p class="chg">O: ${item.open_price}</p>
                        </div>
                    </td>
                    <td>
                        <div class="list_cntnt">
                            ${item.isActive 
                                ? `<input type="checkbox" name="mcx_search" id="search_${item.scriptName}_check" checked class="check_box" onclick="process_market_watch('${item.scriptId}', '${item.marketType}',0);" >`
                                : `<input type="checkbox" name="mcx_search" id="search_${item.scriptName}_check" class="check_box" onclick="process_market_watch('${item.scriptId}', '${item.marketType}',1);" >`
                            }
                            <div class="check_mark"></div>
                        </div>
                    </td>
                `;
                
                searchResultsBody.appendChild(row);
            });
            
            // Show the search action sheet
            $('#searchActionSheet').modal('show');
        }
    });
</script>
@endsection
