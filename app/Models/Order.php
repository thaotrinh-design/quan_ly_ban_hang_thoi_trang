<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'address',
        'order_type',
        'subtotal',
        'coupon_code',
        'discount_amount',
        'payment_method',
        'note',
        'total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'float',
            'subtotal' => 'float',
            'discount_amount' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Đã thanh toán',
            'cancelled' => 'Đã hủy',
            default => ucfirst((string) $this->status),
        };
    }

    public function getSubtotalAmount(): float
    {
        if ($this->subtotal > 0) {
            return $this->subtotal;
        }

        if ($this->relationLoaded('items')) {
            return $this->items->sum(fn ($item) => $item->price * $item->quantity);
        }

        return $this->total + $this->discount_amount;
    }

    public function hasDiscount(): bool
    {
        return $this->discount_amount > 0;
    }
}
