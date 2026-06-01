@extends('layouts.admin')

@section('title', 'Active Users')

@section('content')
    <style>
        .card .card-body {
            color: #000000;
            position: relative;
        }
    </style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2>{{ $symbol }}</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Active Buy</th>
                                <th>Active Sell</th>
                                <th>Overall Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td><a class="badge badge-pill badge-success"
                                            href="{{ route('admin.users-view', $user->id) }}">{{ $user->user_id }}:
                                            ({{ $user->Username }})</a></td>
                                    <td>{{ $user->total_buy }}</td>
                                    <td>{{ $user->total_sell }}</td>
                                    <td>{{ $user->total_trades }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
