@extends('layouts.admin')

@section('title', 'Deleted Trades')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Deleted Trades</h4>
                    <p class="card-category">History of deleted trades</p>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.deleted-trades')}}" method="GET">
                    <div class="row">
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-commodity">
                                    <label class="control-label" for="tradessearch-commodity">Username</label>
                                    <input type="text" id="txtuserid" class="form-control" name="username" placeholder="" value="">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" onclick="LoadWatchList();">Search</button>
                                <a href="{{route('admin.deleted-trades')}}" class="btn btn-default">Reset</a>
                                {{-- <button type="reset" class="btn btn-default" onclick="document.getElementById('trade_search').reset();">Reset</button> --}}
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                               <tr>
                                            <th >&nbsp;</th>
                                            <th>ID</th>
                                            <th>Scrip</th>
                                            <th> Segment</th>
                                            <th>User ID</th>
                                            <th>Buy Rate</th>
                                            <th>Sell Rate</th>
                                            <th> Units</th>
                                            <th>Profit/Loss</th>
                                            <th> Diff</th>
                                            <th>Bought at</th>
                                            <th >Sold at</th>
                                        </tr>
                            </thead>
                            <tbody>
                                @foreach ($deletedTrades as $val)
                                <tr>
                                    <td>{{$val->Pk_id}}</td>
                                    <td>{{$val->Symbol}}</td>
                                    <td>{{$val->TransactionMode}}</td>
                                    <td>{{$val->UserId}}</td>
                                    <td>{{$val->BUYPRICE}}</td>
                                    <td>{{$val->SELLPRICE}}</td>
                                    <td>{{$val->BUYDATE }} / {{$val->Lots }}</td>
                                    <td>74000</td>
                                    <td>10105 secs</td>
                                    <td>{{$val->BUYDATE }} </td>
                                    <td>{{$val->SOLDDATE }}</td>
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
            "order": [[ 8, "desc" ]],
            "pageLength": 25
        });
    });
</script>
@endsection
