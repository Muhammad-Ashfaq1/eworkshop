@extends('layout.main')

@section('title', 'Profile')

@section('content')
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
                                <label class="avatar-lg mx-auto profile-avatar-upload d-block mb-0"
                                       for="imageInput"
                                       id="profileAvatarPreview"
                                       title="Change profile photo">
                                    @if (auth()->user()->image_url)
                                        <img src="{{ auth()->user()->image_url }}" alt="Profile Image"
                                            class="avatar-lg rounded-circle img-thumbnail" id="profileAvatarImage">
                                    @else
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20" id="profileAvatarInitial">
                                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}
                                        </div>
                                    @endif
                                </label>
                                <div class="text-danger mt-2 small d-none" id="profileImageError"></div>
                                @error('image_url')
                                    <div class="alert alert-danger mt-2 py-1 px-2 small">{{ $message }}</div>
                                @enderror
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
                                        enctype="multipart/form-data" novalidate>
                                        @csrf
                                        <input type="file"
                                               class="d-none"
                                               id="imageInput"
                                               name="image_url"
                                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="firstnameInput" class="form-label">
                                                        First Name <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                           class="form-control enhanced-dropdown @error('first_name') is-invalid @enderror"
                                                           id="firstnameInput"
                                                           name="first_name"
                                                           placeholder="Enter your firstname"
                                                           value="{{ old('first_name', $user->first_name) }}"
                                                           required
                                                           maxlength="75">
                                                    <div class="invalid-feedback account-field-error @error('first_name') is-visible @enderror"
                                                         data-error-for="first_name"
                                                         @error('first_name') style="display:block" @enderror>
                                                        @error('first_name'){{ $message }}@enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="lastnameInput" class="form-label">
                                                        Last Name <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                           class="form-control enhanced-dropdown @error('last_name') is-invalid @enderror"
                                                           id="lastnameInput"
                                                           name="last_name"
                                                           placeholder="Enter your lastname"
                                                           value="{{ old('last_name', $user->last_name) }}"
                                                           required
                                                           maxlength="75">
                                                    <div class="invalid-feedback account-field-error @error('last_name') is-visible @enderror"
                                                         data-error-for="last_name"
                                                         @error('last_name') style="display:block" @enderror>
                                                        @error('last_name'){{ $message }}@enderror
                                                    </div>
                                                </div>
                                            </div>


                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="phonenumberInput" class="form-label">Phone Number</label>
                                                    <input type="text"
                                                           class="form-control enhanced-dropdown @error('phone_number') is-invalid @enderror"
                                                           id="phonenumberInput"
                                                           name="phone_number"
                                                           placeholder="Enter your phone number"
                                                           value="{{ old('phone_number', $user->phone_number) }}"
                                                           maxlength="30">
                                                    <div class="invalid-feedback account-field-error @error('phone_number') is-visible @enderror"
                                                         data-error-for="phone_number"
                                                         @error('phone_number') style="display:block" @enderror>
                                                        @error('phone_number'){{ $message }}@enderror
                                                    </div>
                                                </div>
                                            </div>


                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="emailInput" class="form-label">
                                                        Email <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email"
                                                           class="form-control enhanced-dropdown profile-email-disabled"
                                                           id="emailInput"
                                                           value="{{ $user->email }}"
                                                           disabled>
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
                                <div class="tab-pane" id="changePassword" role="tabpanel">
                                    <form action="{{ route('update.password', $user->id) }}"
                                          method="POST"
                                          id="changePasswordForm"
                                          autocomplete="off"
                                          novalidate>
                                        @csrf
                                        <div class="row g-2">
                                            <div class="col-lg-4">
                                                <label for="oldpasswordInput" class="form-label">Old Password <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge profile-password-group @error('current_password') is-invalid @enderror">
                                                    <input type="password"
                                                           class="form-control enhanced-dropdown @error('current_password') is-invalid @enderror"
                                                           name="current_password"
                                                           id="oldpasswordInput"
                                                           placeholder="Enter current password"
                                                           required
                                                           autocomplete="current-password">
                                                    <span class="input-group-text profile-password-toggle cursor-pointer" role="button" tabindex="0" title="Show/Hide password">
                                                        <i class="ri-eye-off-line"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback account-field-error @error('current_password') is-visible @enderror"
                                                     data-error-for="current_password"
                                                     @error('current_password') style="display:block" @enderror>
                                                    @error('current_password'){{ $message }}@enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <label for="newpasswordInput" class="form-label">New Password <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge profile-password-group @error('new_password') is-invalid @enderror">
                                                    <input type="password"
                                                           class="form-control enhanced-dropdown @error('new_password') is-invalid @enderror"
                                                           name="new_password"
                                                           id="newpasswordInput"
                                                           placeholder="Enter new password"
                                                           required
                                                           minlength="8"
                                                           autocomplete="new-password">
                                                    <span class="input-group-text profile-password-toggle cursor-pointer" role="button" tabindex="0" title="Show/Hide password">
                                                        <i class="ri-eye-off-line"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback account-field-error @error('new_password') is-visible @enderror"
                                                     data-error-for="new_password"
                                                     @error('new_password') style="display:block" @enderror>
                                                    @error('new_password'){{ $message }}@enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <label for="confirmpasswordInput" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge profile-password-group">
                                                    <input type="password"
                                                           class="form-control enhanced-dropdown"
                                                           name="new_password_confirmation"
                                                           id="confirmpasswordInput"
                                                           placeholder="Confirm password"
                                                           required
                                                           minlength="8"
                                                           autocomplete="new-password">
                                                    <span class="input-group-text profile-password-toggle cursor-pointer" role="button" tabindex="0" title="Show/Hide password">
                                                        <i class="ri-eye-off-line"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback account-field-error"
                                                     data-error-for="new_password_confirmation"></div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success" id="changePasswordBtn">Change Password</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@section('styles')
    <style>
        .profile-avatar-upload {
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border-radius: 50%;
        }
        .profile-avatar-upload img,
        .profile-avatar-upload .avatar-title {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-email-disabled,
        .profile-email-disabled:disabled {
            background-color: #f9fafb !important;
            color: #6b7280 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            opacity: 1 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            min-height: unset !important;
            height: auto !important;
            padding: 8px 12px !important;
            line-height: 1.5 !important;
        }
        .profile-password-group {
            position: relative;
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            overflow: hidden;
            background: #fff !important;
            min-height: 44px;
            flex-wrap: nowrap !important;
            box-shadow: none !important;
            display: block !important;
        }
        .profile-password-group:focus-within {
            border-color: rgba(105, 108, 255, 0.55) !important;
            box-shadow: 0 0 0 0.15rem rgba(105, 108, 255, 0.15) !important;
        }
        .profile-password-group.is-invalid,
        .profile-password-group:has(.form-control.is-invalid) {
            border-color: #ef4444 !important;
            box-shadow: none !important;
        }
        .profile-password-group .form-control,
        .profile-password-group .form-control.enhanced-dropdown,
        html.pos-theme-lake .profile-password-group .form-control,
        html.pos-theme-lake .profile-password-group .form-control:focus,
        html.pos-theme-lake .profile-password-group .form-control:focus-visible,
        .profile-password-group .form-control:focus,
        .profile-password-group .form-control:focus-visible,
        .profile-password-group .form-control:hover,
        .profile-password-group .form-control.is-invalid {
            border: 0 !important;
            border-color: transparent !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            outline: none !important;
            background: transparent !important;
            background-image: none !important;
            min-height: 42px !important;
            height: 42px !important;
            transform: none !important;
            z-index: 0 !important;
            padding-right: 2.75rem !important;
            width: 100% !important;
        }
        /* Eye overlay — no side border / blue divider next to icon */
        .profile-password-group .input-group-text,
        .profile-password-group .profile-password-toggle {
            position: absolute !important;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 2;
            width: 2.75rem;
            display: flex !important;
            align-items: center;
            justify-content: center;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            border-left: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            outline: none !important;
            color: #6b7280 !important;
            cursor: pointer;
        }
        .profile-password-toggle:focus,
        .profile-password-toggle:active,
        .profile-password-toggle:hover,
        .profile-password-toggle:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            border: 0 !important;
            background: transparent !important;
            color: #6b7280 !important;
        }
        #changePasswordForm .account-field-error,
        #ajaxform .account-field-error {
            display: none;
            margin-top: 0.35rem;
            font-size: 0.8125rem;
            color: #ef4444;
        }
        #changePasswordForm .account-field-error.is-visible,
        #ajaxform .account-field-error.is-visible {
            display: block !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif
        @if (session('status'))
            toastr.success(@json(session('status')));
        @endif
        @if (session('error'))
            toastr.error(@json(session('error')));
        @endif

        $(document).ready(function () {
            @if ($errors->any() && ($errors->has('current_password') || $errors->has('new_password')))
                $('#changePasswordLink').tab('show');
            @endif

            const fileInput = document.getElementById('imageInput');
            const preview = document.getElementById('profileAvatarPreview');
            const imageError = document.getElementById('profileImageError');
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            const maxBytes = 2 * 1024 * 1024;

            function showImageError(message) {
                if (!imageError) return;
                imageError.textContent = message || '';
                imageError.classList.toggle('d-none', !message);
            }

            if (fileInput && preview) {
                fileInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    showImageError('');
                    if (!file) return;

                    const ext = (file.name.split('.').pop() || '').toLowerCase();
                    const allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    const typeOk = (file.type && allowedTypes.indexOf(file.type) !== -1) || allowedExt.indexOf(ext) !== -1;
                    if (!typeOk) {
                        this.value = '';
                        showImageError('Please upload a JPG, PNG, GIF, or WEBP image.');
                        return;
                    }
                    if (file.size > maxBytes) {
                        this.value = '';
                        showImageError('The image may not be greater than 2MB.');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Image" class="avatar-lg rounded-circle img-thumbnail" id="profileAvatarImage">';
                    };
                    reader.readAsDataURL(file);
                });
            }

            const profileForm = document.getElementById('ajaxform');
            if (profileForm) {
                const firstName = document.getElementById('firstnameInput');
                const lastName = document.getElementById('lastnameInput');
                const phone = document.getElementById('phonenumberInput');

                function setProfileError(input, name, message) {
                    const err = profileForm.querySelector('[data-error-for="' + name + '"]');
                    if (input) {
                        input.classList.toggle('is-invalid', !!message);
                    }
                    if (err) {
                        err.textContent = message || '';
                        err.classList.toggle('is-visible', !!message);
                        err.style.display = message ? 'block' : 'none';
                    }
                }

                function validateFirstName() {
                    const value = (firstName.value || '').trim();
                    if (!value) {
                        setProfileError(firstName, 'first_name', 'First name is required.');
                        return false;
                    }
                    if (value.length > 75) {
                        setProfileError(firstName, 'first_name', 'First name may not be greater than 75 characters.');
                        return false;
                    }
                    setProfileError(firstName, 'first_name', '');
                    return true;
                }

                function validateLastName() {
                    const value = (lastName.value || '').trim();
                    if (!value) {
                        setProfileError(lastName, 'last_name', 'Last name is required.');
                        return false;
                    }
                    if (value.length > 75) {
                        setProfileError(lastName, 'last_name', 'Last name may not be greater than 75 characters.');
                        return false;
                    }
                    setProfileError(lastName, 'last_name', '');
                    return true;
                }

                function validatePhone() {
                    const value = (phone.value || '').trim();
                    if (value.length > 30) {
                        setProfileError(phone, 'phone_number', 'Phone number may not be greater than 30 characters.');
                        return false;
                    }
                    setProfileError(phone, 'phone_number', '');
                    return true;
                }

                if (firstName) firstName.addEventListener('input', validateFirstName);
                if (lastName) lastName.addEventListener('input', validateLastName);
                if (phone) phone.addEventListener('input', validatePhone);

                profileForm.addEventListener('submit', function (e) {
                    const ok = validateFirstName() && validateLastName() && validatePhone();
                    if (!ok) {
                        e.preventDefault();
                        e.stopPropagation();
                        const firstInvalid = profileForm.querySelector('.form-control.is-invalid');
                        if (firstInvalid) firstInvalid.focus();
                    }
                });
            }

            document.querySelectorAll('.profile-password-toggle').forEach(function (toggleBtn) {
                const group = toggleBtn.closest('.profile-password-group');
                const input = group ? group.querySelector('input') : null;
                const icon = toggleBtn.querySelector('i');
                if (!input || !icon) return;

                function toggleVisibility() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('ri-eye-off-line');
                        icon.classList.add('ri-eye-line');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('ri-eye-line');
                        icon.classList.add('ri-eye-off-line');
                    }
                }

                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    toggleVisibility();
                });
                toggleBtn.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleVisibility();
                    }
                });
            });

            const passwordForm = document.getElementById('changePasswordForm');
            if (passwordForm) {
                const currentPassword = document.getElementById('oldpasswordInput');
                const newPassword = document.getElementById('newpasswordInput');
                const confirmPassword = document.getElementById('confirmpasswordInput');
                const submitBtn = document.getElementById('changePasswordBtn');
                const minLength = 8;

                function fieldGroup(input) {
                    return input ? input.closest('.profile-password-group') : null;
                }

                function setFieldError(input, name, message) {
                    const err = passwordForm.querySelector('[data-error-for="' + name + '"]');
                    const group = fieldGroup(input);
                    if (input) {
                        input.classList.toggle('is-invalid', !!message);
                    }
                    if (group) {
                        group.classList.toggle('is-invalid', !!message);
                    }
                    if (err) {
                        err.textContent = message || '';
                        err.classList.toggle('is-visible', !!message);
                        err.style.display = message ? 'block' : 'none';
                    }
                }

                function clearFieldError(input, name) {
                    setFieldError(input, name, '');
                }

                function clearAllErrors() {
                    clearFieldError(currentPassword, 'current_password');
                    clearFieldError(newPassword, 'new_password');
                    clearFieldError(confirmPassword, 'new_password_confirmation');
                }

                function focusFirstInvalid() {
                    const firstInvalid = passwordForm.querySelector('.form-control.is-invalid, input.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }

                function validateCurrent() {
                    const value = (currentPassword.value || '').trim();
                    if (!value) {
                        setFieldError(currentPassword, 'current_password', 'Current password is required.');
                        return false;
                    }
                    clearFieldError(currentPassword, 'current_password');
                    return true;
                }

                function validateNew() {
                    const value = newPassword.value || '';
                    if (!value) {
                        setFieldError(newPassword, 'new_password', 'New password is required.');
                        return false;
                    }
                    if (value.length < minLength) {
                        setFieldError(newPassword, 'new_password', 'The password must be at least ' + minLength + ' characters.');
                        return false;
                    }
                    clearFieldError(newPassword, 'new_password');
                    return true;
                }

                function validateConfirm() {
                    const value = confirmPassword.value || '';
                    if (!value) {
                        setFieldError(confirmPassword, 'new_password_confirmation', 'Please confirm your new password.');
                        return false;
                    }
                    if (value !== newPassword.value) {
                        setFieldError(confirmPassword, 'new_password_confirmation', 'Password confirmation does not match.');
                        return false;
                    }
                    clearFieldError(confirmPassword, 'new_password_confirmation');
                    return true;
                }

                [currentPassword, newPassword, confirmPassword].forEach(function (input) {
                    if (!input) return;
                    input.addEventListener('input', function () {
                        if (input === currentPassword) validateCurrent();
                        if (input === newPassword) {
                            validateNew();
                            if (confirmPassword.value) validateConfirm();
                        }
                        if (input === confirmPassword) validateConfirm();
                    });
                });

                passwordForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const ok = validateCurrent() && validateNew() && validateConfirm();
                    if (!ok) {
                        focusFirstInvalid();
                        return;
                    }

                    const tokenInput = passwordForm.querySelector('input[name="_token"]');
                    const body = new FormData(passwordForm);

                    if (submitBtn) submitBtn.disabled = true;

                    fetch(passwordForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': tokenInput ? tokenInput.value : '',
                        },
                        body: body,
                        credentials: 'same-origin',
                    })
                        .then(function (res) {
                            return res.json().then(function (data) {
                                return { ok: res.ok, status: res.status, data: data || {} };
                            }).catch(function () {
                                return { ok: res.ok, status: res.status, data: {} };
                            });
                        })
                        .then(function (result) {
                            if (result.ok) {
                                passwordForm.reset();
                                clearAllErrors();
                                if (window.toastr) {
                                    toastr.success(result.data.message || 'Password updated successfully.');
                                }
                                return;
                            }

                            clearAllErrors();
                            const errors = (result.data && result.data.errors) ? result.data.errors : {};
                            if (errors.current_password) {
                                setFieldError(currentPassword, 'current_password', errors.current_password[0]);
                            }
                            if (errors.new_password) {
                                setFieldError(newPassword, 'new_password', errors.new_password[0]);
                            }
                            if (errors.new_password_confirmation) {
                                setFieldError(confirmPassword, 'new_password_confirmation', errors.new_password_confirmation[0]);
                            }
                            if (!errors.current_password && !errors.new_password && !errors.new_password_confirmation) {
                                const message = (result.data && result.data.message)
                                    ? result.data.message
                                    : 'Unable to update password. Please try again.';
                                setFieldError(currentPassword, 'current_password', message);
                            }

                            newPassword.value = '';
                            confirmPassword.value = '';
                            focusFirstInvalid();
                        })
                        .catch(function () {
                            if (window.toastr) {
                                toastr.error('Unable to update password. Please try again.');
                            }
                        })
                        .finally(function () {
                            if (submitBtn) submitBtn.disabled = false;
                        });
                });
            }
        });
    </script>
@endsection
