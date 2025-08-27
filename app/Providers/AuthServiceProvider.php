<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for permissions
        Gate::define('create_users', function ($user) {
            return $user->hasPermissionTo('create_users');
        });

        Gate::define('read_users', function ($user) {
            return $user->hasPermissionTo('read_users');
        });

        Gate::define('update_users', function ($user) {
            return $user->hasPermissionTo('update_users');
        });

        Gate::define('delete_users', function ($user) {
            return $user->hasPermissionTo('delete_users');
        });

        Gate::define('create_admin', function ($user) {
            return $user->hasPermissionTo('create_admin');
        });

        Gate::define('read_admin', function ($user) {
            return $user->hasPermissionTo('read_admin');
        });

        Gate::define('update_admin', function ($user) {
            return $user->hasPermissionTo('update_admin');
        });

        Gate::define('delete_admin', function ($user) {
            return $user->hasPermissionTo('delete_admin');
        });

        Gate::define('create_deo', function ($user) {
            return $user->hasPermissionTo('create_deo');
        });

        Gate::define('read_deo', function ($user) {
            return $user->hasPermissionTo('read_deo');
        });

        Gate::define('update_deo', function ($user) {
            return $user->hasPermissionTo('update_deo');
        });

        Gate::define('delete_deo', function ($user) {
            return $user->hasPermissionTo('delete_deo');
        });

        Gate::define('create_locations', function ($user) {
            return $user->hasPermissionTo('create_locations');
        });

        Gate::define('read_locations', function ($user) {
            return $user->hasPermissionTo('read_locations');
        });

        Gate::define('update_locations', function ($user) {
            return $user->hasPermissionTo('update_locations');
        });

        Gate::define('delete_locations', function ($user) {
            return $user->hasPermissionTo('delete_locations');
        });

        Gate::define('update_profile', function ($user) {
            return $user->hasPermissionTo('update_profile');
        });

        Gate::define('read_profile', function ($user) {
            return $user->hasPermissionTo('read_profile');
        });

        Gate::define('access_admin_panel', function ($user) {
            return $user->hasPermissionTo('access_admin_panel');
        });

        Gate::define('manage_roles', function ($user) {
            return $user->hasPermissionTo('manage_roles');
        });

        Gate::define('manage_permissions', function ($user) {
            return $user->hasPermissionTo('manage_permissions');
        });

        Gate::define('data_entry', function ($user) {
            return $user->hasPermissionTo('data_entry');
        });

        Gate::define('view_reports', function ($user) {
            return $user->hasPermissionTo('view_reports');
        });

        Gate::define('export_data', function ($user) {
            return $user->hasPermissionTo('export_data');
        });

        // Fleet Manager gates
        Gate::define('create_fleet_manager', function ($user) {
            return $user->hasPermissionTo('create_fleet_manager');
        });

        Gate::define('read_fleet_manager', function ($user) {
            return $user->hasPermissionTo('read_fleet_manager');
        });

        Gate::define('update_fleet_manager', function ($user) {
            return $user->hasPermissionTo('update_fleet_manager');
        });

        Gate::define('delete_fleet_manager', function ($user) {
            return $user->hasPermissionTo('delete_fleet_manager');
        });

        // MVI gates
        Gate::define('create_mvi', function ($user) {
            return $user->hasPermissionTo('create_mvi');
        });

        Gate::define('read_mvi', function ($user) {
            return $user->hasPermissionTo('read_mvi');
        });

        Gate::define('update_mvi', function ($user) {
            return $user->hasPermissionTo('update_mvi');
        });

        Gate::define('delete_mvi', function ($user) {
            return $user->hasPermissionTo('delete_mvi');
        });

        // Fleet management gates
        Gate::define('manage_fleet', function ($user) {
            return $user->hasPermissionTo('manage_fleet');
        });

        Gate::define('view_fleet_reports', function ($user) {
            return $user->hasPermissionTo('view_fleet_reports');
        });

        Gate::define('track_vehicles', function ($user) {
            return $user->hasPermissionTo('track_vehicles');
        });

        Gate::define('assign_vehicles', function ($user) {
            return $user->hasPermissionTo('assign_vehicles');
        });

        // Vehicle inspection gates
        Gate::define('conduct_inspections', function ($user) {
            return $user->hasPermissionTo('conduct_inspections');
        });

        Gate::define('approve_inspections', function ($user) {
            return $user->hasPermissionTo('approve_inspections');
        });

        Gate::define('reject_inspections', function ($user) {
            return $user->hasPermissionTo('reject_inspections');
        });

        Gate::define('view_inspection_reports', function ($user) {
            return $user->hasPermissionTo('view_inspection_reports');
        });

        // Super admin gate - has all permissions
        Gate::define('super_admin', function ($user) {
            return $user->hasRole('super_admin');
        });

        // Defect Report gates
        Gate::define('create_defect_reports', function ($user) {
            return $user->hasPermissionTo('create_defect_reports');
        });

        Gate::define('read_defect_reports', function ($user) {
            return $user->hasPermissionTo('read_defect_reports');
        });

        Gate::define('update_defect_reports', function ($user) {
            return $user->hasPermissionTo('update_defect_reports');
        });

        Gate::define('delete_defect_reports', function ($user) {
            return $user->hasPermissionTo('delete_defect_reports');
        });

        // Vehicle gates
        Gate::define('create_vehicles', function ($user) {
            return $user->hasPermissionTo('create_vehicles');
        });

        Gate::define('read_vehicles', function ($user) {
            return $user->hasPermissionTo('read_vehicles');
        });

        Gate::define('update_vehicles', function ($user) {
            return $user->hasPermissionTo('update_vehicles');
        });

        Gate::define('delete_vehicles', function ($user) {
            return $user->hasPermissionTo('delete_vehicles');
        });

        // Vehicle Parts gates
        Gate::define('create_vehicle_parts', function ($user) {
            return $user->hasPermissionTo('create_vehicle_parts');
        });

        Gate::define('read_vehicle_parts', function ($user) {
            return $user->hasPermissionTo('read_vehicle_parts');
        });

        Gate::define('update_vehicle_parts', function ($user) {
            return $user->hasPermissionTo('update_vehicle_parts');
        });

        Gate::define('delete_vehicle_parts', function ($user) {
            return $user->hasPermissionTo('delete_vehicle_parts');
        });

        // Vehicle Categories gates
        Gate::define('create_vehicle_categories', function ($user) {
            return $user->hasPermissionTo('create_vehicle_categories');
        });

        Gate::define('read_vehicle_categories', function ($user) {
            return $user->hasPermissionTo('read_vehicle_categories');
        });

        Gate::define('update_vehicle_categories', function ($user) {
            return $user->hasPermissionTo('update_vehicle_categories');
        });

        Gate::define('delete_vehicle_categories', function ($user) {
            return $user->hasPermissionTo('delete_vehicle_categories');
        });
    }
}
