# Spatie Laravel Permission Integration Guide

## Overview
This Laravel application now includes a complete role and permission system using the Spatie Laravel Permission package. The system supports three main roles: **Super Admin**, **Admin**, and **DEO (Data Entry Operator)**.

## Default Users Created
After running the seeders, the following default users are available:

| Role | Email | Password | Permissions |
|------|-------|----------|-------------|
| Super Admin | superadmin@example.com | password123 | All permissions |
| Admin | admin@example.com | password123 | User management, locations, some system functions |
| DEO | deo@example.com | password123 | Data entry, limited location access |

## Roles and Permissions

### Super Admin
- **Purpose**: System administrator with full access
- **Capabilities**: 
  - Create and manage Admin and DEO users
  - Full access to all system features
  - Manage roles and permissions
  - Access admin panel

### Admin
- **Purpose**: Department/section administrator
- **Capabilities**:
  - Manage regular users and DEOs
  - Manage locations
  - View reports and export data
  - Access admin panel
  - Cannot create other admins (only Super Admin can)

### DEO (Data Entry Operator)
- **Purpose**: Data entry personnel with limited access
- **Capabilities**:
  - Perform data entry tasks
  - Basic location management (create, read, update)
  - View reports
  - Update own profile
  - Cannot access user management

## Permission List

### User Management Permissions
- `create_users` - Create new users
- `read_users` - View user lists
- `update_users` - Edit user information
- `delete_users` - Delete users
- `create_admin` - Create admin users (Super Admin only)
- `read_admin` - View admin users
- `update_admin` - Edit admin users
- `delete_admin` - Delete admin users
- `create_deo` - Create DEO users
- `read_deo` - View DEO users
- `update_deo` - Edit DEO users
- `delete_deo` - Delete DEO users

### Location Management Permissions
- `create_locations` - Create new locations
- `read_locations` - View locations
- `update_locations` - Edit locations
- `delete_locations` - Delete locations

### Profile Management Permissions
- `update_profile` - Update own profile
- `read_profile` - View own profile

### System Permissions
- `access_admin_panel` - Access administrative interface
- `manage_roles` - Manage user roles
- `manage_permissions` - Manage permissions
- `data_entry` - Perform data entry operations
- `view_reports` - View system reports
- `export_data` - Export data from system

## Usage Examples

### In Controllers
```php
// Check permission before allowing action
$this->authorize('create_users');

// Check role
if ($user->hasRole('super_admin')) {
    // Super admin specific logic
}

// Check permission
if ($user->can('create_locations')) {
    // User can create locations
}
```

### In Blade Templates
```php
@can('create_admin')
    <button>Create Admin User</button>
@endcan

@role('super_admin')
    <div>Super Admin Only Content</div>
@endrole
```

### In Routes (Middleware)
```php
// Only super admin can access
Route::middleware(['role:super_admin'])->group(function () {
    // Routes here
});

// Multiple roles
Route::middleware(['role:super_admin|admin'])->group(function () {
    // Routes here
});

// Permission based
Route::middleware(['permission:create_locations'])->group(function () {
    // Routes here
});
```

## API Endpoints

### User Management (Super Admin Only)
- `GET /admin/users` - List all users
- `POST /admin/users/store` - Create new user
- `GET /admin/users/show/{user}` - View user details
- `PUT /admin/users/update/{user}` - Update user
- `DELETE /admin/users/destroy/{user}` - Delete user
- `POST /admin/users/reset-password/{user}` - Reset user password
- `POST /admin/users/toggle-status/{user}` - Toggle user active status

### Location Management
- `GET /admin/location` - List locations (requires `read_locations`)
- `POST /admin/location/store` - Create location (requires `create_locations`)
- `GET /admin/location/edit/{id}` - Edit location (requires `update_locations`)
- `DELETE /admin/location/destroy/{id}` - Delete location (requires `delete_locations`)

## How Super Admin Creates Users

1. **Login** as Super Admin using `superadmin@example.com` / `password123`
2. **Navigate** to `/admin/users` (User Management)
3. **Click** "Add User" button
4. **Fill** the form with user details:
   - First Name, Last Name
   - Email (must be unique)
   - Phone Number (optional)
   - Role (Admin or DEO)
   - Status (Active/Inactive)
   - Password and confirmation
5. **Submit** the form

The system will:
- Create the user with the specified role
- Assign appropriate permissions based on role
- Set the user as active/inactive as specified
- Send appropriate notifications (if configured)

## Testing the Implementation

You can test the roles and permissions using:

```bash
# Check user roles
php artisan tinker --execute="dd(App\Models\User::with('roles')->get());"

# Check super admin permissions
php artisan tinker --execute="echo App\Models\User::find(1)->getAllPermissions()->pluck('name')->implode(', ');"

# Check DEO permissions
php artisan tinker --execute="echo App\Models\User::find(3)->getAllPermissions()->pluck('name')->implode(', ');"
```

## Security Features

1. **Permission Checks**: All sensitive operations require appropriate permissions
2. **Role-based Access**: Routes are protected by role middleware
3. **Authorization Gates**: Custom gates defined for all permissions
4. **Middleware Protection**: Critical routes protected by authentication and role middleware
5. **Super Admin Protection**: Super Admin users cannot be deleted through the system

## Extending the System

To add new permissions:

1. Add the permission name to `RolePermissionSeeder.php`
2. Assign the permission to appropriate roles
3. Add authorization gates in `AuthServiceProvider.php`
4. Use `$this->authorize('permission_name')` in controllers
5. Use `@can('permission_name')` in Blade templates

## Notes

- All users created through the system are automatically verified (email_verified_at is set)
- Default password for seeded users is `password123`
- The system uses Laravel's built-in authorization features combined with Spatie's permission package
- Roles and permissions are cached for performance (automatically handled by Spatie package)
