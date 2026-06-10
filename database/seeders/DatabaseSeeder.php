<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '0123456789',
            'role' => 'admin',
            'status' => 'active',
            'password' => 'Admin@123',
        ]);

        User::create([
            'name' => 'Khách hàng demo',
            'email' => 'customer@gmail.com',
            'phone' => '0987654321',
            'role' => 'customer',
            'status' => 'active',
            'password' => 'Customer@123',
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
