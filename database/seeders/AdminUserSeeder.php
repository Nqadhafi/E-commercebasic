<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@local.test'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'), // ganti sesuai kebutuhan
                'role' => 'admin',
                'phone' => '628123456789',
                'wa_opt_in' => true
            ]
        );
    }
}
