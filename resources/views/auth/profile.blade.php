@extends('layout.main')


@section('content')
<div class="row">

</div>
<div class="col-xxl-9">
        <div class="card mt-xxl-n5">
            <div class="card-header">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" id="personalDetailsLink">
                            <i class="fas fa-home"></i> Personal Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab" id="changePasswordLink">
                            <i class="far fa-user"></i> Change Password
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <form id="ajaxform"action="{{ route('update.user',$user->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                    <label for="firstnameInput" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstnameInput"
                                           name="first_name"
                                           placeholder="Enter your firstname"
                                           value="{{ $user->first_name }}">
                                </div>
                                  @error('first_name')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                @enderror

                                </div>

                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="lastnameInput" class="form-label">Last Name</label>
                                     <input type="text" class="form-control" id="lastnameInput"
                                           name="last_name"
                                           placeholder="Enter your lastname"
                                           value="{{  $user->last_name }}">
                                    </div>
                                    @error('last_name')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    @enderror
                                    </div>


                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="phonenumberInput" class="form-label">Phone Number</label>
                                      <input type="text" class="form-control" id="phonenumberInput"
                                           name="phone_number"
                                           placeholder="Enter your phone number"
                                           value="{{$user->phone_number}}">

                                    </div>
                                       @error('phone_number')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                @enderror
                                    </div>


                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="emailInput" class="form-label">Email Address</label>
                                     <input type="email" class="form-control" id="emailInput"
                                           name="email"
                                           placeholder="Enter your email"
                                           readonly
                                           value="{{  $user->email}}">
                                    </div>
                                       @error('email')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                @enderror
                                    </div>
                                      </div>

                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">

                                        <button type="submit" class="btn btn-primary">Updates</button>

                                        <button type="button" class="btn btn-soft-success">Cancel</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane d-none" id="changePassword" role="tabpanel">
                        <form action="{{ route('update.password', $user->id) }}" method="POST">
                            @csrf
                            <div class="row g-2">
                                <div class="col-lg-4">
                                    <div>
                                        <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                        <input type="password" class="form-control"name="current_password" id="oldpasswordInput" placeholder="Enter current password">
                                    </div>
                                      @error('current_password')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                @enderror
                                </div>

                                <!--end col-->
                                <div class="col-lg-4">
                                    <div>
                                        <label for="newpasswordInput" class="form-label">New Password*</label>
                                        <input type="password" class="form-control" name="new_password"id="newpasswordInput" placeholder="Enter new password">
                                    </div>
                                        @error('new_password')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                @enderror
                                </div>

                                <!--end col-->
                                <div class="col-lg-4">
                                    <div>
                                        <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                        <input type="password" class="form-control"name="new_password_confirmation" id="confirmpasswordInput" placeholder="Confirm password">
                                    </div>

                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <a href="javascript:void(0);" class="link-primary text-decoration-underline">Forgot Password ?</a>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">Change Password</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                        <div class="mt-4 mb-3 border-bottom pb-2">
                            <div class="float-end">
                                <a href="javascript:void(0);" class="link-primary">All Logout</a>
                            </div>
                            <h5 class="card-title">Login History</h5>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                    <i class="ri-smartphone-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>iPhone 12 Pro</h6>
                                <p class="text-muted mb-0">Los Angeles, United States - March 16 at 2:47PM</p>
                            </div>
                            <div>
                                <a href="javascript:void(0);">Logout</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                    <i class="ri-tablet-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Apple iPad Pro</h6>
                                <p class="text-muted mb-0">Washington, United States - November 06 at 10:43AM</p>
                            </div>
                            <div>
                                <a href="javascript:void(0);">Logout</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                    <i class="ri-smartphone-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Galaxy S21 Ultra 5G</h6>
                                <p class="text-muted mb-0">Conneticut, United States - June 12 at 3:24PM</p>
                            </div>
                            <div>
                                <a href="javascript:void(0);">Logout</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                    <i class="ri-macbook-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Dell Inspiron 14</h6>
                                <p class="text-muted mb-0">Phoenix, United States - July 26 at 8:10AM</p>
                            </div>
                            <div>
                                <a href="javascript:void(0);">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()
@section('scripts')
    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif


        $(document).ready(function() {
            $('#changePasswordLink').click(function() {
                $('#changePassword').removeClass('d-none');
                $('#personalDetails').addClass('d-none');
            });

            $('#personalDetailsLink').click(function() {
                $('#changePassword').addClass('d-none');
                $('#personalDetails').removeClass('d-none');
            });
        });
    </script>
@endsection
