<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management permissions
            'create_users',
            'read_users',
            'update_users',
            'delete_users',

            // Admin management permissions
            'create_admin',
            'read_admin',
            'update_admin',
            'delete_admin',

            // DEO management permissions
            'create_deo',
            'read_deo',
            'update_deo',
            'delete_deo',

            // Location management permissions
            'create_locations',
            'read_locations',
            'update_locations',
            'delete_locations',

            // Profile management permissions
            'update_profile',
            'read_profile',

            // System management permissions
            'access_admin_panel',
            'manage_roles',
            'manage_permissions',

            // Data entry permissions
            'data_entry',
            'view_reports',
            'export_data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - can manage users, locations, and some system functions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'create_users',
            'read_users',
            'update_users',
            'delete_users',
            'create_deo',
            'read_deo',
            'update_deo',
            'delete_deo',
            'create_locations',
            'read_locations',
            'update_locations',
            'delete_locations',
            'update_profile',
            'read_profile',
            'access_admin_panel',
            'view_reports',
            'export_data',
        ]);

        // DEO (Data Entry Operator) - limited to data entry and viewing
        $deo = Role::firstOrCreate(['name' => 'deo']);
        $deo->syncPermissions([
            'data_entry',
            'read_locations',
            'create_locations',
            'update_locations',
            'update_profile',
            'read_profile',
            'view_reports',
        ]);
    }
}
