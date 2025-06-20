@extends('layouts.admin')

@section('title', 'Closed Trades')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Closed Trades</h4>
                        <p class="card-category">History of completed trades</p>
                    </div>
                    <div class="card-body">
                        <div class="trades-search">
                            <div class="row">
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group field-tradessearch-id">
                                        <label class="control-label" for="tradessearch-id">Time Diff.</label>
                                        <input type="text" id="txttradessearchid" class="form-control" name="time"
                                            placeholder="No. of seconds" value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group field-tradessearch-commodity">
                                        <label class="control-label" for="tradessearch-commodity">Scrip</label>
                                        <input type="text" id="txttradessearchcommodity" class="form-control"
                                            name="scrip_id" placeholder="e.g. GOLD" value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group field-tradessearch-commodity">
                                        <label class="control-label" for="tradessearch-commodity">Username</label>
                                        <input type="text" id="txtuserid" class="form-control" name="username"
                                            placeholder="" value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success"
                                        onclick="LoadWatchList();">Search</button>
                                        <a href="{{route('admin.closed-trades')}}" class="btn btn-default">Reset</a>
                                    {{-- <button type="reset" class="btn btn-default" onclick="LoadWatchList();">Reset</button> --}}
                                </div>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th class="action-column">
                                            <label class="checkcontainer">
                                                <input type="checkbox"
                                                    onclick="checkBoxes(this, 'toggle_trade_status', 'trades')"
                                                    name="selectall">
                                                <span class="checkmark"></span>
                                            </label>
                                        </th>
                                        <th>&nbsp;</th>
                                        <th>ID</a></th>
                                        <th>Scrip</a></th>
                                        <th>Segment</a></th>
                                        <th>User ID</a></th>
                                        <th>Buy Rate</a></th>
                                        <th>Sell Rate</a></th>
                                        <th>Lots / Units</a></th>
                                        <th>Profit/Loss</a></th>
                                        <th>Time Diff</a></th>
                                        <th>>Bought at</a></th>
                                        <th>Sold at</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($closedTrades as $item)
                                        
                                    <tr>
                                        <td>{{$item->Pk_id}}</td>
                                        <td>{{$item->Symbol}}</td>
                                        <td>{{$item->TransactionMode}}</td>
                                        <td>{{$item->UserId}}</td>
                                        <td>{{$item->BUYPRICE}}</td>
                                        <td>{{$item->SELLPRICE}}</td>
                                        <td>{{$item->Ask}} / {{$item->Lots}}</td>
                                        <td >74000</td>
                                        <td>10105 secs</td>
                                        <td>{{$item->BUYDATE}}</td>
                                        <td>{{$item->SOLDDATE}}</td>
                                       
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.table').DataTable({
                "order": [
                    [9, "desc"]
                ],
                "pageLength": 25
            });
        });
    </script>
@endsection
