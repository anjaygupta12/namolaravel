@extends('layouts.admin')

@section('title', 'Market Watch')

@section('content')
    <div class="container">
        <!-- Single Search Box -->
        <div class="search-container mb-4">
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">

                </div>
                <input type="text" id="global-search-box" class="form-control" placeholder="Search symbols, names, or any market data...">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="clear-search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <small class="form-text text-muted mt-2">
                Search across all market data - symbols, names, prices, changes, etc.
            </small>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs market-tabs" id="marketTabs" role="tablist" style="background-color: black;">
            <li class="nav-item">
                <a class="nav-link" id="mcx-tab" data-toggle="tab" href="#mcx-content" role="tab"
                    aria-controls="mcx-content" aria-selected="false">MCX</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="nse-tab" data-toggle="tab" href="#nse-content" role="tab"
                    aria-controls="nse-content" aria-selected="true">NSE</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="bse-tab" data-toggle="tab" href="#bse-content" role="tab"
                    aria-controls="bse-content" aria-selected="false">Option</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="forex-tab" data-toggle="tab" href="#forex-content" role="tab"
                    aria-controls="forex-content" aria-selected="false">Forex & Crypto</a>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="marketTabsContent">
            <!-- MCX Tab -->
            <div class="tab-pane fade" id="mcx-content" role="tabpanel" aria-labelledby="mcx-tab">
                <div class="table-container">
                    <div class="table-info" id="mcx-table-info">
                        Showing <span id="mcx-count">0</span> MCX symbols
                    </div>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="mcx-data-body">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- NSE Tab -->
            <div class="tab-pane fade show active" id="nse-content" role="tabpanel" aria-labelledby="nse-tab">
                <div class="table-container">
                    <div class="table-info" id="nse-table-info">
                        Showing <span id="nse-count">0</span> NSE symbols
                    </div>
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
                                <th>Action</th>
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
                    <div class="table-info" id="bse-table-info">
                        Showing <span id="bse-count">0</span> Option symbols
                    </div>
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
                    <div class="table-info" id="forex-table-info">
                        Showing <span id="forex-count">0</span> Forex & Crypto symbols
                    </div>
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
                                    <button type="button" class="btn btn-success btn-lg btn-block"
                                        id="buy-button">BUY</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger btn-lg btn-block"
                                        id="sell-button">SELL</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Search Container Styles */
        .search-container {
            margin-top: 20px;
        }

        .table-info {
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-size: 14px;
            color: #6c757d;
        }

        .highlight {
            background-color: #fff3cd !important;
        }

        /* Table container handles horizontal scroll if needed */
        .table-container {
            overflow-x: auto;
        }

        /* Market data table styling */
        .market-data-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: auto;
        }

        /* Table headers and cells */
        .market-data-table th,
        .market-data-table td {
            border: 1px solid #ddd;
            padding: 6px 12px;
            text-align: center;
            white-space: nowrap;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }
        .updated {
    background-color: #ffeaa7;
    transition: background-color 0.5s ease;
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

            // Set initial active tab
            window.activeTab = 'NSE';

            // Market data elements
            const nseDataBody = document.getElementById('nse-data-body');
            const bseDataBody = document.getElementById('bse-data-body');
            const mcxDataBody = document.getElementById('mcx-data-body');
            const forexDataBody = document.getElementById('forex-data-body');
            const globalSearchBox = document.getElementById('global-search-box');
            const clearSearchBtn = document.getElementById('clear-search');

            let socket;
            let marketData = {};
            let searchTerm = '';

            // Connect to WebSocket
            function connect() {
                try {
                    const wsUrl = 'wss://namotraders.in:5005/ws?isAdmin=true';
                    console.log('Attempting to connect to WebSocket at:', wsUrl);

                    // Close existing socket if it exists
                    if (socket) {
                        console.log('Closing existing socket connection');
                        socket.close();
                    }

                    // Create new WebSocket connection
                    socket = new WebSocket(wsUrl);

                    socket.onopen = function() {
                        console.log('WebSocket connection established successfully');
                    };

                    socket.onclose = function(event) {
                        console.log('WebSocket connection closed. Code:', event.code, 'Reason:', event.reason);
                    };

                    socket.onerror = function(error) {
                        console.error('WebSocket error:', error);
                    };

                    socket.onmessage = function(event) {
                        try {
                            // Parse the JSON data
                            const data = JSON.parse(event.data);

                            // Check if data is an array (as sent by the server)
                            if (Array.isArray(data)) {
                                console.log('Received array data with', data.length, 'items');

                                // Process each item in the array
                                data.forEach(function(item) {
                                    if (item && item.Data) {
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
                                            rawData: item.Data.rawData,
                                            banned: item.Data.banned
                                        };
                                    }
                                });

                                // Update the tables with the new data
                                updateMarketDataTables();
                            }
                        } catch (error) {
                            console.error('Error parsing message:', error);
                        }
                    };
                } catch (error) {
                    console.error('Error setting up WebSocket:', error);
                }
            }

            // Filter market data based on search term and exchange
            function filterMarketData(exchange) {
                return Object.entries(marketData).filter(function(entry) {
                    const symbol = entry[0];
                    const data = entry[1];

                    // Filter by exchange first
                    let isCorrectExchange = false;
                    if (exchange === 'nse') {
                        isCorrectExchange = symbol.startsWith('NSE:');
                    } else if (exchange === 'bse') {
                        isCorrectExchange = symbol.startsWith('BSE:');
                    } else if (exchange === 'mcx') {
                        isCorrectExchange = symbol.startsWith('MCX:');
                    } else if (exchange === 'forex') {
                        isCorrectExchange = symbol.startsWith('FOREX:') || symbol.startsWith('CRYPTO:');
                    }

                    if (!isCorrectExchange) {
                        return false;
                    }

                    // If no search term, return all data for this exchange
                    if (!searchTerm) {
                        return true;
                    }

                    // Convert search term to lowercase for case-insensitive search
                    const term = searchTerm.toLowerCase();

                    // Search across multiple fields
                    return (
                        symbol.toLowerCase().includes(term) ||
                        (data.symbolShortName && data.symbolShortName.toLowerCase().includes(term)) ||
                        (data.expiryDate && data.expiryDate.toLowerCase().includes(term)) ||
                        (data.ltp && data.ltp.toString().includes(term)) ||
                        (data.bid_price && data.bid_price.toString().includes(term)) ||
                        (data.ask_price && data.ask_price.toString().includes(term)) ||
                        (data.ch && data.ch.toString().includes(term)) ||
                        (data.chp && data.chp.toString().includes(term)) ||
                        (data.vol_traded_today && data.vol_traded_today.toString().includes(term)) ||
                        (data.open_price && data.open_price.toString().includes(term))
                    );
                });
            }

            // Update all market data tables
