@extends('layouts.admin')

@section('title', 'Pending Orders')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pending Orders</h4>
                        <p class="card-category">Manage pending trade orders</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form class="form-inline">
                                    <button type="submit" class="btn btn-primary">Create Pending Order</button>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>TIME</th>
                                        <th>Commodity</th>
                                        <th>User ID</th>
                                        <th>Trade</th>
                                        <th>Rate</th>
                                        <th>Lots</th>
                                        <th>Condition</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-key="10026">
                                        <td>10026</td>
                                        <td>10026</td>
                                        <td>6/2/2025 10:27:53 PM</td>
                                        <td>MCX:GOLD25AUGFUT</td>
                                        <td>2003</td>
                                        <td>SELL ORDER</td>
                                        <td>95000.00</td>
                                        <td>0.2</td>
                                        <td>Below</td>
                                        <td>Pending</td>
                                        <td>
                                           <a href="Pending-Orders-view.php?id=10026" title="View" aria-label="View" data-pjax="0"><svg aria-hidden="true" style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1.125em color: black;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path></svg></a><a href="delete-order.php?id=10026" onclick="return confirm(" are="" you="" sure="" want="" to="" delete="" this="" item?")"="" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><svg aria-hidden="true" style="display: inline-block; color: black; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: .875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg></a>

                                            <a href="complete-order.php?id=10026" class="btn btn-sm btn-success">Complete
                                                Order</a></td>
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

            // Auto refresh data every 30 seconds
            setInterval(function() {
                // In a real application, this would fetch fresh data from the server
                console.log('Refreshing pending orders data...');
            }, 30000);
        });
    </script>
@endsection
