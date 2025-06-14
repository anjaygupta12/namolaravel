@extends('layouts.admin')

@section('title', 'Market Scripts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Market Scripts</h4>
                    <p class="card-category">Manage available market scripts</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success">Add Script</button>
                            <button class="btn btn-info">Import Scripts</button>
                            <button class="btn btn-warning">Sync with Exchange</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Symbol</th>
                                    <th>Name</th>
                                    <th>Exchange</th>
                                    <th>Type</th>
                                    <th>Expiry</th>
                                    <th>Strike</th>
                                    <th>Option Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>NIFTY23MAY22000CE</td>
                                    <td>NIFTY 22000 CE</td>
                                    <td>NSE</td>
                                    <td>Index Option</td>
                                    <td>25-05-2023</td>
                                    <td>22000</td>
                                    <td>CE</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Deactivate</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>NIFTY23MAY22000PE</td>
                                    <td>NIFTY 22000 PE</td>
                                    <td>NSE</td>
                                    <td>Index Option</td>
                                    <td>25-05-2023</td>
                                    <td>22000</td>
                                    <td>PE</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Deactivate</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>BANKNIFTY23MAY46000CE</td>
                                    <td>BANKNIFTY 46000 CE</td>
                                    <td>NSE</td>
                                    <td>Index Option</td>
                                    <td>25-05-2023</td>
                                    <td>46000</td>
                                    <td>CE</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Deactivate</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>BANKNIFTY23MAY46000PE</td>
                                    <td>BANKNIFTY 46000 PE</td>
                                    <td>NSE</td>
                                    <td>Index Option</td>
                                    <td>25-05-2023</td>
                                    <td>46000</td>
                                    <td>PE</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Deactivate</button>
                                    </td>
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
        $('.table').DataTable({
            "pageLength": 25
        });
    });
</script>
@endsection
