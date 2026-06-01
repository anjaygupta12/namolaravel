@extends('layouts.admin')

@section('title', 'Active Positions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Active Positions</h4>
                        <p class="card-category">Hs01's Active Positions</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Scrip</th>
                                        <th>Active Buy</th>
                                        <th>Active Sell</th>
                                        <th>Avg Buy Rate</th>
                                        <th>Avg Sell Rate</th>
                                        <th>Total</th>
                                        <th>Net</th>
                                        <th>M2M</th>
                                        <th>CMP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($positions as $val)
                                   
                                        <tr id="row-{{ $val->Pk_id }}">
                                             <td><a class="badge badge-pill badge-success"
                                                    href="{{ route('admin.active-users', $val->Pk_id) }}">{{ explode(':', $val->Symbol)[1] }}</a>
                                            </td>
                                            <td>{{ $val->Mode == 'BUY' ? $val->total . ' (' . $val->LotSize . ')' : '0(0)' }}</td>
                                            <td>{{ $val->Mode == 'SELL' ? $val->total . ' (' . $val->LotSize . ')' : '0(0)' }}</td>
                                            <td>{{ $val->Mode == 'BUY' ? round($val->avgBuy, 2) : '0' }}</td>
                                            <td>{{ $val->Mode == 'SELL' ? round($val->avgBuy, 2) : '0' }}</td>
                                            <td>{{ $val->total }}</td>
                                            <td>{{ $val->total }}</td>
                                            {{-- <td >{{ $val->m2m }}</td> --}}
                                            <td>
                                                {{ is_array($val->m2m) ? implode(', ', $val->m2m) : $val->m2m }}
                                            </td>
                                            <td >{{ $val->TradeLast }}</td>
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

<script>

@endsection