@extends('layouts.admin')

@section('title', 'Change Password')

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
                    <div class="card-header">


                        <h4 class="card-title">Change Password</h4>
                        <p class="card-category">Update your login password</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.update-user-password') }}">
                            @csrf
                            <input type="hidden" name="Login" value="1">
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Current Transaction Password</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                        @error('current_password')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Change Password</label>
                                        <input type="password" name="new_password" class="form-control new_password" required>
                                    <input type="hidden" name="user" value="{{ collect(request()->query())->keys()[0] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Confirm New Transaction Password</label>
                                        <input type="password" name="new_password_confirmation" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div> --}}
                            <button type="submit" class="btn btn-primary pull-right">Update</button>
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
            var newPassword = $(".new_password").val();

            // Validate
            if (!newPassword) {
                e.preventDefault(); // Prevent submission only on error
                alert('All fields are required');
                return false;
            }
             alert('Password changed Successfully.');
        });
    });
</script>
@endsection
