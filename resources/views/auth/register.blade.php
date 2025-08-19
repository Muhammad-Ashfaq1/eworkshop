@extends('auth.auth-main')
@section('title', 'Register')
@section('formContent')
<div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create New Account</h5>
                                    <p class="text-muted">Get your free velzon account now</p>
                                </div>
                                <div class="p-2 mt-4">

                                    <form class="needs-validation" novalidate
                                    action="{{ route('register.user')}}" method=POST>
                                    @csrf

                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">First Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username"
                                        placeholder="Enter username" name="first_name"required value="{{ old('first_name') }}">
                                    <div class="invalid-feedback">
                                        Please enter First Name
                                    </div>
                                    @error('first_name')
                                    {{ $message }}
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Last Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control"name="last_name" id="username"
                                        placeholder="Enter username" value="{{ old('last_name') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter LastName
                                    </div>
                                     @error('last_name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="useremail" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="useremail"
                                        placeholder="Enter email address"name="email" value="{{ old('email') }}" required>
                                    <div class="invalid-feedback">
                                        Please enter email
                                    </div>
                                      @error('email')
                                     <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                     <div class="mb-3">
                                    <label for="useremail" class="form-label">Phone Number<span
                                            class="text-danger">*</span></label>
                                    <input type="number" maxlength="12" value="{{ old('phone_number') }}" class="form-control" id="useremail"
                                        placeholder="Enter email address"name="phone_number" required>
                                    <div class="invalid-feedback">
                                        Please enter phone number
                                    </div>
                                    @error('phone_number')
                                    <div class="text-danger small">{{ $message }}</div>
                                    @enderror


                                    <div class="mb-3">
                                        <label class="form-label" for="password-input">Password</label>
                                        <div class="position-relative auth-pass-inputgroup">
                                            <input type="password" class="form-control pe-5 password-input"
                                                placeholder="Enter password" id="password-input"
                                                name="password"
                                                value="{{ old('password') }}"
                                                required>
                                            <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                        type="button" id="password-addon"><i
                                                            class="ri-eye-fill align-middle"></i></button>
                                                    <div class="invalid-feedback">
                                                        Please enter password
                                                    </div>
                                                    @error('password')
                                            <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="password-input">Confirm Password</label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input type="password"name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control pe-5 password-input"
                                                        onpaste="return false" placeholder="Enter password"
                                                        id="password-input" aria-describedby="passwordInput"name="password"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                        type="button" id="password-addon"><i
                                                            class="ri-eye-fill align-middle"></i></button>
                                                    <div class="invalid-feedback">
                                                        Confirm Password
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the
                                                    Velzon <a href="#"
                                                        class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                        of Use</a></p>
                                            </div>

                                            <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                                <h5 class="fs-13">Password must contain:</h5>
                                                <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
                                                <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)
                                                </p>
                                                <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter
                                                    (A-Z)</p>
                                                <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
                                            </div>

                                            <div class="mt-4">
                                                <button class="btn btn-success w-100" type="submit">Sign Up</button>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <div class="signin-other-title">
                                                    <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>
                                                </div>

                                                <div>
                                                    <button type="button"
                                                        class="btn btn-primary btn-icon waves-effect waves-light"><i
                                                            class="ri-facebook-fill fs-16"></i></button>
                                                    <button type="button"
                                                        class="btn btn-danger btn-icon waves-effect waves-light"><i
                                                            class="ri-google-fill fs-16"></i></button>
                                                    <button type="button"
                                                        class="btn btn-dark btn-icon waves-effect waves-light"><i
                                                            class="ri-github-fill fs-16"></i></button>
                                                    <button type="button"
                                                        class="btn btn-info btn-icon waves-effect waves-light"><i
                                                            class="ri-twitter-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                            </form>

                                        </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Already have an account ? <a href="{{ route('login') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
@endsection
