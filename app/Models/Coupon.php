<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'start_date',
        'end_date',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(): bool
    {
        $today = Carbon::today();

        return $this->quantity > 0
            && $today->gte($this->start_date)
            && $today->lte($this->end_date);
    }

    /**
     * Kiểm tra user đã dùng coupon này chưa
     */
    public function isUsedByUser(int $userId): bool
    {
        return CouponUsage::hasUserUsedCoupon($userId, $this->id);
    }
}
