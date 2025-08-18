@extends('auth.auth-main')
{{-- @dd($token) --}}
@section('formContent')
<div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Reset Password</h5>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" novalidate
                                    action="{{ route('reset.password')}}" method=POST>
                                    @csrf
                                <input name="email" value="{{ $email ?? ''}}" type="hidden">
                                <input type="hidden"  name="token" value="{{ $token }}">
                                </div>
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
                                                    <input type="password"name="password_confirmation"  class="form-control pe-5 password-input"
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
                                                <button class="btn btn-success w-100" type="submit">Update</button>
                                            </div>
                                            </div>
                                            </form>

                                        </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
@endsection
@section('scripts')
<script>
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif
</script>
@endsection
