<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Namo Traders</title>
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

        .register-container {
            max-width: 420px;
            margin: 4% auto;
            background: #2c2f33;
            padding: 2.5rem 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
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

        .text-muted {
            color: #aaa !important;
        }
    </style>
</head>

<body>
    <div class="register-container text-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="logo mb-3">
        <h4 class="mb-1">Namo Traders</h4>

        <p class="mb-4 text-muted">Create your account</p>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('register.submit') }}" method="POST" id="registerForm">
            @csrf

            <div class="form-floating mb-3">
                <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" required>
                <label for="mobile">Mobile</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name"
                    required>
                <label for="full_name">Full Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email ID"
                    required>
                <label for="email">Email ID</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    required>
                <label for="password">Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="referral" name="referral" placeholder="Referral Code">
                <label for="referral">Referral Code (optional)</label>
            </div>

            <div class="form-check text-start mb-3">
                <input class="form-check-input" type="checkbox" value="" id="termsCheckbox" required>
                <label class="form-check-label" for="termsCheckbox">
                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms &
                        Conditions</a>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>

            <div class="form-links">
                <small>Already have an account? <a href="{{ route('login') }}">Login</a></small>
            </div>
        </form>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>1. Welcome to Namo Traders. By using our services, you agree to these terms.</p>
                    <p>2. You must provide accurate registration info.</p>
                    <p>3. Trading involves financial risk.</p>
                    <p>4. We respect your privacy.</p>
                    <p>5. We can suspend accounts if needed.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (!document.getElementById('termsCheckbox').checked) {
                e.preventDefault();
                alert('You must agree to the terms and conditions.');
            }
        });
    </script>
</body>

</html>
