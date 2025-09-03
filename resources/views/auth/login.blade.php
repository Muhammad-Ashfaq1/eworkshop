<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Management Portal - Sign In</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/auth/css/login.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="government-logo">
                <img src="{{ asset('assets/auth/images/government-logo-small.png') }}" alt="Government of Pakistan Logo"
                    class="gov-logo">
            </div>

            <div class="content">
                <h1 class="main-heading">Sign in to LWMC E-Workshop</h1>

                <img src="{{ asset('assets/auth/images/waste-workers-illustration.png') }}"
                    alt="Waste management workers illustration" class="workers-img">

            </div>
        </div>

        <div class="right-section">
            <div class="login-form-container">
                <div class="portal-header">
                    <img src="{{ 'assets/auth/images/recycle-icon.png' }}" alt="Recycle icon" class="recycle-logo">
                    <div class="portal-title">
                        <h2 class="urdu-title"></h2>
                        <h3 class="english-title"></h3>
                    </div>
                </div>

                <form class="login-form" action="{{ route('login.action') }}" method="POST" id="loginform">
                    @csrf
                    <div class="input-group">
                        <label for="login-id" class="form-label">CNIC or Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon user-icon"></span>
                            <input type="text" id="login-id" placeholder="Enter your Email" class="form-input"
                                name="email" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon lock-icon"></span>
                            <input type="password" id="password" placeholder="Enter your password" class="form-input"
                                name="password" required value="{{ old('password') }}">
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" class="checkbox" value="true" id="auth-remember-check"
                                name="remember_me">
                            Remember me
                        </label>
                        {{-- <a href="{{ route('auth.forgot.password') }}" class="forgot-password">Forgot password?</a> --}}
                    </div>

                    <button type="submit" class="sign-in-btn">Sign in</button>

                    {{-- <div class="signup-link">
                        <span>New user? </span><a href="{{ route('register') }}">Sign up</a>
                    </div> --}}
                </form>

                <div class="footer">
                    <div class="footer-logo">
                        <img src="{{ asset('assets/auth/images/government-logo-small.png') }}" alt="Government Logo"
                            class="footer-gov-logo">
                    </div>
                    <div class="footer-text">
                        <span>Managed by Ministry of Climate Change,</span>
                        <span>Government of Pakistan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

    <!-- Toastr JavaScript (requires jQuery to be loaded first) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <!-- Include jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

    <script>
        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        @if (session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        @if ($errors->has('email'))
            toastr.error('{{ $errors->first('email') }}');
        @endif
        // jquery validation
        $(document).ready(function() {
            $('#loginform').validate({
                rules: {
                    email: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                },
                messages: {
                    email: {
                        required: "Please enter your email address."
                    },
                    password: {
                        required: "Please enter your password."
                    }

                }
            });
        });
    </script>
</body>

</html>

</html>
