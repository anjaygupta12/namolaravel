@extends('layouts.admin')

@section('title', 'View User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" required="">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" required="">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="export" class="btn btn-info w-100">Export Trades</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" id="from_date1" name="from_date" class="form-control" placeholder="From Date" required="">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="to_date1" name="to_date" class="form-control" placeholder="To Date" required="">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="export" class="btn btn-info w-100">Download Trades PDF</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" id="from_date2" name="from_date" class="form-control" placeholder="From Date" required="">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="to_date2" name="to_date" class="form-control" placeholder="To Date" required="">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="export" class="btn btn-info w-100">Export Funds</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ContentPlaceHolder1_dvdropdown" class="btn-group"><div class="btn-group"><button type="button" class="btn btn-primary" onclick="action_fun(); ">Actions</button><button type="button" class="btn btn-primary dropdown-toggle" onclick="action_fun();"><span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" id="action_menu"><li><a class="dropdown-item" href="edit-user.aspx?userid=2004">Update</a></li><li role="separator" class="dropdown-divider"></li><li><a class="dropdown-item" href="reset-user.aspx?userid=2004" data-confirm="Are you sure you want to Reset this item? All History will be cleared..." data-method="post">Reset Account</a></li><li role="separator" class="dropdown-divider"></li><li><a class="dropdown-item" href="recalculate-brokerage.aspx?userid=2004" data-confirm="Brokerage for all existing(active &amp; Inactive) trades will be recalculated, would you like to proceed?" data-method="post">Refresh Brokerage</a></li><li role="separator" class="dropdown-divider"></li><li><a class="dropdown-item" href="copy-user.aspx?userid=2004" data-confirm="New Account with similar details will created, are you sure you want to proceed?" data-method="post">Duplicate</a></li><li role="separator" class="dropdown-divider"></li><li><a class="dropdown-item" href="change-user-password.aspx?userid=2004">Change Password</a></li><li role="separator" class="dropdown-divider"></li><li><a class="dropdown-item" href="delete-account.aspx?userid=2004">Delete Account</a></li><li role="separator" class="dropdown-divider"></li><!--<li><span id = '25152_chpass' class='dropdown-item' onclick='changePassword()'>Change Password</span></li>--></ul></div></div>

            <script type="text/javascript">
                function action_fun() {
                    document.getElementById("action_menu").classList.toggle("actionShow");
                }
            </script>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                       
                        <div class="col-md-12">
                            <div id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-success btn-lg btn-block" type="button" onclick="view_detailsShow();">
                                        View Details
                                    </button>
                                </h2>
                            </div>
                            <script type="text/javascript">
                                function view_detailsShow() {
                                    document.getElementById("show_collpse").classList.toggle("show_colla");
                                }
                            </script>

                        </div>
                        <div class="col-md-12">
                            <div class="accordion" id="accordionExample">
                                    <div class="collapse" id="show_collpse">
                                        <table id="w2" class="table table-striped table-bordered detail-view">
                                            <tbody>
                                                <tr>
                                                    <th>ID</th>
                                                    <td>6349</td>
                                                </tr>
                                                <tr>
                                                    <th>Name</th>
                                                    <td>Anshul</td>
                                                </tr>
                                                <tr>
                                                    <th>Mobile</th>
                                                    <td>7015626484</td>
                                                </tr>
                                                <tr>
                                                    <th>Username</th>
                                                    <td>7015626484</td>
                                                </tr>
                                                <tr>
                                                    <th>City</th>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <th>Account Status</th>
                                                    <td>Active</td>
                                                </tr>
                                                <tr>
                                                    <th>Allow Orders between High - Low?</th>
                                                    <td>Yes</td>
                                                </tr>
                                                <tr>
                                                    <th>Allow Fresh Entry Order above high &amp; below low?</th>
                                                    <td>Yes</td>
                                                </tr>
                                                <!--                <tr>
                                               <th>Trigger orders at New High - Low rates Instead of Bid-Ask?</th>
                                               <td>Yes</td>
                                            </tr>-->
                                                <tr>
                                                    <th>demo account?</th>
                                                    <td>No</td>
                                                </tr>
                                                <tr>
                                                    <th>Auto-close trades if losses cross beyond the configured limit</th>
                                                    <td>Yes</td>
                                                </tr>
                                                <tr>
                                                    <th>Auto-close trades if insufficient fund to hold overnight</th>
                                                    <td>Yes</td>
                                                </tr>
                                                <!--                                 <tr>
                                               <th>Trade equity as units instead of lots.</th>
                                               <td>Yes</td>
                                            </tr>
                                            -->
                                                <tr>
                                                    <th>Minimum lot size required per single trade of MCX</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of MCX</th>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity</th>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity INDEX</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity INDEX</th>
                                                    <td>20</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of MCX to be actively open at a time</th>
                                                    <td>15</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity to be actively open at a time</th>
                                                    <td>20</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity INDEX to be actively open at a time</th>
                                                    <td>100</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity Options</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity Options</th>
                                                    <td>25</td>
                                                </tr>
                                                <tr>
                                                    <th>Minimum lot size required per single trade of Equity INDEX Options</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per single trade of Equity INDEX Options</th>
                                                    <td>25</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity to be actively open at a time</th>
                                                    <td>50</td>
                                                </tr>
                                                <tr>
                                                    <th>Maximum lot size allowed per scrip of Equity INDEX Options to be actively open at a time</th>
                                                    <td>50</td>
                                                </tr>
                                                <tr>
                                                    <th>auto-Close all active trades when the losses reach % of Ledger-balance</th>
                                                    <td>90</td>
                                                </tr>
                                                <tr>
                                                    <th>Notify client when the losses reach % of Ledger-balance</th>
                                                    <td>70</td>
                                                </tr>
                                                <tr>
                                                    <th>MCX Trading</th>
                                                    <td>Active</td>
                                                </tr>
                                                <tr>
                                                    <th>MCX brokerage per_crore</th>
                                                    <td>1000.0000</td>
                                                </tr>
                                                <!--                                 <tr>
                                               <th>MCX Intraday Margin per Lot</th>
                                               <td style="word-break: break-all;width: 60%;">{"BULLDEX":"100000","GOLD":"2500","SILVER":"2500","CRUDEOIL":"1000","COPPER":"2500","NICKEL":"2500","ZINC":"2500","LEAD":"2500","ALUMINIUM":"2500","NATURALGAS":"2500","MENTHAOIL":"100000","COTTON":"100000","CPO":"100000","GOLD-MINI":"500","SILVER-MINI":"500","MGOLD":"5000","MSILVER":"5000","SILVERMIC":"300"}</td>
                                            </tr>
                                            <tr>
                                               <th>MCX Holding Margin per Lot</th>
                                               <td style="word-break: break-all;width: 60%;">{"BULLDEX":"200000","GOLD":"25000","SILVER":"25000","CRUDEOIL":"15000","COPPER":"25000","NICKEL":"25000","ZINC":"25000","LEAD":"25000","ALUMINIUM":"25000","NATURALGAS":"25000","MENTHAOIL":"200000","COTTON":"200000","CPO":"200000","GOLD-MINI":"10000","SILVER-MINI":"10000","MGOLD":"50000","MSILVER":"50000","SILVERMIC":"3000"}</td>
                                            </tr>-->
                                                <tr>
                                                    <th>Equity Trading</th>
                                                    <td>Active</td>
                                                </tr>
                                                <tr>
                                                    <th>Equity brokerage</th>
                                                    <td>1000.0000</td>
                                                </tr>
                                                <tr>
                                                    <th>Intraday Exposure/Margin Equity</th>
                                                    <td>500</td>
                                                </tr>
                                                <tr>
                                                    <th>Holding Exposure/Margin Equity</th>
                                                    <td>70</td>
                                                </tr>

                                                <tr>
                                                    <th>Options Trading</th>
                                                    <td>Active</td>
                                                </tr>
                                                <tr>
                                                    <th>Options brokerage</th>
                                                    <td>25.0000</td>
                                                </tr>
                                                <tr>
                                                    <th>Intraday Exposure/Margin Options</th>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <th>Holding Exposure/Margin Options</th>
                                                    <td>2</td>
                                                </tr>

                                                <tr>
                                                    <th>Ledger Balance</th>
                                                    <td>0.0000</td>
                                                </tr>
                                                <!--                                 <tr>
                                               <th>Orders to be away by % from current price Equity</th>
                                               <td>0</td>
                                            </tr>
                                            <tr>
                                               <th>Orders to be away by points in each scrip MCX</th>
                                               <td>{"MGOLD":"0","MSILVER":"0","BULLDEX":"0","GOLD":"0","SILVER":"0","CRUDEOIL":"0","COPPER":"0","NICKEL":"0","ZINC":"0","LEAD":"0","NATURALGAS":"0","ALUMINIUM":"0","MENTHAOIL":"100","COTTON":"100","CPO":"100","GOLD-MINI":"0","SILVER-MINI":"0","SILVERMIC":"0"}</td>
                                            </tr>
                                            -->
                                                <tr>
                                                    <th>Broker</th>
                                                    <td>0 : </td>
                                                </tr>
                                                <tr>
                                                    <th>Account Created At</th>
                                                    <td>2024-12-07 14:01:06</td>
                                                </tr>
                                                <tr>
                                                    <th>Notes</th>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Profit / Loss</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Brokerage</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Net Profit / Loss</th>
                                                    <td>0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h3>Fund - Withdrawal &amp; Deposits</h3>
                                <div id="w3" class="grid-view">
                                    <div class="summary">Showing <b>1</b> of <b>1</b> items.</div>
                                    No records found<table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><a href="#" data-sort="wd">Customer Name</a></th>
                                                <th><a href="#" data-sort="wd">Amount</a></th>
                                                <th><a href="#" data-sort="created_at">Type</a></th>
                                                <th><a href="#" data-sort="created_at">Created At</a></th>
                                                <th><a href="#" data-sort="created_at">Status</a></th>
                                                <th><a href="#" data-sort="notes">Notes</a></th>
                                            </tr>
                                        </thead>
                                        <tbody id="ContentPlaceHolder1_tblwithdrawl"><tr></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h3>Active Trades</h3>
                                <div id="pjax-grid-index" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                                    <div id="w4" class="grid-view">
                                        <div class="summary">Showing <b>0</b> of <b></b>items.</div>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>X</th>
                                                    <th><a class="desc" href="#" data-sort="id">ID</a></th>
                                                    <th><a href="#" data-sort="commodity">Scrip</a></th>
                                                    <!--                                             <th><a href="#" data-sort="status">Trade</a></th>-->
                                                    <th><a href="#" data-sort="buy_price">Buy Rate</a></th>
                                                    <th><a href="#" data-sort="sell_price">Sell Rate</a></th>
                                                    <th><a href="#" data-sort="lots">Lots / Units</a></th>
                                                    <th><a href="#" data-sort="buy_turnover">Buy Turnover</a></th>
                                                    <th><a href="#" data-sort="sell_turnover">Sell Turnover</a></th>
                                                    <th>CMP</th>
                                                    <th>Active P/L</th>
                                                    <th><a href="#" data-sort="margin_used">Margin Used</a></th>
                                                    <th><a href="#" data-sort="bought_at">Bought at</a></th>
                                                    <th><a href="#" data-sort="sold_at">Sold at</a></th>
                                                    <th><a href="#" data-sort="buy_ip">Buy Ip</a></th>
                                                    <th><a href="#" data-sort="sell_ip">Sell Ip</a></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="16">No records found
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h3>Closed Trades</h3>
                                <div id="w5" class="grid-view">
                                    <div class="summary">Showing <b>0</b> of <b></b>items.</div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><a class="desc" href="#" data-sort="id">ID</a></th>
                                                <th><a href="#" data-sort="commodity">Scrip</a></th>
                                                <th><a href="#" data-sort="buy_price">Buy Rate</a></th>
                                                <th><a href="#" data-sort="sell_price">Sell Rate</a></th>
                                                <th><a href="#" data-sort="lots">Lots / Units</a></th>
                                                <th><a href="#" data-sort="buy_turnover">Buy Turnover</a></th>
                                                <th><a href="#" data-sort="sell_turnover">Sell Turnover</a></th>
                                                <th><a href="#" data-sort="pl">Profit / Loss</a></th>
                                                <th><a href="#" data-sort="brokerage">Brokerage</a></th>
                                                <th><a href="#" data-sort="bought_at">Bought at</a></th>
                                                <th><a href="#" data-sort="sold_at">Sold at</a></th>
                                                <th><a href="#" data-sort="sell_ip">Buy Ip</a></th>
                                                <th><a href="#" data-sort="buy_ip">Sell Ip</a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="16">No records found
                                                </td>
                                            </tr>
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h3>MCX Pending Orders</h3>
                                <div id="w6" class="grid-view">
                                    <div class="summary">Showing <b>0</b> of <b></b>items.</div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><a href="#" data-sort="bs">ID</a></th>
                                                <th><a href="#" data-sort="bs">Trade</a></th>
                                                <th><a href="#" data-sort="lots">Lots</a></th>
                                                <th><a href="#" data-sort="commodity">Commodity</a></th>
                                                <th><a href="#" data-sort="order_condition">Condition</a></th>
                                                <th><a href="#" data-sort="rate">Rate</a></th>
                                                <th><a href="#" data-sort="date">Date</a></th>
                                                <th><a href="#" data-sort="ip_address">Ip Address</a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="7">No records found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h3>Equity Pending Orders</h3>
                                <div id="w7" class="grid-view">
                                    <div class="summary">Showing <b>0</b> of <b></b>items.</div>
    
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><a href="#" data-sort="bs">ID</a></th>
                                                <th><a href="#" data-sort="bs">Trade</a></th>
                                                <th><a href="#" data-sort="lots">Lots</a></th>
                                                <th><a href="#" data-sort="commodity">Commodity</a></th>
                                                <th><a href="#" data-sort="order_condition">Condition</a></th>
                                                <th><a href="#" data-sort="rate">Rate</a></th>
                                                <th><a href="#" data-sort="date">Date</a></th>
                                                <th><a href="#" data-sort="ip_address">Ip Address</a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="7">No records found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h3>COMEX Pending Orders</h3>
                                <div id="w7" class="grid-view">
                                    <div class="summary">Showing <b>0</b> of <b></b>items.</div>
    
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><a href="#" data-sort="bs">ID</a></th>
                                                <th><a href="#" data-sort="bs">Trade</a></th>
                                                <th><a href="#" data-sort="lots">Lots</a></th>
                                                <th><a href="#" data-sort="commodity">Scrip</a></th>
                                                <th><a href="#" data-sort="order_condition">Condition</a></th>
                                                <th><a href="#" data-sort="rate">Rate</a></th>
                                                <th><a href="#" data-sort="date">Date</a></th>
                                                <th><a href="#" data-sort="ip_address">Ip Address</a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="7">No records found</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
