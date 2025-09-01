@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">User Management</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">

                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">Users List</h5>
                                 @can('create_users')
                                <button type="button" class="btn btn-primary" id="addUserBtn">
                                    <i class="ri-user-add-line align-bottom me-1"></i> Add User
                                </button>
                                @endcan
                            </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="table-wrapper">
                                <table id="usersTable"
                                    class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-ordering="false">SR No.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="usersTableBody">
                                        @forelse($users as $index => $user)
                                            <tr id="user-row-{{ $user->id }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($user->roles->isNotEmpty())
                                                        <span
                                                            class="badge bg-primary">{{ ucfirst($user->roles->first()->name) }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">No Role</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status" type="checkbox"
                                                            id="status-{{ $user->id }}"
                                                            data-user-id="{{ $user->id }}"
                                                            {{ $user->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="status-{{ $user->id }}">
                                                            <span
                                                                class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}"
                                                                id="status-badge-{{ $user->id }}">
                                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>{{ formatCreatedAt($user->created_at) }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('update_users')
                                                            <button type="button"
                                                                class="btn btn-sm btn-soft-primary edit-user-btn"
                                                                data-user-id="{{ $user->id }}" title="Edit User">
                                                                <i class="ri-edit-2-line"></i>
                                                            </button>
                                                        @endcan

                                                        @can('reset_user_password')
                                                            <button type="button"
                                                                class="btn btn-sm btn-soft-info reset-password-btn"
                                                                data-user-id="{{ $user->id }}" title="Reset Password">
                                                                <i class="ri-lock-password-line"></i>
                                                            </button>
                                                        @endcan
                                                        @can('delete_users')
                                                            <button type="button"
                                                                class="btn btn-sm btn-soft-danger delete-user-btn"
                                                                data-user-id="{{ $user->id }}" title="Delete User">
                                                                <i class="ri-delete-bin-2-line"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No users found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">
                            <i class="ri-user-add-line me-2"></i>Add New User
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="userForm" novalidate>
                        @csrf
                        <input type="hidden" id="user_id" name="user_id">
                        <input type="hidden" id="form_method" name="_method" value="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">
                                            <i class="ri-user-line me-1"></i>First Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">
                                            <i class="ri-user-line me-1"></i>Last Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="ri-mail-line me-1"></i>Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">
                                    <i class="ri-phone-line me-1"></i>Phone Number
                                </label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_active" class="form-label">Status</label>
                                        <select class="form-control" id="is_active" name="is_active">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="passwordSection">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password" name="password">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>Close
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveUserBtn">
                                <i class="ri-save-line me-1"></i>Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reset Password Modal -->
        <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetPasswordModalLabel">
                            <i class="ri-lock-password-line me-2"></i>Reset Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="resetPasswordForm">
                        @csrf
                        <input type="hidden" id="reset_user_id" name="user_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="new_password" name="password" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="new_password_confirmation"
                                    name="password_confirmation" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>Close
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="ri-lock-password-line me-1"></i>Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            console.log('User Management Script Loading...');

            // Global variables
            let isEditMode = false;
            let currentUserId = null;

            $(document).ready(function() {
                console.log('jQuery is ready - Initializing User Management...');

                // Initialize all event handlers
                initializeEventHandlers();

                console.log('User Management initialized successfully');
            });

            function initializeEventHandlers() {
                // Add User Button
                $('#addUserBtn').on('click', function() {
                    openCreateModal();
                });

                // Edit User Buttons (using event delegation for dynamically added content)
                $(document).on('click', '.edit-user-btn', function() {
                    const userId = $(this).data('user-id');
                    editUser(userId);
                });

                // Delete User Buttons
                $(document).on('click', '.delete-user-btn', function() {
                    const userId = $(this).data('user-id');
                    deleteUser(userId);
                });

                // Reset Password Buttons
                $(document).on('click', '.reset-password-btn', function() {
                    const userId = $(this).data('user-id');
                    resetPassword(userId);
                });

                // Toggle Status Checkboxes
                $(document).on('change', '.toggle-status', function() {
                    const userId = $(this).data('user-id');
                    const isActive = $(this).is(':checked');
                    toggleUserStatus(userId, isActive);
                });

                // User Form Submission
                $('#userForm').on('submit', function(e) {
                    e.preventDefault();
                    submitUserForm();
                });

                // Reset Password Form Submission
                $('#resetPasswordForm').on('submit', function(e) {
                    e.preventDefault();
                    submitResetPasswordForm();
                });

                // Clear validation errors when modal closes
                $('#userModal').on('hidden.bs.modal', function() {
                    clearValidationErrors();
                });

                $('#resetPasswordModal').on('hidden.bs.modal', function() {
                    clearValidationErrors('resetPasswordForm');
                });
            }

            // Open Create Modal
            function openCreateModal() {
                console.log('Opening create modal...');
                isEditMode = false;
                currentUserId = null;

                // Reset modal
                $('#userModalLabel').html('<i class="ri-user-add-line me-2"></i>Add New User');
                $('#saveUserBtn').html('<i class="ri-save-line me-1"></i>Save User');
                $('#userForm')[0].reset();
                $('#user_id').val('');
                $('#form_method').val('POST');

                // Show password section
                $('#passwordSection').show();
                $('#password').prop('required', true);
                $('#password_confirmation').prop('required', true);

                // Clear validation errors
                clearValidationErrors();

                // Show modal
                $('#userModal').modal('show');
            }

            // Edit User
            function editUser(userId) {
                console.log('Editing user:', userId);
                isEditMode = true;
                currentUserId = userId;

                // Show loading
                showLoading();

                $.ajax({
                    url: `/admin/users/show/${userId}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        hideLoading();

                        if (response.success) {
                            const user = response.user;

                            // Update modal for editing
                            $('#userModalLabel').html('<i class="ri-edit-2-line me-2"></i>Edit User');
                            $('#saveUserBtn').html('<i class="ri-refresh-line me-1"></i>Update User');
                            $('#form_method').val('PUT');
                            $('#user_id').val(user.id);

                            // Populate form fields
                            $('#first_name').val(user.first_name);
                            $('#last_name').val(user.last_name);
                            $('#email').val(user.email);
                            $('#phone_number').val(user.phone_number || '');
                            $('#role').val(user.roles[0]?.name || '');
                            $('#is_active').val(user.is_active ? '1' : '0');

                            // Hide password section for editing
                            $('#passwordSection').hide();
                            $('#password').prop('required', false);
                            $('#password_confirmation').prop('required', false);

                            // Clear validation errors
                            clearValidationErrors();

                            // Show modal
                            $('#userModal').modal('show');
                        } else {
                            showErrorMessage('Error fetching user data');
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        console.error('Error fetching user:', xhr.responseText);
                        showErrorMessage('Error fetching user data');
                    }
                });
            }

            // Submit User Form
            function submitUserForm() {
                console.log('Submitting user form...');

                const formData = new FormData($('#userForm')[0]);
                let url = '/admin/users/store';
                let method = 'POST';

                if (isEditMode && currentUserId) {
                    url = `/admin/users/update/${currentUserId}`;
                    formData.append('_method', 'PUT');
                }

                // Disable submit button
                const $submitBtn = $('#saveUserBtn');
                const originalText = $submitBtn.html();
                $submitBtn.prop('disabled', true).html('<i class="ri-loader-2-line me-1"></i>Saving...');

                // Clear previous validation errors
                clearValidationErrors();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log('Form submission response:', response);

                        // Re-enable submit button
                        $submitBtn.prop('disabled', false).html(originalText);

                        if (response.success) {
                            $('#userModal').modal('hide');
                            showSuccessMessage(response.message);

                            // Reload page after short delay
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showErrorMessage(response.message || 'Error saving user');
                        }
                    },
                    error: function(xhr) {
                        console.error('Form submission error:', xhr.responseText);

                        // Re-enable submit button
                        $submitBtn.prop('disabled', false).html(originalText);

                        if (xhr.status === 422) {
                            // Validation errors
                            const response = JSON.parse(xhr.responseText);
                            if (response.errors) {
                                displayValidationErrors(response.errors);
                            } else {
                                showErrorMessage('Validation failed');
                            }
                        } else {
                            showErrorMessage('Error saving user');
                        }
                    }
                });
            }

            // Delete User
            function deleteUser(userId) {
                console.log('Deleting user:', userId);

                if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    showLoading();

                    $.ajax({
                        url: `/admin/users/destroy/${userId}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            hideLoading();

                            if (response.success) {
                                // Remove row from table
                                $(`#user-row-${userId}`).fadeOut(300, function() {
                                    $(this).remove();
                                });
                                showSuccessMessage(response.message);
                            } else {
                                showErrorMessage(response.message || 'Error deleting user');
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            console.error('Delete error:', xhr.responseText);
                            showErrorMessage('Error deleting user');
                        }
                    });
                }
            }

            // Toggle User Status
            function toggleUserStatus(userId, isActive) {
                console.log('Toggling status for user:', userId, 'to:', isActive);

                $.ajax({
                    url: `/admin/users/toggle-status/${userId}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update badge
                            const $badge = $(`#status-badge-${userId}`);
                            if (response.is_active) {
                                $badge.removeClass('bg-danger').addClass('bg-success').text('Active');
                            } else {
                                $badge.removeClass('bg-success').addClass('bg-danger').text('Inactive');
                            }
                            showSuccessMessage(response.message);
                        } else {
                            // Revert checkbox
                            $(`#status-${userId}`).prop('checked', !isActive);
                            showErrorMessage('Error updating status');
                        }
                    },
                    error: function(xhr) {
                        console.error('Toggle status error:', xhr.responseText);
                        // Revert checkbox
                        $(`#status-${userId}`).prop('checked', !isActive);
                        showErrorMessage('Error updating status');
                    }
                });
            }

            // Reset Password
            function resetPassword(userId) {
                console.log('Opening reset password modal for user:', userId);
                $('#reset_user_id').val(userId);
                $('#resetPasswordForm')[0].reset();
                clearValidationErrors('resetPasswordForm');
                $('#resetPasswordModal').modal('show');
            }

            // Submit Reset Password Form
            function submitResetPasswordForm() {
                console.log('Submitting reset password form...');

                const userId = $('#reset_user_id').val();
                const formData = new FormData($('#resetPasswordForm')[0]);

                // Disable submit button
                const $submitBtn = $('#resetPasswordForm button[type="submit"]');
                const originalText = $submitBtn.html();
                $submitBtn.prop('disabled', true).html('<i class="ri-loader-2-line me-1"></i>Resetting...');

                // Clear previous validation errors
                clearValidationErrors('resetPasswordForm');

                $.ajax({
                    url: `/admin/users/reset-password/${userId}`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        // Re-enable submit button
                        $submitBtn.prop('disabled', false).html(originalText);

                        if (response.success) {
                            $('#resetPasswordModal').modal('hide');
                            showSuccessMessage(response.message);
                        } else {
                            showErrorMessage(response.message || 'Error resetting password');
                        }
                    },
                    error: function(xhr) {
                        console.error('Reset password error:', xhr.responseText);

                        // Re-enable submit button
                        $submitBtn.prop('disabled', false).html(originalText);

                        if (xhr.status === 422) {
                            // Validation errors
                            const response = JSON.parse(xhr.responseText);
                            if (response.errors) {
                                displayValidationErrors(response.errors, 'resetPasswordForm');
                            } else {
                                showErrorMessage('Validation failed');
                            }
                        } else {
                            showErrorMessage('Error resetting password');
                        }
                    }
                });
            }

            // Helper Functions
            function showSuccessMessage(message) {
                toastr.success(message);
            }

            function showErrorMessage(message) {
                toastr.error(message);
            }

            function showLoading() {
                // You can customize this based on your existing loading implementation
                console.log('Loading...');
            }

            function hideLoading() {
                console.log('Loading complete');
            }

            function displayValidationErrors(errors, formId = 'userForm') {
                console.log('Displaying validation errors:', errors);

                Object.keys(errors).forEach(field => {
                    const $input = $(`#${formId} [name="${field}"]`);
                    if ($input.length) {
                        $input.addClass('is-invalid');
                        const $feedback = $input.next('.invalid-feedback');
                        if ($feedback.length) {
                            $feedback.text(errors[field][0]);
                        }
                    }
                });
            }

            function clearValidationErrors(formId = 'userForm') {
                $(`#${formId} .is-invalid`).removeClass('is-invalid');
                $(`#${formId} .invalid-feedback`).text('');
            }

            console.log('User Management Script Loaded');
        </script>
    @endsection
