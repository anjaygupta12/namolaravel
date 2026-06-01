@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
    <div class="user-changePassword">
        <div class="card ">
            <div class="card-header card-header-success card-header-text">
                <div class="card-text">
                    <h4 class="card-title">Restore Sell</h4>
                </div>
            </div>
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

            <div class="card-body ">
                <div style="color:red"></div>
                <div>
                    You are about to restore sell of {{ $trade->Lots }} lots of {{ $trade->Symbol }} of
                    Brokerage and Profit/Loss of the Buy Trade will
                    be refunded to the client and the Buy Trade will be removed. Enter Transaction Password to continue.
                </div>
                <form action="{{ route('admin.trade-restore-update') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group field-changepasswordform-old_password required">
                                <input type="hidden" name="id" value=" {{ $trade->Pk_id }}">
                                <input type="hidden" name="userid" value=" {{ request()->route('userid') }}">
                                <label class="control-label" for="changepasswordform-old_password">Transaction
                                    Password</label>
                                <input type="password" id="old_password" class="form-control" name="transaction_password"
                                    aria-required="true">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-success">Restore Trade</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
@endsection
