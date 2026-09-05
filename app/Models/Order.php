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
        'vnpay_txn_ref',
        'vnpay_transaction_no',
        'vnpay_response_code',
        'vnpay_bank_code',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'float',
            'subtotal' => 'float',
            'discount_amount' => 'float',
            'paid_at' => 'datetime',
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

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderByDesc('created_at');
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

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function canBePaidOnline(): bool
    {
        return in_array($this->payment_method, ['vnpay']) && !$this->isPaid();
    }

    /**
     * Ghi lại lịch sử thay đổi trạng thái
     */
    public function logStatusChange(string $newStatus, ?string $note = null, ?string $changedBy = null): void
    {
        $oldStatus = $this->status;

        $this->update(['status' => $newStatus]);

        OrderStatusHistory::create([
            'order_id' => $this->id,
            'status' => $newStatus,
            'note' => $note ?? "Thay đổi từ \"$oldStatus\" sang \"$newStatus\"",
            'changed_by' => $changedBy ?? (auth()->check() ? auth()->user()->name : 'system'),
            'created_at' => now(),
        ]);
    }

    /**
     * Tạo status history ban đầu khi tạo đơn hàng
     */
    public function logInitialStatus(string $status = 'pending'): void
    {
        OrderStatusHistory::create([
            'order_id' => $this->id,
            'status' => $status,
            'note' => 'Đơn hàng được tạo thành công',
            'changed_by' => auth()->user()->name ?? 'customer',
            'created_at' => $this->created_at,
        ]);
    }
}
