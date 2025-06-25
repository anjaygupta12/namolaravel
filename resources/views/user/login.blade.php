<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Namo Traders - Login</title>
    <meta name="description" content="Namo Traders - Trading Platform">

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(135deg, #2d2d2d, #1a1a1a);
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 5% auto;
            background: #2c2f33;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .logo {
            width: 120px;
        }

        .form-control {
            background: #1e1e1e;
            color: #fff;
            border: none;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        .btn-primary {
            background: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .form-links a {
            color: #ccc;
            text-decoration: underline;
        }

        .modal-content {
            background: #2c2f33;
            color: #fff;
        }

        .modal-header,
        .modal-footer {
            border: none;
        }

        .clear-input ion-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-container text-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="logo mb-3">
        <h4 class="mb-1">Namo Traders</h4>
        {{-- @if ($errors->has('email'))
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
            </div>
        @endif --}}
       @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


        <p class="mb-4 ">Log in to your account</p>

        <form action="{{ route('user.login') }}" method="POST">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="email" placeholder="Username"
                    required>
                <label for="email">Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    required>
                <label for="password">Password</label>
            </div>

            <div class="d-flex justify-content-between mb-3 form-links">
                {{-- <a href="{{route('register')}}" >Register</a> --}}
                <a href="#" >Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.modal-footer .btn-primary').on('click', function() {
                var modal = $(this).closest('.modal');
                var form = modal.find('form')[0];

                if (form.checkValidity()) {
                    if (modal.attr('id') === 'registerModal') {
                        var pwd = $('#newPassword').val();
                        var confirmPwd = $('#confirmPassword').val();
                        if (pwd !== confirmPwd) {
                            alert('Passwords do not match!');
                            return false;
                        }
                        alert('Registration successful!');
                    } else {
                        alert('Password reset link sent!');
                    }
                    modal.modal('hide');
                } else {
                    form.reportValidity();
                }
            });
        });
    </script>
</body>

</html>
