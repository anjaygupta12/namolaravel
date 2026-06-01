@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
  <style>
    /* Table Layout Fix */
.custom-table {
    table-layout: fixed;
    width: 100%;
}

/* Common Cell Styling */
.custom-table th,
.custom-table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Column Widths */
.custom-table th:nth-child(1),
.custom-table td:nth-child(1) {
    width: 50px;
}

.custom-table th:nth-child(2),
.custom-table td:nth-child(2) {
    width: 100px;
}

.custom-table th:nth-child(3),
.custom-table td:nth-child(3) {
    width: 80px;
}

.custom-table th:nth-child(4),
.custom-table td:nth-child(4) {
    width: 100px;
}

.custom-table th:nth-child(5),
.custom-table td:nth-child(5) {
    width: 100px;
}

.custom-table th:nth-child(6),
.custom-table td:nth-child(6) {
    width: 120px;
}

.custom-table th:nth-child(7),
.custom-table td:nth-child(7) {
    width: 120px;
}

.custom-table th:nth-child(8),
.custom-table td:nth-child(8) {
    width: 100px;
}

.custom-table th:nth-child(9),
.custom-table td:nth-child(9) {
    width: 120px;
}

.custom-table th:nth-child(10),
.custom-table td:nth-child(10) {
    width: 120px;
}

.custom-table th:nth-child(11),
.custom-table td:nth-child(11) {
    width: 120px;
}

.custom-table th:nth-child(12),
.custom-table td:nth-child(12) {
    width: 100px;
}

.custom-table th:nth-child(13),
.custom-table td:nth-child(13) {
    width: 100px;
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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Trading Users Management</h4>
                        <p class="card-category">View and manageUsers Management</p>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.users') }}">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>ID </label>
                                        <input name="user_id" type="text" id="user_id" class="form-control"
                                            placeholder="Search by ID">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input name="username" type="text" id="username" class="form-control"
                                            placeholder="Search by username">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Account Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option selected="selected" value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <input type="submit" value="Search" class="btn btn-primary">
                                    <a href="{{ route('admin.users') }}" class="btn btn-secondary"> Reset </a>

                                    <a href="{{ route('admin.user-create') }}" class="btn btn-success float-right">add new
                                        User</a>
                                </div>
                            </div>

                        </form>

                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th scope="col">action</th>
                                        <th scope="col">ID</th>
                                        <th>Full Name</th>
                                        <th scope="col">Username</th>
                                        {{-- <th>Net Balance</th> --}}
                                        <th scope="col">Ledger Balance</th>
                                        <th>Gross P/L</th>
                                        <th>Brokerage</th>
                                        <th>Net P/L</th>
                                        <th scope="col">Admin</th>
                                        <th scope="col">Demo Account?</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <td>
                                                @if (canAccess('Trading View'))
                                                    <a href="{{ route('admin.users-view', $user->id) }}"
                                                        style="color: white" title="View" aria-label="View"
                                                        data-pjax="0">
                                                        <svg aria-hidden="true"
                                                            style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1.125em"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                            <path fill="currentColor"
                                                                d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if (canAccess('Trading Edit'))
                                                    <a href="{{ route('admin.users-edit', $user->id) }}"
                                                        style="color: white" title="Update" aria-label="Update"
                                                        data-pjax="0">
                                                        <svg aria-hidden="true"
                                                            style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1em"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                            <path fill="currentColor"
                                                                d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if (canAccess('Trading Copy'))
                                                    <a href="{{ route('admin.users-copy', $user->id) }}"
                                                        style="color: white" title="Copy" aria-label="Copy"
                                                        data-pjax="0">
                                                        <i class="fa-solid fa-copy"></i>
                                                    </a>
                                                @endif
                                                
                                                @if (canAccess('Trading Profile'))
                                                    <a href="{{ route('admin.comex-margins', $user->id) }}"
                                                        style="color: white" title="Comex Margins" aria-label="Comex"
                                                        data-pjax="0">
                                                        <i class="fa-solid fa-gear"></i>
                                                    </a>
                                                @endif
                                               </br>
                                                    <a href="{{ route('admin.create-funds', $user->id) }}"
                                                        style="color: white" title="Deposit">
                                                        <img src="{{ asset('admin-assets/images/in.png') }}"
                                                            style="color: white" width="20"></a>
                                              

                                                @if (canAccess('Withdraw Fund'))

                                                    <a href="{{ route('admin.create-funds-wd', $user->id) }}"
                                                        style="color: white" title="Withdraw">
                                                        <img src="{{ asset('admin-assets/images/out.png') }}"
                                                            width="20"></a>
                                                @endif
                                                @if (canAccess('Trading Profile'))
                                                    <a href="{{ route('admin.wf-status', $user->id) }}"
                                                        style="color: white" title="Enable/Disable Withdrawal Form">
                                                        <i class="fa-solid fa-rupee-sign py-3"
                                                            style="color: #00bcd4;"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $user->user_id }}</td>
                                            <td>{{ $user->FullName }}</td>
                                            <td>{{ $user->Username }}</td>
                                            @php 
                                           
                                             $newBrokrage=  $user->bidamount
                                                    ->whereBetween('updated_at', [
                                                        now()->startOfWeek(Carbon\Carbon::MONDAY),
                                                        now()->endOfWeek(Carbon\Carbon::FRIDAY)
                                                    ])->where('Isactive',2)
                                                    ->sum('brokrage');

                                        //    $pl = $user->weekly_profit_loss - $newBrokrage;

                                        //         $data = getNetBalance($user->id, $pl);

                                        //         $netBalance = $data['net_balance'];
                                                
                                        //         if( ($netBalance!= $user->balance)){
                                        //            DB::table('tradeuser')->where('id', $user->id)->update([
                                        //                         'balance' => $netBalance
                                        //                     ]);
                                        //         }
                                            @endphp
                                            {{-- <td>{{ ($netBalance==$user->balance) ? true : $netBalance }}</td> --}}
                                            <td>{{ $user->balance }}</td>
                                            <td>{{ number_format($user->weekly_profit_loss,2) }}</td>
                                           
                                            <td> {{  $newBrokrage  }}</td>
                                            <td>{{ number_format($user->weekly_profit_loss- $newBrokrage,2) }}</td>
                                            <td>{{ $user->broker_id == 0 ? 'Admin' : $user->broker->UserName ?? '' }}</td>
                                            <td>{{ $user->IsDemo ? 'Yes' : 'No' }}</td>
                                            <td>
                                                <form action="{{ route('admin.users-toggle-status', $user->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" onclick="return confirmStatusChange();"
                                                        class="btn btn-{{ $user->IsActive ? 'success' : 'danger' }} btn-sm">
                                                        {{-- <i class="fas fa-{{ $user->is_active ? 'check' : 'times' }}"></i> --}}
                                                        {{ $user->IsActive ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                              <a href="{{ route('admin.delete-user-confirm', $user->id) }}" 
                                                    onclick="return confirmDelete();">
                                                        <button type="button" 
                                                                title="Delete User" 
                                                                class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
@endsection

@section('scripts')


    <script type="text/javascript">
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }

        function confirmStatusChange() {
            return confirm('Are you sure you want to change the status of this user?');
        }
    </script>

    <script>
        // $(document).ready(function() {
        //     // Initialize DataTables
        //     $('.table').DataTable();
        // });
    </script>
@endsection
