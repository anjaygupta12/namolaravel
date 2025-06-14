@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header ">
                    <h4 class="card-title">Notifications</h4>
                    <p class="card-category">System notifications and alerts</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Delivered at</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>System</td>
                                    <td>New user registration: John Doe</td>
                                    <td>2023-05-15 14:30:25</td>
                                    
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Alert</td>
                                    <td>Unusual login activity detected</td>
                                    <td>2023-05-15 16:45:10</td>
                                   
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
