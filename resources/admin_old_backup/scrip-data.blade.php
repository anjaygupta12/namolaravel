@extends('layouts.admin')

@section('title', 'Scrip Data')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Scrip Data</h4>
                        <p class="card-category">Manage script information and data</p>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="row">
                                <input type="date" id="from_date" name="date" placeholder="Date" value=""
                                    required="" class="">
                                <select id="ddlhour" name="hour">
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{ $i }}">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>

                                :
                                <select id="ddlminutes" name="minutes">
                                    @for ($i = 0; $i < 60; $i++)
                                        <option value="{{$i}}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                               
                                <button type="submit" name="submit" class="btn btn-info col-3 mx-1"
                                    onclick="GetData();">Show Data</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Scrip ID</th>
                                        <th>Exchange Time</th>
                                        <th>System Time</th>
                                        <th>Bid</th>
                                        <th>Ask</th>
                                        <th>High</th>
                                        <th>Low</th>
                                        <th>LTP</th>
                                    </tr>
                                </thead>
                                <tbody id="tbldata">

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
            $('.table').DataTable();
        });


         function GetData() {
            alert($("#ddlscrip_id option:selected").text());
            var _data = {
                Date: $("#from_date").val(),
                symbol: $("#ddlscrip_id option:selected").text(),
                minuts: $("#ddlminutes").val(),
                 hours: $("#ddlhour").val(),
               _token: "{{ csrf_token() }}"
            }
            _data = JSON.stringify(_data);
           
            $.ajax({
                type: "POST",
                contentType: "application/json; charset=utf-8",
                url: "{{route('admin.get.scrip.data')}}",
                data: _data,
                dataType: 'Json',
                success: function (data) {
                    $('#tbldata').html('');
                    
                    var html = '';
                    for (var i = 0; i < data.d.length; i++) {
                        html += '<tr>' +
                            '<td id="' + data.candles[i][0] + '">' + data.candles[i][0] + '</td>' +
                            '<td>' + symbol+'</td>' +
                            '<td>0</td>' +
                            '<td>2</td>' +
                            '<td>2</td>' +
                            '<td>'+data.candles[i][1]+'</td>' +
                            '<td>'+data.candles[i][2]+'</td>' +
                            '<td>'+data.candles[i][3]+'</td>' +
                            '<td>'+data.candles[i][5]+'</td>' +
                            '</tr>';
                    }
                    $('#tbldata').html(html);


                },
                error: function (data) {
                    alert("error found");
                }
            });
        }
    </script>
@endsection
