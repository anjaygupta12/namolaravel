@extends('layouts.admin')

@section('title', 'Brokers')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Brokers</h4>
                        <p class="card-category">Manage broker accounts and connections</p>
                    </div>
                    <div class="card-body">

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>There were some problems with your input:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <form action="{{ route('admin.brokers') }}">
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group field-usersearch-username">
                                        <label class="control-label" for="usersearch-username">Username</label>
                                        <input type="text" id="username" value="" class="form-control"
                                            name="username">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group field-usersearch-status">
                                        <label class="control-label" for="usersearch-status">Account Status</label>
                                        <div class="dropdown">
                                            <select name="status" tabindex="-98">
                                                <option value="" selected>All</option>
                                                <option value="0">Inactive</option>
                                                <option value="1">Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 mt-4">
                                    <div class="form-group">
                                        <button runat="server" class="btn btn-success">Search</button>
                                        <a href="javascript:void(0);" onclick="reset_user_form();" title="delete">
                                            <a href="{{ route('admin.brokers') }}" onclick="reset_user_form();"
                                                class="btn btn-default" title="Reset Filters">
                                                <i class="fa-solid fa-arrows-rotate"></i> Reset
                                            </a>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <p>
                            <a class="btn btn-success" href="{{ route('admin.brokers-create') }} "> Create User</a>
                        </p>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th class="action-column">Actions</th>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Brokerage Share</th>
                                        <th>Profit Share</th>
                                        <th>Type</th>
                                        <th>Account Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brokers as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('admin.brokers-edit', $val->BrokerId) }}" title="Update">
                                                    <i class="fa-solid fa-edit" style="color: black"></i>
                                                </a>
                                                 <a href="{{ route('admin.brokers-copy', $val->BrokerId) }}" title="Copy">
                                                    <i class="fa-solid fa-clone" style="color: black"></i>
                                                </a>
                                                <a href="{{ route('admin.brokers-toggle-status', $val->BrokerId) }}"
                                                    onclick="return confirm('Are you sure you want to Change status?');"
                                                    title="Status">
                                                    <i class="fa-solid fa-toggle-off" style="color: black"></i>
                                                </a>
                                                <form action="{{ route('admin.brokers-destroy', $val->BrokerId) }}"
                                                    method="POST" style="display: inline-block;"
                                                    onsubmit="return confirm('Are you sure you want to delete this broker?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Delete" class="btn btn-link p-0 m-0"
                                                        style="border: none; background: none;">
                                                        <i class="fa-solid fa-trash" style="color: black"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>{{ $val->BrokerId }}</td>
                                            <td>{{ $val->username }}</td>
                                            <td>{{ $val->first_name }}</td>
                                            <td>{{ $val->last_name }}</td>
                                            <td>{{ $val->brokerage_share }}%</td>
                                            <td>{{ $val->profit_share }}%</td>
                                            <td>{{ $val->user_type }}</td>
                                            <td>{{ $val->account_status == 1 ? 'Active' : 'Inactive' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Broker Modal -->
        <div class="modal fade" id="addBrokerModal" tabindex="-1" role="dialog" aria-labelledby="addBrokerModalLabel"
            aria-hidden="true">
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
                                <input type="text" class="form-control" id="brokerName"
                                    placeholder="Enter broker name">
                            </div>
                            <div class="form-group">
                                <label for="apiKey">API Key</label>
                                <input type="text" class="form-control" id="apiKey" placeholder="Enter API key">
                            </div>
                            <div class="form-group">
                                <label for="apiSecret">API Secret</label>
                                <input type="password" class="form-control" id="apiSecret"
                                    placeholder="Enter API secret">
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
