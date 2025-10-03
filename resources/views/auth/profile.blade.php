@extends('layout.main')

@section('title', 'Profile')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-border">
                        <h4 class="mb-sm-0">Profile</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End page title -->

            <div class="row">
                <div class="col-xxl-3">
                    <div class="card ribbon-box border shadow-none mb-lg-0">
                        <div class="card-body text-center">
                            <div class="ribbon-two ribbon-two-primary">
                                <span>{{ ucfirst(auth()->user()->getRoleNames()->first()) }}</span></div>
                            <div class="mt-3">
                                <div class="avatar-lg mx-auto">
                                    @if (auth()->user()->image_url)
                                        <img src="{{ auth()->user()->image_url }}" alt="Profile Image"
                                            class="avatar-lg rounded-circle img-thumbnail">
                                    @else
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <h5 class="mt-3 mb-1">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h5>
                                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-9">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab"
                                        id="personalDetailsLink">
                                        <i class="fas fa-home"></i> Personal Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab"
                                        id="changePasswordLink">
                                        <i class="far fa-user"></i> Change Password
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                    <form id="ajaxform" action="{{ route('update.user', $user->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="firstnameInput" class="form-label">First Name</label>
                                                    <input type="text" class="form-control enhanced-dropdown" id="firstnameInput"
                                                        name="first_name" placeholder="Enter your firstname"
                                                        value="{{ $user->first_name }}">
                                                </div>
                                                @error('first_name')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="lastnameInput" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control enhanced-dropdown" id="lastnameInput"
                                                        name="last_name" placeholder="Enter your lastname"
                                                        value="{{ $user->last_name }}">
                                                </div>
                                                @error('last_name')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>


                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="phonenumberInput" class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control enhanced-dropdown" id="phonenumberInput"
                                                        name="phone_number" placeholder="Enter your phone number"
                                                        value="{{ $user->phone_number }}">

                                                </div>
                                                @error('phone_number')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>


                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="emailInput" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control enhanced-dropdown" id="emailInput"
                                                        name="email" placeholder="Enter your email" readonly
                                                        value="{{ $user->email }}">
                                                </div>
                                                @error('email')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="imageInput" class="form-label">Profile Image</label>
                                                    <input type="file" class="form-control enhanced-dropdown" id="imageInput"
                                                        name="image_url" accept="image/*">
                                                    <div class="form-text">Choose an image file (JPEG, PNG, JPG, GIF). Max
                                                        size: 2MB</div>
                                                    @if ($user->image_url)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current image:</small>
                                                            <img src="{{ $user->image_url }}" alt="Current Profile"
                                                                class="img-thumbnail mt-1" style="max-width: 100px;">
                                                        </div>
                                                    @endif
                                                </div>
                                                @error('image_url')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
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
                                <div class="tab-pane" id="changePassword" role="tabpanel">
                                    <form action="{{ route('update.password', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="row g-2">
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                                    <input type="password" class="form-control enhanced-dropdown" name="current_password"
                                                        id="oldpasswordInput" placeholder="Enter current password">
                                                </div>
                                                @error('current_password')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="newpasswordInput" class="form-label">New Password*</label>
                                                    <input type="password" class="form-control enhanced-dropdown" name="new_password"
                                                        id="newpasswordInput" placeholder="Enter new password">
                                                </div>
                                                @error('new_password')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="confirmpasswordInput" class="form-label">Confirm
                                                        Password*</label>
                                                    <input type="password" class="form-control enhanced-dropdown"
                                                        name="new_password_confirmation" id="confirmpasswordInput"
                                                        placeholder="Confirm password">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            
                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success">Change
                                                        Password</button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @section('scripts')
            <script>
                @if (session('success'))
                    toastr.success('{{ session('success') }}');
                @endif
                @if (session('error'))
                    toastr.error('{{ session('error') }}');
                @endif


                $(document).ready(function() {
                    // Bootstrap tabs will handle the tab switching automatically
                    // No custom JavaScript needed for tab functionality
                });
            </script>
        @endsection
