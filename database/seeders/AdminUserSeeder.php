<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates the default admin account. The password is only set when the account is first created,
 * so running the seeder again never resets a password that has since been changed.
 */
class AdminUserSeeder extends Seeder
{
    public const EMAIL = 'admin@medzsolutions.com';

    public const PASSWORD = 'MedzAdmin#2026';

    public function run(): void
    {
        $admin = User::firstOrNew(['email' => self::EMAIL]);

        if (! $admin->exists) {
            $admin->name = 'Medz Admin';
            $admin->password = self::PASSWORD;
        }

        $admin->is_admin = true;
        $admin->save();
    }
}
