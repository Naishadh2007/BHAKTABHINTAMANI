<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Main super admin — full access
        Admin::firstOrCreate(
            ['email' => 'naishad@ssgd.com'],
            [
                'name'           => 'Main Admin',
                'password'       => bcrypt('naishad@123'),
                'is_super_admin' => true,
                'permissions'    => null,
            ]
        );
    }
}
