@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
<style>
.modal-dialog .modal-title {
  color: #000;
  
}
#edit-qty{
    border: none;
    border-bottom: 1px solid #ccc;
    background-color: transparent;
    color: #000000!important;
    border-radius: 0;
}
.white-colr{
    border: none;
    border-bottom: 1px solid #ccc;
    background-color: transparent;
    color: #000000!important;
    border-radius: 0;
}

#edit-name{
    border: none;
    border-bottom: 1px solid #ccc;
    background-color: transparent;
    color: #000000!important;
    border-radius: 0;
}

#edit-market{
     color: #eaeaea;
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
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lot size Management</h4>
                        <p class="card-category">View and LotSize Management</p>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.lot.size') }}">
                            <div class="row mb-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Symbol</label>
                                        @php
                                            $selectedStatus = request()->get('symbol');
                                        @endphp
                                        <input type="text" class="form-control" name="symbol" value="{{ $selectedStatus ?? ''}}">
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Market</label>
                                        @php
                                            $selectedStatus = request()->get('status');
                                        @endphp
                                     <select name="status" id="status" class="form-control">
                                        <option value="" {{ $selectedStatus == '' ? 'selected' : '' }}></option>
                                        <option value="MCX" {{ $selectedStatus == 'MCX' ? 'selected' : '' }}>MCX</option>
                                        <option value="NSE" {{ $selectedStatus == 'NSE' ? 'selected' : '' }}>NSE</option>
                                        <option value="POSTION" {{ $selectedStatus == 'POSTION' ? 'selected' : '' }}>POSTION</option>
                                        <option value="COMRX" {{ $selectedStatus == 'COMRX' ? 'selected' : '' }}>COMRX</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-12">
                                    <input type="submit" value="Search" class="btn btn-primary">
                                    <a href="{{ route('admin.lot.size') }}" class="btn btn-secondary"> Reset </a>

                                    <button type="button" class="btn btn-success float-end create-model" > Add Lot Size</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Symbol</th>
                                        <th>Lot size</th>
                                        <th>Market</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lotSizes as $index => $lot)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $lot->name }}</td>
                                            <td>{{ $lot->qty }}</td>
                                            <td>{{ $lot->market }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning editLotBtn"
                                                    data-id="{{ $lot->id }}"
                                                    data-name="{{ $lot->name }}"
                                                    data-qty="{{ $lot->qty }}"
                                                    data-market="{{ $lot->market }}">
                                                    Edit
                                                </button>
                                            </td>
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

    <!-- Create Modal -->
    <div class="modal fade" id="createLotModal" tabindex="-1" aria-labelledby="createLotModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.lot-size.store') }}" method="POST">
                @csrf
                <div class="modal-content bg-white text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Lot Size</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Symbol</label>
                            <input type="text"  name="name" class="form-control white-colr" required>
                        </div>
                        <div class="mb-3">
                            <label>Qty</label>
                            <input type="number" name="qty" class="form-control white-colr" required>
                        </div>
                        <div class="mb-3">
                            <label>Market</label>
                            <select name="market" class="form-control" required>
                                <option value="MCX">MCX</option>
                                <option value="NSE">NSE</option>
                                <option value="POSTION">POSTION</option>
                                <option value="COMEX">COMEX</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editLotModal" tabindex="-1" aria-labelledby="editLotModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editLotForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST" />
                <div class="modal-content bg-white text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Lot Size</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Symbol</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Qty</label>
                            <input type="number" name="qty" id="edit-qty" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Market</label>
                            <select name="market" id="edit-market" class="form-control" required>
                                <option value="MCX">MCX</option>
                                <option value="NSE">NSE</option>
                                <option value="POSTION">POSTION</option>
                                <option value="COMEX">COMEX</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable();

        $(document).on('click', '.create-model', function () {
         
            $('#createLotModal').modal('show');
        });

        // Delegated click handler for dynamic table
        $(document).on('click', '.editLotBtn', function () {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let qty = $(this).data('qty');
            let market = $(this).data('market');

            $('#edit-name').val(name);
            $('#edit-qty').val(qty);

            // Match dropdown value (case-insensitive)
            $('#edit-market option').each(function () {
                if ($(this).val().trim().toUpperCase() == market.trim().toUpperCase()) {
                    $(this).prop('selected', true);
                }
            });

            let actionUrl = "{{ url('admin/lot-size/update') }}/" + id;
            $('#editLotForm').attr('action', actionUrl);

            $('#editLotModal').modal('show');
        });
    });
</script>
@endsection