let initialized = {
    nse: false,
    bse: false,
    mcx: false,
    forex: false
};

function updateMarketDataTables() {
    updateTable('nse', 11);
    updateTable('bse', 10);
    updateTable('mcx', 11);
    updateTable('forex', 10);
}

function updateTable(type, colspan) {
    const data = filterMarketData(type);
    const countEl = document.getElementById(`${type}-count`);
    const tbody = document.getElementById(`${type}-data-body`); // make sure table body id matches (e.g. id="nse-data-body")

    data.sort((a, b) => a[0].localeCompare(b[0]));
    countEl.textContent = data.length;

    if (!initialized[type]) {
        // --- First Time Render ---
        tbody.innerHTML = '';

        if (data.length === 0) {
            // tbody.innerHTML = `<tr><td colspan="${colspan}" class="no-results">No matching symbols found</td></tr>`;
        } else {
            data.forEach(([symbol, item]) => {
                const row = createTableRow(symbol, item);
                row.setAttribute('data-symbol', symbol);
                tbody.appendChild(row);
            });
        }
        initialized[type] = true;
        return;
    }

    // --- Subsequent Updates (Only Update Values) ---
    const existingRows = {};
    tbody.querySelectorAll('tr[data-symbol]').forEach(row => {
        existingRows[row.getAttribute('data-symbol')] = row;
    });

    data.forEach(([symbol, item]) => {
        const row = existingRows[symbol];
        if (row) {
            // Update only changed cells
            const newRow = createTableRow(symbol, item);
            const oldCells = row.children;
            const newCells = newRow.children;

            for (let i = 0; i < oldCells.length; i++) {
                if (oldCells[i].textContent !== newCells[i].textContent) {
                    oldCells[i].innerHTML = newCells[i].innerHTML;
                    oldCells[i].classList.add('updated');
                    setTimeout(() => oldCells[i].classList.remove('updated'), 500);
                }
            }
        } else {
            // New symbol - append new row
            const newRow = createTableRow(symbol, item);
            newRow.setAttribute('data-symbol', symbol);
            tbody.appendChild(newRow);
        }
    });

    // Remove rows for symbols no longer present
    Object.keys(existingRows).forEach(symbol => {
        if (!data.some(entry => entry[0] === symbol)) {
            existingRows[symbol].remove();
        }
    });
}


            // Create a table row for market data
            function createTableRow(symbol, data) {
                const row = document.createElement('tr');

                // Row click: show buy/sell sheet
                row.addEventListener('click', function() {
                    showBuySellActionSheet(symbol, data);
                });

                row.style.cursor = 'pointer';

                // Create table cells
                const cells = [
                    createCell(symbol),
                    createCell(data.symbolShortName || ''),
                    createCell(data.expiryDate || ''),
                    createCell(data.ltp || 0),
                    createCell(data.bid_price || 0, data.bid_price == 0 ? 'zero-value' : ''),
                    createCell(data.ask_price || 0, data.ask_price == 0 ? 'zero-value' : ''),
                    createCell(
                        data.ch || 0,
                        parseFloat(data.ch || 0) > 0 ?
                        'positive-change' :
                        parseFloat(data.ch || 0) < 0 ?
                        'negative-change' :
                        ''
                    ),
                    createCell(
                        (data.chp || 0) + '%',
                        parseFloat(data.chp || 0) > 0 ?
                        'positive-change' :
                        parseFloat(data.chp || 0) < 0 ?
                        'negative-change' :
                        ''
                    ),
                    createCell(data.vol_traded_today || 0),
                    createCell(data.open_price || 0)
                ];

                // Add banned/unbanned button
                const bannedCell = document.createElement('td');
                const bannedButton = document.createElement('a');

                const href = 'banned-status/?status=' + data.banned + '&sybmol=' + symbol;

                bannedButton.href = href;
                bannedButton.textContent = data.banned == 0 ? 'ADD TO BAN' : 'REMOVE TO BAN';
                bannedButton.classList.add('btn-status');
                bannedButton.style.color = '#fff';
                bannedButton.style.padding = '4px 8px';
                bannedButton.style.borderRadius = '4px';
                bannedButton.style.textDecoration = 'none';
                bannedButton.style.fontSize = '13px';
                bannedButton.style.backgroundColor = data.banned == 1 ? '#007bff' : '#dc3545';

                bannedButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    window.location.href = href;
                });

                bannedCell.appendChild(bannedButton);
                cells.push(bannedCell);

                // Append all cells to row
                cells.forEach(function(cell) {
                    row.appendChild(cell);
                });

                return row;
            }

            // Show buy/sell action sheet for the selected symbol
            function showBuySellActionSheet(symbol, data) {
                document.getElementById('selected-symbol').textContent = symbol;
                document.getElementById('bid-price-value').textContent = data.bid_price || '0.00';
                document.getElementById('ask-price-value').textContent = data.ask_price || '0.00';
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

            // Global search functionality
            globalSearchBox.addEventListener('input', function(e) {
                searchTerm = e.target.value.trim();
                updateMarketDataTables();
            });

            // Clear search functionality
            clearSearchBtn.addEventListener('click', function() {
                globalSearchBox.value = '';
                searchTerm = '';
                updateMarketDataTables();
                globalSearchBox.focus();
            });

            // Auto-connect when the page loads
            connect();

            // Handle buy button click
            document.getElementById('buy-button').addEventListener('click', function() {
                const symbol = document.getElementById('selected-symbol').textContent;
                const quantity = document.getElementById('trade-quantity').value;
                const price = document.getElementById('ask-price-value').textContent;

                console.log('BUY Order:', {
                    symbol,
                    quantity,
                    price
                });

                alert('Buy order placed for ' + quantity + ' ' + symbol + ' at price ' + price);
                $('#buyActionSheet').modal('hide');
            });

            // Handle sell button click
            document.getElementById('sell-button').addEventListener('click', function() {
                const symbol = document.getElementById('selected-symbol').textContent;
                const quantity = document.getElementById('trade-quantity').value;
                const price = document.getElementById('bid-price-value').textContent;

                console.log('SELL Order:', {
                    symbol,
                    quantity,
                    price
                });

                alert('Sell order placed for ' + quantity + ' ' + symbol + ' at price ' + price);
                $('#buyActionSheet').modal('hide');
            });

            // Handle close button click
            document.getElementById('close-action-sheet').addEventListener('click', function() {
                $('#buyActionSheet').modal('hide');
            });
        });
    </script>
@endsection
