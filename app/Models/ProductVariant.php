<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Tên hiển thị: "S / Đen"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->size} / {$this->color}";
    }

    /**
     * Kiểm tra còn hàng
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }
}
