<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['code' => 'WELCOME10', 'discount' => 10, 'quantity' => 100],
            ['code' => 'SUMMER20', 'discount' => 20, 'quantity' => 50],
            ['code' => 'VIP30', 'discount' => 30, 'quantity' => 20],
            ['code' => 'FLASH15', 'discount' => 15, 'quantity' => 80],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create([
                'code' => $coupon['code'],
                'discount' => $coupon['discount'],
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(3),
                'quantity' => $coupon['quantity'],
            ]);
        }
    }
}
