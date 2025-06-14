@extends('layouts.admin')

@section('title', 'Action Ledger')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Action Ledger</h4>
                        <p class="card-category">Track all system actions and activities</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group field-tradeactionrecordssearch-message">
                                    <label class="control-label" for="tradeactionrecordssearch-message">Message</label>
                                    <input type="text" id="tradeactionrecordssearch-message" value=""
                                        class="form-control" name="message">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Search</button>
                                <a href="javascript:void(0);" onclick="document.getElementById('log_search').reset();"
                                    title="delete">
                                    <div class="btn btn-primary"> <i class="fa-solid fa-arrows-rotate"> </i> Reset</div>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Message</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Admin login successful</td>
                                        <td>2023-05-15 14:35:10</td>
                                    </tr>
                                    <tr>
                                        <td>Admin login successful</td>
                                        <td>2023-05-15 14:35:10</td>
                                    </tr>
                                    <tr>
                                        <td>Admin login successful</td>
                                        <td>2023-05-15 14:35:10</td>
                                    </tr>
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
    </script>
@endsection
