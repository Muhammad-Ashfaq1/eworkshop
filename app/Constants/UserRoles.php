<?php

namespace App\Constants;

class UserRoles
{
    const SUPER_ADMIN = 'super_admin';

    const ADMIN = 'admin';

    const DEO = 'deo';

    /**
     * Get all available roles
     */
    public static function getAllRoles()
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::DEO,
        ];
    }

    /**
     * Get role display names
     */
    public static function getRoleDisplayNames()
    {
        return [
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::DEO => 'Data Entry Operator',
        ];
    }

    /**
     * Get dashboard route names for roles
     */
    public static function getDashboardRoutes()
    {
        return [
            self::SUPER_ADMIN => 'dashboard.super_admin',
            self::ADMIN => 'dashboard.admin',
            self::DEO => 'dashboard.deo',
        ];
    }
}
