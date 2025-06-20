@extends('layouts.admin')

@section('title', 'Market Scripts')

@section('content')
    <style>
        label {
            display: inline-block;
            margin-bottom: .2rem;
            margin-top: .2rem;
            font-weight: 500;
            color: #ffffff !important;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #ccc;
            background-color: transparent;
            color: #ffffff !important;
            border-radius: 0;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif


            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Market Scripts</h4>
                        <p class="card-category">Manage available market scripts</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-success add-script">Add Script</button>
                                <button class="btn btn-info">Import Scripts</button>
                                <button class="btn btn-warning">Sync with Exchange</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                    <tr>
                                        <th>Script Name</th>
                                        <th>Market</th>
                                        <th>Lot Size</th>
                                        <th>Tick Size</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scriptsData as $item)
                                        <tr>
                                            <td>{{ $item->ScriptName }}</td>
                                            <td>{{ $item->MarketType }}</td>
                                            <td>{{ $item->LotSize }}</td>
                                            <td>{{ $item->TickSize }}</td>
                                            <td>

                                                <a onclick="return confirm('Are you sure you want to deactivate this item?');"
                                                    href="{{ route('admin.acript.status', $item->ScriptId) }}"
                                                    class="btn {{ $item->Isactive == 1 ? 'btn-success' : 'btn-danger' }} btn-sm"
                                                    data-toggle="tooltip" title="Click to Change Status">
                                                    @if ($item->Isactive == 1)
                                                        <i class="fas fa-check"></i> Active
                                                    @else
                                                        <i class="fas fa-times"></i> Deactive
                                                    @endif
                                                </a>


                                            </td>
                                            <td>
                                                <a href="{{ route('admin.script.edit', $item->ScriptId) }}"
                                                    class="btn btn-primary btn-sm edit-script"id="{{ $item->ScriptId }}"
                                                    data-toggle="tooltip" title="Edit script details">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                <a onclick="return confirm('Are you sure you want to delete this script?');"
                                                    href="{{ route('admin.script.delete', $item->ScriptId) }}"
                                                    class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                    title="Delete script">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
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
    <!-- Edit Script Modal -->
    <div class="modal fade" id="editScriptModal" tabindex="-1" role="dialog" aria-labelledby="editScriptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="" action="{{ route('admin.script.update') }}" method="POST">
                @csrf
                <!-- keep the record id here so it goes back with the AJAX post -->


                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editScriptModalLabel">Edit Script</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria‑hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        {{-- Script Name --}}
                        <div class="form-group">
                            <label for="edit_script_name">Script Name</label>
                            <input type="text" name="script_name" id="edit_script_name" class="form-control"
                                maxlength="100" data-toggle="tooltip" title="Enter script name">
                        </div>

                        {{-- Market Type --}}
                        <div class="form-group">
                            <label for="edit_market_type">Market Type</label>
                            <select name="market_type" id="edit_market_type" class="form-control" data-toggle="tooltip"
                                title="Select market type" style="color: black !important">
                                <option value="">Select Market</option>
                                <option value="NSE">NSE</option>
                                <option value="MCX">MCX</option>
                            </select>
                        </div>

                        {{-- Lot Size --}}
                        <div class="form-group">
                            <label for="edit_lot_size">Lot Size</label>
                            <input type="number" name="lot_size" id="edit_lot_size" class="form-control" min="1"
                                step="0.0001" data-toggle="tooltip" title="Enter lot size (minimum 1)">
                        </div>

                        {{-- Tick Size --}}
                        <div class="form-group">
                            <label for="edit_tick_size">Tick Size</label>
                            <input type="text" name="tick_size" id="edit_tick_size" class="form-control"
                                data-toggle="tooltip" title="Enter tick size (decimal number)">
                        </div>

                        {{-- Is Active --}}
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="edit_is_active"
                                    name="is_active">
                                <label class="custom-control-label" for="edit_is_active">
                                    Is Active
                                </label>
                            </div>
                        </div>
                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary subm-btn" style="margin-right: 15px;"> Create
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.table').DataTable({
                "pageLength": 25
            });

            $(".add-script").on('click', function() {
                $('#editScriptModal').modal('show');
                $(".subm-btn").text('Create');
                $('#edit_id').val('');
                $('#edit_script_name').val('');
                $('#edit_market_type').val('');
                $('#edit_lot_size').val('');
                $('#edit_tick_size').val();
            })
        });

        $(function() {
            /* open modal */
            $('.edit-script').on('click', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');

                $.get(`script-edit/${id}`, function(data) {
                    // populate all fields
                    console.log('testing', data);
                    $('#edit_id').val(data.ScriptId);
                    $('#edit_script_name').val(data.ScriptName);
                    $('#edit_market_type').val(data.MarketType);
                    $('#edit_lot_size').val(data.LotSize);
                    $('#edit_tick_size').val(data.TickSize);
                    $('#edit_is_active').prop('checked', data.is_active == 1);
                    $(".subm-btn").text('Update');
                    $('#editScriptModal').modal('show');
                });
            });

            /* submit update */
            $('#editScriptForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);

                $.ajax({
                    url: '/script/update',
                    type: 'POST',
                    data: form.serialize(),
                    success(res) {
                        $('#editScriptModal').modal('hide');
                        // your preferred toast/alert here:
                        toastr.success(res.message || 'Updated!');
                        // refresh DataTable or page:
                        $('.your-table').DataTable().ajax.reload(null, false);
                    },
                    error(xhr) {
                        toastr.error('Update failed');
                    }
                });
            });
        });
    </script>
@endsection
