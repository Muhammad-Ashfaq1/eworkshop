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

        // Fleet Manager - manages fleet operations and vehicles
        $fleetManager = Role::firstOrCreate(['name' => UserRoles::FLEET_MANAGER]);
        $fleetManager->syncPermissions([
            UserPermissions::MANAGE_FLEET,
            UserPermissions::VIEW_FLEET_REPORTS,
            UserPermissions::TRACK_VEHICLES,
            UserPermissions::ASSIGN_VEHICLES,
            UserPermissions::READ_LOCATIONS,
            UserPermissions::UPDATE_LOCATIONS,
            UserPermissions::UPDATE_PROFILE,
            UserPermissions::READ_PROFILE,
            UserPermissions::VIEW_REPORTS,
            UserPermissions::EXPORT_DATA,
        ]);

        // MVI (Motor Vehicle Inspector) - vehicle inspections and approvals
        $mvi = Role::firstOrCreate(['name' => UserRoles::MVI]);
        $mvi->syncPermissions([
            UserPermissions::CONDUCT_INSPECTIONS,
            UserPermissions::APPROVE_INSPECTIONS,
            UserPermissions::REJECT_INSPECTIONS,
            UserPermissions::VIEW_INSPECTION_REPORTS,
            UserPermissions::READ_LOCATIONS,
            UserPermissions::UPDATE_PROFILE,
            UserPermissions::READ_PROFILE,
            UserPermissions::VIEW_REPORTS,
        ]);
    }
}
