<?php

namespace App\Http\Controllers;

use App\Constants\UserRoles;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get the dashboard route for the authenticated user based on their role
     */
    public static function getDashboardRoute()
    {
        $user = Auth::user();

        if (! $user) {
            return route('login');
        }

        $dashboardRoutes = UserRoles::getDashboardRoutes();

        foreach ($dashboardRoutes as $role => $routeName) {
            if ($user->hasRole($role)) {
                return route($routeName);
            }
        }

        return route('home');
    }

    /**
     * Get the dashboard route name for the authenticated user based on their role
     */
    public static function getDashboardRouteName()
    {
        $user = Auth::user();

        if (! $user) {
            return 'login';
        }

        $dashboardRoutes = UserRoles::getDashboardRoutes();

        foreach ($dashboardRoutes as $role => $routeName) {
            if ($user->hasRole($role)) {
                return $routeName;
            }
        }

        return 'home';
    }

    /**
     * Super Admin Dashboard
     */
    public function superAdmin()
    {
        $this->authorize(UserRoles::SUPER_ADMIN);

        $user = Auth::user();
        $data = [
            'title' => 'Super Admin Dashboard',
            'user' => $user,
            'stats' => [
                'total_users' => \App\Models\User::count(),
                'active_users' => \App\Models\User::where('is_active', true)->count(),
                'total_roles' => \Spatie\Permission\Models\Role::count(),
                'total_permissions' => \Spatie\Permission\Models\Permission::count(),
            ],
        ];

        return view('dashboards.super_admin', $data);
    }

    /**
     * Admin Dashboard
     */
    public function admin()
    {
        $user = Auth::user();
        if (! $user->hasRole(UserRoles::ADMIN)) {
            abort(403, 'Unauthorized access');
        }

        $data = [
            'title' => 'Admin Dashboard',
            'user' => $user,
            'stats' => [
                'total_locations' => 0, // Add actual count when location model is available
                'active_deos' => \App\Models\User::role(UserRoles::DEO)->where('is_active', true)->count(),
                'pending_reports' => 0, // Add actual count when reports are available
            ],
        ];

        return view('dashboards.admin', $data);
    }

    /**
     * DEO Dashboard
     */
    public function deo()
    {
        $user = Auth::user();
        if (! $user->hasRole(UserRoles::DEO)) {
            abort(403, 'Unauthorized access');
        }

        // Get defect report statistics for the current DEO
        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();
        $monthStart = now()->startOfMonth();

        $defectStats = [
            'today' => \App\Models\DefectReport::where('created_by', $user->id)
                ->where('created_at', '>=', $today)
                ->count(),
            'this_week' => \App\Models\DefectReport::where('created_by', $user->id)
                ->where('created_at', '>=', $weekStart)
                ->count(),
            'this_month' => \App\Models\DefectReport::where('created_by', $user->id)
                ->where('created_at', '>=', $monthStart)
                ->count(),
            'total' => \App\Models\DefectReport::where('created_by', $user->id)->count(),
        ];

        // Get recent defect reports
        $recentReports = \App\Models\DefectReport::where('created_by', $user->id)
            ->with(['vehicle', 'location', 'fleetManager', 'mvi'])
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'title' => 'Data Entry Operator Dashboard',
            'user' => $user,
            'defectStats' => $defectStats,
            'recentReports' => $recentReports,
            'stats' => [
                'defect_reports_today' => $defectStats['today'],
                'defect_reports_week' => $defectStats['this_week'],
                'defect_reports_month' => $defectStats['this_month'],
                'total_defect_reports' => $defectStats['total'],
            ],
        ];

        return view('dashboards.deo', $data);
    }

    /**
     * Fleet Manager Dashboard
     */
    public function fleetManager()
    {
        $user = Auth::user();
        if (! $user->hasRole(UserRoles::FLEET_MANAGER)) {
            abort(403, 'Unauthorized access');
        }

        $data = [
            'title' => 'Fleet Manager Dashboard',
            'user' => $user,
            'stats' => [
                'total_vehicles' => 0, // Add actual count when vehicle model is available
                'active_vehicles' => 0, // Add actual count
                'maintenance_due' => 0, // Add actual count
                'fuel_consumption' => 0, // Add actual metric
            ],
        ];

        return view('dashboards.fleet_manager', $data);
    }

    /**
     * MVI Dashboard
     */
    public function mvi()
    {
        $user = Auth::user();
        if (! $user->hasRole(UserRoles::MVI)) {
            abort(403, 'Unauthorized access');
        }

        $data = [
            'title' => 'Motor Vehicle Inspector Dashboard',
            'user' => $user,
            'stats' => [
                'pending_inspections' => 0, // Add actual count when inspection model is available
                'completed_today' => 0, // Add actual count
                'rejected_inspections' => 0, // Add actual count
                'approved_inspections' => 0, // Add actual count
            ],
        ];

        return view('dashboards.mvi', $data);
    }
}
