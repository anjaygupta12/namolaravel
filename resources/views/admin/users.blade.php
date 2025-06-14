@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Users Management</h4>
                        <p class="card-category">View and manageUsers Management</p>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input name="ctl00$ContentPlaceHolder1$txtSearchName" type="text"
                                        id="ContentPlaceHolder1_txtSearchName" class="form-control"
                                        placeholder="Search by name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input name="ctl00$ContentPlaceHolder1$txtSearchUsername" type="text"
                                        id="ContentPlaceHolder1_txtSearchUsername" class="form-control"
                                        placeholder="Search by username">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Account Status</label>
                                    <select name="ctl00$ContentPlaceHolder1$ddlSearchStatus"
                                        id="ContentPlaceHolder1_ddlSearchStatus" class="form-control">
                                        <option selected="selected" value="">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <input type="submit" name="ctl00$ContentPlaceHolder1$btnSearch" value="Search"
                                    id="ContentPlaceHolder1_btnSearch" class="btn btn-primary">
                                <input type="submit" name="ctl00$ContentPlaceHolder1$btnReset" value="Reset"
                                    id="ContentPlaceHolder1_btnReset" class="btn btn-secondary">
                             <a href="{{ route('admin.user-create')}}" class="btn btn-success float-right">add new User</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th scope="col">action</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Ledger Balance</th>
                                        <th scope="col">Gross P/L</th>
                                        <th scope="col">Brokerage</th>
                                        <th scope="col">Net P/L</th>
                                        <th scope="col">Admin</th>
                                        <th scope="col">Demo Account?</th>
                                        <th scope="col">Account Status</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a href="mcxusers-views.aspx?userid=2004" title="View" aria-label="View"
                                                data-pjax="0">
                                                <svg aria-hidden="true"
                                                    style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1.125em"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                    <path fill="currentColor"
                                                        d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="edit-user.aspx?userid=2004" title="Update" aria-label="Update"
                                                data-pjax="0">
                                                <svg aria-hidden="true"
                                                    style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1em"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                    <path fill="currentColor"
                                                        d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="copy-user.aspx?userid=2004" title="Copy" aria-label="Copy"
                                                data-pjax="0">
                                                <i class="fa-solid fa-copy"></i>
                                            </a>
                                            <a href="comex-margins.aspx?userid=2004" title="Comex Margins"
                                                aria-label="Comex" data-pjax="0">
                                                <i class="fa-solid fa-gear"></i>
                                            </a>
                                            <a href="Create-Funds.aspx?userid=2004" title="Deposit">
                                                <img src="images/in.png"></a>
                                            <a href="Create-Funds-Wd.aspx?userid=6349" title="Withdraw">
                                                <img src="images/out.png"></a>
                                            <a href="wf-status.aspx?userid=2004" title="Enable/Disable Withdrawal Form">
                                                <i class="fa-solid fa-rupee-sign" style="color: #00bcd4;"></i>
                                            </a>
                                        </td>
                                        <td>Anshul</td>
                                        <td>Ab01</td>
                                        <td>1580</td>
                                        <td>15410</td>
                                        <td>1201</td>
                                        <td>100</td>
                                        <td>Admin</td>
                                        <td>False</td>
                                        <td>Active</td>
                                        <td>
                                            <a onclick="return confirmStatusChange();"
                                                id="ContentPlaceHolder1_gvUsers_btnToggleStatus_0"
                                                class="btn btn-success btn-sm"
                                                href="javascript:__doPostBack('ctl00$ContentPlaceHolder1$gvUsers$ctl02$btnToggleStatus','')">
                                                <i class="fas fa-check"></i>
                                                Active</a>
                                        </td>
                                        <td>
                                            <a id="ContentPlaceHolder1_gvUsers_btnEdit_0" title="Edit User"
                                                class="btn btn-primary btn-sm"
                                                href="javascript:__doPostBack('ctl00$ContentPlaceHolder1$gvUsers$ctl02$btnEdit','')">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a onclick="return confirmDelete();"
                                                id="ContentPlaceHolder1_gvUsers_btnDelete_0" title="Delete User"
                                                class="btn btn-danger btn-sm"
                                                href="javascript:__doPostBack('ctl00$ContentPlaceHolder1$gvUsers$ctl02$btnDelete','')">
                                                <i class="fas fa-trash"></i>
                                            </a>
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
            $('.table').DataTable();
        });
    </script>
@endsection
