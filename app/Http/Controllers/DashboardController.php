<?php

namespace App\Http\Controllers;

use App\Constants\UserRoles;
use App\Models\DefectReport;
use App\Models\Location;
use App\Models\PurchaseOrder;
use App\Models\ReportAudit;
use App\Models\User;
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

        return route('login');
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

        return 'login';
    }

    /**
     * Helper to compute monthly performance chart data for FY 2026-27 (Jul 2026 to Jun 2027) using 100% real database records
     */
    private function getYearWisePerformanceData($user): array
    {
        $months = [
            ['label' => 'Jul 2026', 'start' => '2026-07-01', 'end' => '2026-07-31'],
            ['label' => 'Aug 2026', 'start' => '2026-08-01', 'end' => '2026-08-31'],
            ['label' => 'Sep 2026', 'start' => '2026-09-01', 'end' => '2026-09-30'],
            ['label' => 'Oct 2026', 'start' => '2026-10-01', 'end' => '2026-10-31'],
            ['label' => 'Nov 2026', 'start' => '2026-11-01', 'end' => '2026-11-30'],
            ['label' => 'Dec 2026', 'start' => '2026-12-01', 'end' => '2026-12-31'],
            ['label' => 'Jan 2027', 'start' => '2027-01-01', 'end' => '2027-01-31'],
            ['label' => 'Feb 2027', 'start' => '2027-02-01', 'end' => '2027-02-28'],
            ['label' => 'Mar 2027', 'start' => '2027-03-01', 'end' => '2027-03-31'],
            ['label' => 'Apr 2027', 'start' => '2027-04-01', 'end' => '2027-04-30'],
            ['label' => 'May 2027', 'start' => '2027-05-01', 'end' => '2027-05-31'],
            ['label' => 'Jun 2027', 'start' => '2027-06-01', 'end' => '2027-06-30'],
        ];

        $categories = array_column($months, 'label');
        $reportsData = [];
        $ordersData = [];
        $fineData = [];

        foreach ($months as $m) {
            $reportsCount = DefectReport::forUser($user)
                ->whereBetween('created_at', [$m['start'] . ' 00:00:00', $m['end'] . ' 23:59:59'])
                ->count();

            $ordersCount = PurchaseOrder::forUser($user)
                ->whereBetween('issue_date', [$m['start'], $m['end']])
                ->count();

            $fineCount = \App\Models\Work::whereHas('defectReport', function($q) use ($user) {
                    $q->forUser($user);
                })
                ->whereBetween('created_at', [$m['start'] . ' 00:00:00', $m['end'] . ' 23:59:59'])
                ->count();

            $reportsData[] = $reportsCount;
            $ordersData[] = $ordersCount;
            $fineData[] = $fineCount;
        }

        $totalReports = array_sum($reportsData);
        $totalOrders  = array_sum($ordersData);
        $totalFine    = array_sum($fineData);
        $grandTotal   = $totalReports + $totalOrders + $totalFine;

        return [
            'categories' => $categories,
            'series' => [
                ['name' => 'Reports', 'data' => $reportsData],
                ['name' => 'Orders', 'data' => $ordersData],
                ['name' => 'Fine', 'data' => $fineData],
            ],
            'totals' => [
                'reports' => $totalReports,
                'orders' => $totalOrders,
                'fine' => $totalFine,
                'grand_total' => $grandTotal,
            ],
        ];
    }

    /**
     * Super Admin Dashboard
     */
    public function superAdmin()
    {
        $this->authorize(UserRoles::SUPER_ADMIN);

        $user = Auth::user();
        $stats = $user->getDashboardStats();
        
        // Additional super admin specific stats
        $adminStats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_deos' => User::role(UserRoles::DEO)->count(),
            'total_admins' => User::role(UserRoles::ADMIN)->count(),
            'total_roles' => \Spatie\Permission\Models\Role::count(),
            'total_permissions' => \Spatie\Permission\Models\Permission::count(),
            'total_defect_reports' => DefectReport::count(),
            'total_purchase_orders' => PurchaseOrder::count(),
            'recent_audits' => ReportAudit::with(['modifier', 'originalCreator'])
                ->latest()
                ->take(10)
                ->get(),
        ];
        
        $data = [
            'title' => 'Super Admin Dashboard',
            'user' => $user,
            'stats' => array_merge($stats, $adminStats),
            'recentReports' => $user->getRecentReports(),
            'chartData' => $this->getYearWisePerformanceData($user),
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

        $stats = $user->getDashboardStats();
        
        // Additional admin specific stats
        $adminStats = [
            'total_deos' => User::role(UserRoles::DEO)->count(),
            'active_deos' => User::role(UserRoles::DEO)->where('is_active', true)->count(),
            'total_locations' => Location::count(),
            'total_defect_reports' => DefectReport::count(),
            'total_purchase_orders' => PurchaseOrder::count(),
            'pending_reports' => 0, // Can be updated based on actual business logic for "pending"
            'reports_edited_today' => ReportAudit::where('modifier_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'recent_edited_reports' => ReportAudit::where('modifier_id', $user->id)
                ->with(['originalCreator'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        $data = [
            'title' => 'Admin Dashboard',
            'user' => $user,
            'stats' => array_merge($stats, $adminStats),
            'recentReports' => $user->getRecentReports(),
            'chartData' => $this->getYearWisePerformanceData($user),
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

        $stats = $user->getDashboardStats();
        
        // Additional DEO specific stats
        $deoStats = [
            'reports_edited_by_admin' => ReportAudit::where('original_creator_id', $user->id)
                ->whereHas('modifier', function($query) {
                    $query->whereHas('roles', function($roleQuery) {
                        $roleQuery->whereIn('name', ['admin', 'super_admin']);
                    });
                })
                ->count(),
            'recent_admin_edits' => ReportAudit::where('original_creator_id', $user->id)
                ->whereHas('modifier', function($query) {
                    $query->whereHas('roles', function($roleQuery) {
                        $roleQuery->whereIn('name', ['admin', 'super_admin']);
                    });
                })
                ->with(['modifier'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        $data = [
            'title' => 'Data Entry Operator Dashboard',
            'user' => $user,
            'stats' => array_merge($stats, $deoStats),
            'recentReports' => $user->getRecentReports(),
            'chartData' => $this->getYearWisePerformanceData($user),
        ];

        return view('dashboards.deo', $data);
    }
}
