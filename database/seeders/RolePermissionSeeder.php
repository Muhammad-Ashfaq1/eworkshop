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

        // Admin - can manage users, locations, and most system functions but CANNOT delete master data
        $admin = Role::firstOrCreate(['name' => UserRoles::ADMIN]);
        $admin->syncPermissions([
            // User management
            UserPermissions::CREATE_USERS,
            UserPermissions::READ_USERS,
            UserPermissions::UPDATE_USERS,
            UserPermissions::RESET_USER_PASSWORD,
           // UserPermissions::DELETE_USERS,
            UserPermissions::CREATE_DEO,
            UserPermissions::READ_DEO,
            UserPermissions::UPDATE_DEO,
            UserPermissions::DELETE_DEO,

            // Location management - can create, read, update but NOT delete
            UserPermissions::CREATE_LOCATIONS,
            UserPermissions::READ_LOCATIONS,
            UserPermissions::UPDATE_LOCATIONS,
            // UserPermissions::DELETE_LOCATIONS, // Admin cannot delete locations

            // Vehicle management - can create, read, update but NOT delete
            UserPermissions::CREATE_VEHICLES,
            UserPermissions::READ_VEHICLES,
            UserPermissions::UPDATE_VEHICLES,
            // UserPermissions::DELETE_VEHICLES, // Admin cannot delete vehicles

            // Vehicle Parts management - can create, read, update but NOT delete
            UserPermissions::CREATE_VEHICLE_PARTS,
            UserPermissions::READ_VEHICLE_PARTS,
            UserPermissions::UPDATE_VEHICLE_PARTS,
            // UserPermissions::DELETE_VEHICLE_PARTS, // Admin cannot delete vehicle parts

            // Vehicle Categories management - can create, read, update but NOT delete
            UserPermissions::CREATE_VEHICLE_CATEGORIES,
            UserPermissions::READ_VEHICLE_CATEGORIES,
            UserPermissions::UPDATE_VEHICLE_CATEGORIES,
            // UserPermissions::DELETE_VEHICLE_CATEGORIES, // Admin cannot delete vehicle categories

            // Fleet Manager management - can create, read, update but NOT delete
            UserPermissions::CREATE_FLEET_MANAGER,
            UserPermissions::READ_FLEET_MANAGER,
            UserPermissions::UPDATE_FLEET_MANAGER,
            // UserPermissions::DELETE_FLEET_MANAGER, // Admin cannot delete fleet managers

            // MVI management - can create, read, update but NOT delete
            UserPermissions::CREATE_MVI,
            UserPermissions::READ_MVI,
            UserPermissions::UPDATE_MVI,
            // UserPermissions::DELETE_MVI, // Admin cannot delete MVI

            // Defect Reports - can read, update but NOT delete
            UserPermissions::CREATE_DEFECT_REPORTS,
            UserPermissions::READ_DEFECT_REPORTS,
            UserPermissions::UPDATE_DEFECT_REPORTS,
            // UserPermissions::DELETE_DEFECT_REPORTS, // Admin cannot delete defect reports

            // Reports and exports
            UserPermissions::VIEW_REPORTS,
            UserPermissions::EXPORT_DATA,
            UserPermissions::ACCESS_ADMIN_PANEL,

            // Profile management
            UserPermissions::UPDATE_PROFILE,
            UserPermissions::READ_PROFILE,

            // Purchase Orders - can create, read, update but NOT delete
            UserPermissions::CREATE_PURCHASE_ORDERS,
            UserPermissions::READ_PURCHASE_ORDERS,
            UserPermissions::UPDATE_PURCHASE_ORDERS,
            // UserPermissions::DELETE_PURCHASE_ORDERS, // Admin cannot delete purchase orders
        ]);

        // DEO (Data Entry Operator) - limited to data entry, viewing master data, and managing own defect reports
        $deo = Role::firstOrCreate(['name' => UserRoles::DEO]);
        $deo->syncPermissions([
            // Data entry
            UserPermissions::DATA_ENTRY,

            // Can only view master data (read-only access)
            UserPermissions::READ_LOCATIONS,
            UserPermissions::READ_VEHICLES,
            UserPermissions::READ_VEHICLE_PARTS,
            UserPermissions::READ_VEHICLE_CATEGORIES,
            UserPermissions::READ_FLEET_MANAGER,
            UserPermissions::READ_MVI,

            // Defect Reports - can create and read their own reports
            UserPermissions::CREATE_DEFECT_REPORTS,
            UserPermissions::READ_DEFECT_REPORTS,

            // Can view reports but not export
            UserPermissions::VIEW_REPORTS,

            // Profile management
            UserPermissions::UPDATE_PROFILE,
            UserPermissions::READ_PROFILE,

            //purchase order permission
            UserPermissions::CREATE_PURCHASE_ORDERS,
            UserPermissions::READ_PURCHASE_ORDERS,
        ]);
    }
}
