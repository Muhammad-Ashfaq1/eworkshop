<?php

namespace App\Constants;

class UserPermissions
{
    // User management permissions
    const CREATE_USERS = 'create_users';

    const READ_USERS = 'read_users';

    const UPDATE_USERS = 'update_users';

    const DELETE_USERS = 'delete_users';

    // Admin management permissions
    const CREATE_ADMIN = 'create_admin';

    const READ_ADMIN = 'read_admin';

    const UPDATE_ADMIN = 'update_admin';

    const DELETE_ADMIN = 'delete_admin';

    // DEO management permissions
    const CREATE_DEO = 'create_deo';

    const READ_DEO = 'read_deo';

    const UPDATE_DEO = 'update_deo';

    const DELETE_DEO = 'delete_deo';

    // Fleet Manager management permissions
    const CREATE_FLEET_MANAGER = 'create_fleet_manager';

    const READ_FLEET_MANAGER = 'read_fleet_manager';

    const UPDATE_FLEET_MANAGER = 'update_fleet_manager';

    const DELETE_FLEET_MANAGER = 'delete_fleet_manager';

    // MVI management permissions
    const CREATE_MVI = 'create_mvi';

    const READ_MVI = 'read_mvi';

    const UPDATE_MVI = 'update_mvi';

    const DELETE_MVI = 'delete_mvi';

    // Location management permissions
    const CREATE_LOCATIONS = 'create_locations';

    const READ_LOCATIONS = 'read_locations';

    const UPDATE_LOCATIONS = 'update_locations';

    const DELETE_LOCATIONS = 'delete_locations';

    // Fleet management permissions
    const MANAGE_FLEET = 'manage_fleet';

    const VIEW_FLEET_REPORTS = 'view_fleet_reports';

    const TRACK_VEHICLES = 'track_vehicles';

    const ASSIGN_VEHICLES = 'assign_vehicles';

    // Vehicle inspection permissions
    const CONDUCT_INSPECTIONS = 'conduct_inspections';

    const APPROVE_INSPECTIONS = 'approve_inspections';

    const REJECT_INSPECTIONS = 'reject_inspections';

    const VIEW_INSPECTION_REPORTS = 'view_inspection_reports';

    // Profile management permissions
    const UPDATE_PROFILE = 'update_profile';

    const READ_PROFILE = 'read_profile';

    // System management permissions
    const ACCESS_ADMIN_PANEL = 'access_admin_panel';

    const MANAGE_ROLES = 'manage_roles';

    const MANAGE_PERMISSIONS = 'manage_permissions';

    // Data entry permissions
    const DATA_ENTRY = 'data_entry';

    const VIEW_REPORTS = 'view_reports';

    const EXPORT_DATA = 'export_data';




    const CREATE_DEFECT_REPORTS = 'create_defect_reports';
    const READ_DEFECT_REPORTS = 'read_defect_reports';
    const UPDATE_DEFECT_REPORTS = 'update_defect_reports';
    const DELETE_DEFECT_REPORTS = 'delete_defect_reports';

    /**
     * Get all permissions as an array
     */
    public static function getAllPermissions()
    {
        return [
            // User management permissions
            self::CREATE_USERS,
            self::READ_USERS,
            self::UPDATE_USERS,
            self::DELETE_USERS,

            // Admin management permissions
            self::CREATE_ADMIN,
            self::READ_ADMIN,
            self::UPDATE_ADMIN,
            self::DELETE_ADMIN,

            // DEO management permissions
            self::CREATE_DEO,
            self::READ_DEO,
            self::UPDATE_DEO,
            self::DELETE_DEO,

            // Fleet Manager management permissions
            self::CREATE_FLEET_MANAGER,
            self::READ_FLEET_MANAGER,
            self::UPDATE_FLEET_MANAGER,
            self::DELETE_FLEET_MANAGER,

            // MVI management permissions
            self::CREATE_MVI,
            self::READ_MVI,
            self::UPDATE_MVI,
            self::DELETE_MVI,

            // Location management permissions
            self::CREATE_LOCATIONS,
            self::READ_LOCATIONS,
            self::UPDATE_LOCATIONS,
            self::DELETE_LOCATIONS,

            // Fleet management permissions
            self::MANAGE_FLEET,
            self::VIEW_FLEET_REPORTS,
            self::TRACK_VEHICLES,
            self::ASSIGN_VEHICLES,

            // Vehicle inspection permissions
            self::CONDUCT_INSPECTIONS,
            self::APPROVE_INSPECTIONS,
            self::REJECT_INSPECTIONS,
            self::VIEW_INSPECTION_REPORTS,

            // Profile management permissions
            self::UPDATE_PROFILE,
            self::READ_PROFILE,

            // System management permissions
            self::ACCESS_ADMIN_PANEL,
            self::MANAGE_ROLES,
            self::MANAGE_PERMISSIONS,

            // Data entry permissions
            self::DATA_ENTRY,
            self::VIEW_REPORTS,
            self::EXPORT_DATA,


            // Defect report management permissions
            self::CREATE_DEFECT_REPORTS,
            self::READ_DEFECT_REPORTS,
            self::UPDATE_DEFECT_REPORTS,
            self::DELETE_DEFECT_REPORTS
        ];
    }
}
