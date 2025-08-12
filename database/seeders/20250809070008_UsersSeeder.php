<?php

/**
 * Seeder: users_seeder
 * Generated: 2025-08-09 07:00:08
 */

use App\Models\User;

class UsersSeeder_20250809070008
{
    public function run(): void
    {
        User::create([
            'name'     => 'Umar Abbas',
            'email'    => 'umarabass.dev@gmail.com',
            'password' => password_hash('mypassword', PASSWORD_DEFAULT),
        ]);

        echo "[OK] Users table seeded.\n";
    }
}
