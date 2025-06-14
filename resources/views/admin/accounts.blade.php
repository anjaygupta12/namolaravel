@extends('layouts.admin')

@section('title', 'Accounts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Accounts</h4>
                    <p class="card-category">Manage system accounts and permissions</p>
                </div>
                <div class="card-body ">
                    <div id="pjax-grid-activeTrade" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" id="from_date" class="form-control" name="from_date" placeholder="From Date">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="to_date" class="form-control" name="to_date" placeholder="To Date">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success">Calculate for custom dates</button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <h3 class="col-md-12" style="color: white;"></h3>
                                <div style="font-size: 24px; color: white; text-align: center; font-weight: bold; margin: auto;"></div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Receivable / Payable</th>
                                            <th>Broker:</th>
                                            <th>SUM of Client PL</th>
                                            <th>SUM of Client Brokerage</th>
                                            <th>SUM of Client Net</th>
                                            <th>PL Share</th>
                                            <th>Brokerage Share</th>
                                            <th>Net Share</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="font-weight: bold;">
                                            <td></td>
                                            <td>Total</td>
                                            <td>-515405</td>
                                            <td>93206.05</td>
                                            <td>-608611.05</td>
                                            <td>-1042605</td>
                                            <td>80550.45</td>
                                            <td>1123155.45</td>
                                        </tr>
                                        <tr>
                                            <td>Rs. 12655.6 is payable to PL </td>
                                            <td>578: ban01</td>
                                            <td>527200</td>
                                            <td>25311.2</td>
                                            <td>501888.8</td>
                                            <td>0</td>
                                            <td>12655.6</td>
                                            <td>12655.6</td>
                                        </tr>
                                        <tr>
                                            <td>Rs. 0 is to receive from Deep </td>
                                            <td>586: dc101</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td>Rs. 0 is to receive from LG Guru</td>
                                            <td>619: lg01</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td>Rs. 0 is payable to Parent Admin</td>
                                            <td>0: Admin</td>
                                            <td>-1042605</td>
                                            <td>67894.85</td>
                                            <td>-1110499.85</td>
                                            <td>-1042605</td>
                                            <td>67894.85</td>
                                            <td>1110499.85</td>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('.table').DataTable();
    });
</script>
@endsection
