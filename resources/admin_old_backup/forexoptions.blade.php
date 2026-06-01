@extends('layouts.admin')

@section('title', 'forx-options')

@section('content')
    <style>
        .modal-dialog .modal-title {
            color: #000;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #28a745;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }
    </style>
    <div class="">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif
                <form method="GET" action="{{ route('admin.forex.option') }}" class="mb-3 row g-2">
            <div class="col-auto">
                <input type="date" name="from_date" class="form-control" 
                    value="{{ request('from_date', \Carbon\Carbon::today()->toDateString()) }}" placeholder="From Date">
            </div>
            <div class="col-auto">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
            </div>
            <div class="col-auto">
                <select name="exchange" class="form-control">
                    <option value="">All Exchanges</option>
                    <option value="MCX" {{ request('exchange')=='MCX'?'selected':'' }}>MCX</option>
                    <option value="NSE" {{ request('exchange')=='NSE'?'selected':'' }}>NSE</option>
                    <option value="OPTIONS" {{ request('exchange')=='OPTIONS'?'selected':'' }}>OPTIONS</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="search" class="form-control" 
                    value="{{ request('search') }}" placeholder="Search Symbol/Instrument">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
          <a href="{{ route('admin.forex.option') }}" class="btn btn-secondary">Reset</a>

        </form>
        

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Forx Options</h4>
                        <p class="card-category">View and forx options Management</p>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Symbol</th>
                                        <th>Exchange</th>
                                        <th>Segment</th>
                                        <th>TickSize</th>
                                        <th>InstrumentType</th>
                                        <th>instrument</th>
                                        <th>SymbolShortName</th>
                                        <th>ExpiryDate</th>
                                        <th>Is Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forexOptions as $val)
                                        <tr>
                                            <td>{{ $val->Id }}</td>
                                            <td>{{ $val->Symbol }}</td>
                                            <td>{{ $val->Exchange }}</td>
                                            <td>{{ $val->Segment }}</td>
                                            <td>{{ $val->TickSize }}</td>
                                            <td>{{ $val->InstrumentType }}</td>
                                            <td>{{ $val->instrument }}</td>
                                            <td>{{ $val->SymbolShortName }}</td>
                                            <td>{{ $val->ExpiryDate }}</td>
                                            <td class="text-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-id="{{ $val->Id }}"
                                                        @if ($val->Isactive == 1) checked @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper">
                                {{ $forexOptions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });


        $(document).ready(function() {
            $('.toggle-status').on('change', function() {
                var id = $(this).data('id');
                var status = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: '{{ route('admin.forex.option.update') }}',
                    method: 'POST',
                    data: {
                        id: id,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Status updated successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update status.'
                        });
                        $checkbox.prop('checked', !status);
                    }
                });
            });
        });
    </script>
@endsection
