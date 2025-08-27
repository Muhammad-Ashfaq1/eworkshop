<?php

namespace Database\Seeders;

use App\Constants\UserPermissions;
use App\Constants\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = UserPermissions::getAllPermissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => UserRoles::SUPER_ADMIN]);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - can manage users, locations, and some system functions
        $admin = Role::firstOrCreate(['name' => UserRoles::ADMIN]);
        $admin->syncPermissions([
            UserPermissions::CREATE_USERS,
            UserPermissions::READ_USERS,
            UserPermissions::UPDATE_USERS,
            UserPermissions::DELETE_USERS,
            UserPermissions::CREATE_DEO,
            UserPermissions::READ_DEO,
            UserPermissions::UPDATE_DEO,
            UserPermissions::DELETE_DEO,
            UserPermissions::CREATE_LOCATIONS,
            UserPermissions::READ_LOCATIONS,
            UserPermissions::UPDATE_LOCATIONS,
            UserPermissions::DELETE_LOCATIONS,
            UserPermissions::UPDATE_PROFILE,
            UserPermissions::READ_PROFILE,
            UserPermissions::ACCESS_ADMIN_PANEL,
            UserPermissions::VIEW_REPORTS,
            UserPermissions::EXPORT_DATA,
            UserPermissions::READ_FLEET_MANAGER,
            UserPermissions::CREATE_FLEET_MANAGER,
            UserPermissions::UPDATE_FLEET_MANAGER,
            UserPermissions::CREATE_DEFECT_REPORTS,
            UserPermissions::READ_DEFECT_REPORTS,
            UserPermissions::UPDATE_DEFECT_REPORTS,
        ]);

        // DEO (Data Entry Operator) - limited to data entry and viewing
        $deo = Role::firstOrCreate(['name' => UserRoles::DEO]);
        $deo->syncPermissions([
            UserPermissions::DATA_ENTRY,
            UserPermissions::READ_LOCATIONS,
            UserPermissions::CREATE_LOCATIONS,
            UserPermissions::UPDATE_LOCATIONS,
            UserPermissions::UPDATE_PROFILE,
            UserPermissions::READ_PROFILE,
            UserPermissions::VIEW_REPORTS,
        ]);
    }
}
