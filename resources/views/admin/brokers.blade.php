@extends('layouts.admin')

@section('title', 'Brokers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Brokers</h4>
                    <p class="card-category">Manage broker accounts and connections</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addBrokerModal">Add New Broker</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Broker Name</th>
                                    <th>API Key</th>
                                    <th>Status</th>
                                    <th>Last Connected</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Zerodha</td>
                                    <td>••••••••ABCD</td>
                                    <td><span class="badge badge-success">Connected</span></td>
                                    <td>2023-05-15 09:30:00</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-warning btn-sm">Disconnect</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Upstox</td>
                                    <td>••••••••EFGH</td>
                                    <td><span class="badge badge-danger">Disconnected</span></td>
                                    <td>2023-05-14 15:45:20</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-success btn-sm">Connect</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Angel One</td>
                                    <td>••••••••IJKL</td>
                                    <td><span class="badge badge-success">Connected</span></td>
                                    <td>2023-05-15 09:32:15</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">View</button>
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-warning btn-sm">Disconnect</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Broker Modal -->
    <div class="modal fade" id="addBrokerModal" tabindex="-1" role="dialog" aria-labelledby="addBrokerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBrokerModalLabel">Add New Broker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="brokerName">Broker Name</label>
                            <input type="text" class="form-control" id="brokerName" placeholder="Enter broker name">
                        </div>
                        <div class="form-group">
                            <label for="apiKey">API Key</label>
                            <input type="text" class="form-control" id="apiKey" placeholder="Enter API key">
                        </div>
                        <div class="form-group">
                            <label for="apiSecret">API Secret</label>
                            <input type="password" class="form-control" id="apiSecret" placeholder="Enter API secret">
                        </div>
                        <div class="form-group">
                            <label for="brokerType">Broker Type</label>
                            <select class="form-control" id="brokerType">
                                <option value="zerodha">Zerodha</option>
                                <option value="upstox">Upstox</option>
                                <option value="angelone">Angel One</option>
                                <option value="fyers">Fyers</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Broker</button>
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
