@extends('layouts.admin')

@section('title', 'Accounts')
@section('content')
<style></style>
<div class="container-fluid" style="background-color: #202940!important;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Accounts</h4>
                    <p class="card-category">Manage system accounts and permissions</p>
                </div>
                <div class="card-body">
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
                                <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-dark align-middle mb-0">
                            <thead class="table-secondary text-dark">
                                <tr>
                                <th class="text-nowrap">Receivable / Payable</th>
                                <th class="text-nowrap">Broker</th>
                                <th class="text-nowrap">SUM of Client PL</th>
                                <th class="text-nowrap">SUM of Client Brokerage</th>
                                <th class="text-nowrap">SUM of Client Net</th>
                                <th class="text-nowrap">PL Share</th>
                                <th class="text-nowrap">Brokerage Share</th>
                                <th class="text-nowrap">Net Share</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                    @php
                                        $total_profit   = $accounts->sum('total_profit');
                                        $total_brokrage = $accounts->sum('total_brokrage');
                                        $total_net_pl   = $total_profit + $total_brokrage;
                                         $total_all_profit  = $accounts->sum('pl_share');      // sum of all pl_share
                                        $total_all_brokrage = $accounts->sum('payable');      // sum of all payable
                                        $total_all_net_pl  = $accounts->sum('net_pl_share');
                                    @endphp

                                    <tr class="fw-bold">
                                        <td></td>
                                        <td>Total</td>
                                        <td class="text-nowrap">{{ $total_profit }}</td>
                                        <td class="text-nowrap">{{ $total_brokrage }}</td>
                                        <td class="all_profit">{{ $total_net_pl }}</td>
                                        <td class="all_brokrage">{{ $total_all_profit }}</td>
                                        <td class="all_net_pl">{{ $total_all_brokrage }}</td>
                                        <td class="text-nowrap">{{ $total_all_net_pl }}</td>
                                    </tr>

                                    @foreach($accounts as $val)
                                    <tr class="fw-bold">
                                        <td> Rs. {{ $val->payable }} is to receive from Parent Admin </td>
                                        <td> <a href="{{ route('admin.sub.accounts', $val->broker_id) }}">{{ $val->UserName }}</a> </td>
                                        <td>{{ $val->total_profit }}</td>
                                        <td>{{ $val->total_brokrage }}</td>
                                        <td>{{ $val->total_profit - $val->total_brokrage }}</td>
                                        <td class="text-nowrap">{{ $val->pl_share }}</td>
                                        <td class="text-nowrap">{{ $val->payable }}</td>
                                        <td class="text-nowrap">{{ $val->net_pl_share }}</td>
                                    </tr>
                                    @endforeach

                            
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
    // $(document).ready(function() {
    //     // Initialize DataTables
    //     $('.table').DataTable();
    // });
</script>
@endsection
