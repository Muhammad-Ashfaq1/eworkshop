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

        // Super admin gate - has all permissions
        Gate::define('super_admin', function ($user) {
            return $user->hasRole('super_admin');
        });
    }
}
