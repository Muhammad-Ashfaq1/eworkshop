<?php

namespace Database\Seeders;

use App\Constants\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Manual seeder — update existing Super Admin and Admin login credentials.
 *
 * Run on server:
 *   php artisan db:seed --class=UpdateAdminCredentialsSeeder
 *
 * Not registered in DatabaseSeeder, so normal deploys/seeders are unaffected.
 */
class UpdateAdminCredentialsSeeder extends Seeder
{
    private const SUPER_ADMIN_EMAIL = 'super-admin@eworkshop-elwmc.com';

    private const SUPER_ADMIN_PASSWORD = 'ELwmc@2026!$#';

    private const ADMIN_EMAIL = 'admin@eworkshop-elwmc.com';

    private const ADMIN_PASSWORD = 'AdminElwmc@2026!$#';

    public function run(): void
    {
        $superAdmin = User::role(UserRoles::SUPER_ADMIN)->orderBy('id')->first();

        if (! $superAdmin) {
            $this->command?->error('No super_admin user found. Nothing updated for super admin.');
        } else {
            $superAdmin->update([
                'email' => self::SUPER_ADMIN_EMAIL,
                'password' => Hash::make(self::SUPER_ADMIN_PASSWORD),
                'is_active' => true,
                'email_verified_at' => $superAdmin->email_verified_at ?? now(),
            ]);

            $this->command?->info('Super Admin updated:');
            $this->command?->line('  Email:    '.self::SUPER_ADMIN_EMAIL);
            $this->command?->line('  Password: '.self::SUPER_ADMIN_PASSWORD);
        }

        $admin = User::where('email', 'admin@example.com')->first()
            ?? User::role(UserRoles::ADMIN)->orderBy('id')->first();

        if (! $admin) {
            $this->command?->error('No admin user found. Nothing updated for admin.');
        } else {
            // Do not overwrite the super admin account if somehow the same row matched
            if ($admin->id === ($superAdmin->id ?? null)) {
                $this->command?->error('Admin lookup matched the super admin user. Skipping admin update.');

                return;
            }

            $admin->update([
                'email' => self::ADMIN_EMAIL,
                'password' => Hash::make(self::ADMIN_PASSWORD),
                'is_active' => true,
                'email_verified_at' => $admin->email_verified_at ?? now(),
            ]);

            $this->command?->info('Admin updated:');
            $this->command?->line('  Email:    '.self::ADMIN_EMAIL);
            $this->command?->line('  Password: '.self::ADMIN_PASSWORD);
        }

        $this->command?->warn('Store these credentials securely, then remove them from chat/logs if needed.');
    }
}
