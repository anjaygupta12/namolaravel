@extends('layouts.admin')

@section('title', 'Action Ledger')

@section('content')
    <style>
        .dataTables_info {
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: .5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            color: #ffffff !important;
            /* border: 1px solid #d0bebe00; */
            border-radius: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            cursor: default;
            color: #ffffff !important;
            border: 1px solid transparent;
            background: transparent;
            box-shadow: none;
        }

        .table {
            table-layout: fixed;
            width: 100%;
        }

        .col-message {
            width: 75%;
            word-break: break-word;
            white-space: normal;
        }

        .col-date {
            width: 25%;
            white-space: nowrap;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Action Ledger</h4>
                        <p class="card-category">Track all system actions and activities</p>
                    </div>
                    <div class="card-body">

                        {{-- ✅ Added id="log_search", method GET, action pointing to route --}}
                        <form id="log_search" method="GET" action="{{ route('admin.action-ledger') }}">
                            <div class="row align-items-end">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="tradeactionrecordssearch-message">Message</label>
                                        <input type="text" id="tradeactionrecordssearch-message" class="form-control"
                                            name="message" {{-- ✅ Preserve search value after submit --}} value="{{ request('message') }}"
                                            placeholder="Search by remark...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa-solid fa-magnifying-glass"></i> Search
                                        </button>
                                        {{-- ✅ Reset clears the URL param, not just the field --}}
                                        <a href="{{ route('admin.action-ledger') }}" class="btn btn-primary">
                                            <i class="fa-solid fa-arrows-rotate"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">


                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th class="col-message">Message</th>
                                        <th class="col-date">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            <td class="col-message">{{ $log->Remark }}</td>
                                            <td class="col-date">{{ $log->Timestamp }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No records found.</td>
                                        </tr>
                                    @endforelse
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
        // $(document).ready(function() {
        //     $('.table').DataTable({
        //         order: [[1, 'desc']]
        //     });
        // });
    </script>
@endsection
