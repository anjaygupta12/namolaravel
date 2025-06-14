<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Namo Traders - Login</title>
    <meta name="description" content="Namo Traders - Trading Platform">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- App Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>

<body>
    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">
        <div class="login-form mt-1">
            <div class="section">
                <div class="text-center">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="logo">
                    <h4 class="mt-3">Namo Traders</h4>
                    <h5>Log in to your account</h5>
                </div>
            </div>
            <div class="section mt-1 mb-5">
                <form action="{{ route('home') }}" method="POST">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-links mt-2">
                        <div>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register Now</a>
                        </div>
                        <div>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="form-button-group">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="phone">Phone</label>
                                <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="newUsername">Username</label>
                                <input type="text" class="form-control" id="newUsername" placeholder="Choose a username" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="newPassword">Password</label>
                                <input type="password" class="form-control" id="newPassword" placeholder="Choose a password" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="confirmPassword">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="termsCheckbox" required>
                            <label class="custom-control-label" for="termsCheckbox">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a></label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Register</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="recoveryEmail">Email</label>
                                <input type="email" class="form-control" id="recoveryEmail" placeholder="Enter your registered email" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-muted">We will send a password reset link to your registered email address.</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send Reset Link</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-1">
                        <h5>1. Introduction</h5>
                        <p>Welcome to Namo Traders. By using our services, you agree to these terms and conditions.</p>
                        
                        <h5>2. Account Registration</h5>
                        <p>You must provide accurate information when registering. You are responsible for maintaining the confidentiality of your account.</p>
                        
                        <h5>3. Trading Risks</h5>
                        <p>Trading involves significant risk of loss. Past performance is not indicative of future results.</p>
                        
                        <h5>4. Privacy Policy</h5>
                        <p>We collect and use your information as described in our Privacy Policy.</p>
                        
                        <h5>5. Termination</h5>
                        <p>We reserve the right to terminate or suspend your account at our discretion.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/lib/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Form validation for register form
            $('.modal-footer .btn-primary').on('click', function() {
                var modal = $(this).closest('.modal');
                var form = modal.find('form');
                
                if (form[0].checkValidity()) {
                    if (modal.attr('id') === 'registerModal') {
                        // Check if passwords match
                        var password = $('#newPassword').val();
                        var confirmPassword = $('#confirmPassword').val();
                        
                        if (password !== confirmPassword) {
                            alert('Passwords do not match!');
                            return false;
                        }
                        
                        // Submit register form
                        alert('Registration successful! Please check your email to verify your account.');
                        modal.modal('hide');
                    } else if (modal.attr('id') === 'forgotPasswordModal') {
                        // Submit forgot password form
                        alert('Password reset link has been sent to your email.');
                        modal.modal('hide');
                    }
                } else {
                    form[0].reportValidity();
                }
            });
        });
    </script>
</body>

</html>
