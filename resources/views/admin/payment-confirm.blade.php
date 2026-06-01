@extends('layouts.admin')

@section('title', 'View User')

@section('content')
<style>
    body {
        background: #111827;
        margin: 0;
        padding: 0;
    }

    .container-fluid {
        padding: 10px;
    }

    .custom-card {
        background: #1f2937;
        border-radius: 12px;
        border: 1px solid #374151;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        width: 100%;
    }

    .custom-card .card-body {
        color: #ffffff;
        padding: 20px;
    }

    .page-title {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 24px;
    }

    .detail-view {
        width: 100%;
        background: #1f2937;
        color: #ffffff;
        margin-bottom: 0;
        table-layout: fixed;
        word-wrap: break-word;
    }

    .detail-view th {
        width: 35%;
        background: #374151;
        color: #ffffff;
        padding: 12px;
        border: 1px solid #4b5563;
        font-weight: 600;
    }

    .detail-view td {
        background: #111827;
        color: #ffffff;
        padding: 12px;
        border: 1px solid #4b5563;
    }

    .detail-view tr:hover td,
    .detail-view tr:hover th {
        background: #2563eb;
        transition: 0.3s;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {

        .container-fluid {
            padding: 0 !important;
        }

        .row,
        .col-md-12 {
            margin: 0 !important;
            padding: 0 !important;
        }

        .custom-card {
            border-radius: 0;
            min-height: 100vh;
            width: 100%;
            border: none;
        }

        .custom-card .card-body {
            padding: 10px;
        }

        .page-title {
            font-size: 18px;
            padding: 10px;
            margin-bottom: 5px;
        }

        .detail-view th,
        .detail-view td {
            font-size: 13px;
            padding: 10px 8px;
        }

        .detail-view th {
            width: 40%;
        }

        .badge {
            font-size: 11px;
        }
    }
</style>
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
    
    <h3 class="page-title mb-0">View User Transaction</h3>

    <a href="{{ url('/admin/users') }}" class="btn btn-primary">
        Back
    </a>

</div>

    <div class="row">
        <div class="col-md-12">

            <div class="card custom-card">
                <div class="card-body">

                    <table class="table table-bordered detail-view">
                        <tbody>

                            <tr>
                                <th>ID</th>
                                <td>{{ $data->PK_Id }}</td>
                            </tr>

                            <tr>
                                <th>User Id</th>
                                <td>{{ $data->user_id }}</td>
                            </tr>

                            <tr>
                                <th>Username</th>
                                <td>{{ $data->Username }}</td>
                            </tr>

                            <tr>
                                <th>Amount</th>
                                <td>₹ {{ number_format($data->Amount, 2) }}</td>
                            </tr>

                            <tr>
                                <th>Note</th>
                                <td>{{ $data->notes }}</td>
                            </tr>

                            <tr>
                                <th>Transaction For</th>
                                <td>
                                    @if($data->type == 1)
                                        <span class="badge bg-success">Deposit</span>
                                    @else
                                        <span class="badge bg-danger">Withdrawal</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Transaction Type</th>
                                <td>
                                    @if($data->type == 1)
                                        <span class="badge bg-primary">Created</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Deducted</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Transaction Mode</th>
                                <td>
                                    <span class="badge bg-info text-dark">Offline</span>
                                </td>
                            </tr>

                            <tr>
                                <th>Created At</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($data->LastModify)->format('d M Y h:i:s A') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Modified At</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($data->LastModify)->format('d M Y h:i:s A') }}
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection