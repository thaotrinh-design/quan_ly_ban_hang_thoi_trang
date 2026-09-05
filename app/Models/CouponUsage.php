<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'coupon_id',
        'user_id',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
        ];
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kiểm tra user đã dùng coupon này chưa
     */
    public static function hasUserUsedCoupon(int $userId, int $couponId): bool
    {
        return self::where('user_id', $userId)
            ->where('coupon_id', $couponId)
            ->exists();
    }
}
