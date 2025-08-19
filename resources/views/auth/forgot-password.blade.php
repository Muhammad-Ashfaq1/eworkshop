@extends('auth.auth-main')
@section('title', 'Forgot Password')
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
                                    action="{{ route('auth.forgot.password.link') }}" method=POST id="js-forgotPasswordForm">
                                    @csrf
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

                                            <div class="mb-4">
                                                <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the
                                                    Velzon <a href="#"
                                                        class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                        of Use</a></p>
                                            </div>



                                            <div class="mt-4">
                                                <button class="btn btn-success w-100" type="submit">Forgot Password</button>
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

@endsection


@section('scripts')

 <script>
         $(document).ready(function() {
            console.log('hello from forgot page');
            $('#js-forgotPasswordForm').on('submit', function(e){
                e.preventDefault();
                var url = $(this).attr('action');
                // var email = $('#useremail').val();
                var data = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response){
                        if(response.success){
                            toastr.success(response.success);
                            $('#useremail').val('');
                        }else{
                            toastr.error(response.error);
                            $('#useremail').val('');
                        }
                    },
                    error: function(xhr, status, error){
                        console.log(xhr.responseText);
                    }
                });
            })
         });
    </script>
@endsection
