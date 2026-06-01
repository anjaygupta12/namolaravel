@extends('layouts.admin')

@section('title', 'Chnage Auth code')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Change Auth Code</h4>
                     
                        <a href="https://api-t1.fyers.in/api/v3/generate-authcode?client_id=XAJU3A9AXW-100&redirect_uri=https://trade.fyers.in/api-login/redirect-uri/index.html&response_type=code&state=sample_state" target="_blank" class="btn btn-info">Change now</a>
                   
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.change-authcode.update') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Enter Auth Code</label>
                                        <input name="code" class="form-control code">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">Update </button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('form').submit(function (e) {
            // Get form values
            var newPassword = $(".code").val();

            // Validate
            if (!newPassword) {
                e.preventDefault();
                alert(' fields id required');
                return false;
            }
        });
    });
</script>

@endsection
