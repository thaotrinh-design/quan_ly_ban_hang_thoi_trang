<?php

namespace App\Models;

use App\Helpers\ProductImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_percent',
        'stock',
        'is_featured',
        'size',
        'color',
        'available_sizes',
        'available_colors',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'price' => 'float',
            'stock' => 'integer',
            'discount_percent' => 'integer',
            'available_sizes' => 'array',
            'available_colors' => 'array',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Lấy tồn kho theo size + color
     * Fallback về stock sản phẩm nếu không có variant
     */
    public function getStockByVariant(?string $size, ?string $color): int
    {
        if (!$size || !$color) {
            return $this->stock;
        }

        $variant = $this->variants()
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        // Nếu không tìm thấy variant → fallback về stock sản phẩm
        if (!$variant) {
            return $this->stock;
        }

        return $variant->stock;
    }

    /**
     * Kiểm tra tồn kho theo variant
     */
    public function isVariantInStock(?string $size, ?string $color): bool
    {
        return $this->getStockByVariant($size, $color) > 0;
    }

    /**
     * Lấy tổng tồn kho từ tất cả variants
     */
    public function getTotalVariantStock(): int
    {
        return $this->variants()->sum('stock');
    }

    public function getSizesList(): array
    {
        $sizes = $this->available_sizes ?? [];

        if (empty($sizes) && $this->size) {
            return [$this->size];
        }

        return $sizes;
    }

    public function getColorsList(): array
    {
        $colors = $this->available_colors ?? [];

        if (empty($colors) && $this->color) {
            return [$this->color];
        }

        return $colors;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function hasDiscount(): bool
    {
        return $this->discount_percent > 0;
    }

    public function getSellingPrice(): float
    {
        if (!$this->hasDiscount()) {
            return $this->price;
        }

        return round($this->price * (1 - $this->discount_percent / 100));
    }

    /**
     * Fulltext search - tìm kiếm trên nhiều trường
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim($keyword ?? '');

        if ($keyword === '') {
            return $query;
        }

        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $keyword);

        $query->where(function ($q) use ($escaped) {
            $q->where('name', 'like', "%{$escaped}%")
                ->orWhere('description', 'like', "%{$escaped}%")
                ->orWhereHas('category', function ($cq) use ($escaped) {
                    $cq->where('name', 'like', "%{$escaped}%");
                })
                ->orWhere('available_colors', 'like', "%{$escaped}%")
                ->orWhere('available_sizes', 'like', "%{$escaped}%");
        });

        return $query;
    }

    public function scopePriceBetween(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null && $min !== '') {
            $query->where('price', '>=', $min);
        }
        if ($max !== null && $max !== '') {
            $query->where('price', '<=', $max);
        }

        return $query;
    }

    public function scopePriceRange(Builder $query, ?string $range): Builder
    {
        if (!$range || !str_contains($range, '-')) {
            return $query;
        }

        [$min, $max] = explode('-', $range, 2);

        if ($min !== '') {
            $query->where('price', '>=', (float) $min);
        }
        if ($max !== '') {
            $query->where('price', '<=', (float) $max);
        }

        return $query;
    }

    public function scopeFilterSize(Builder $query, ?string $size): Builder
    {
        if ($size) {
            $query->where(function ($q) use ($size) {
                $q->whereJsonContains('available_sizes', $size)
                    ->orWhere('size', $size);
            });
        }

        return $query;
    }

    public function scopeFilterColor(Builder $query, ?string $color): Builder
    {
        if ($color) {
            $query->where(function ($q) use ($color) {
                $q->whereJsonContains('available_colors', $color)
                    ->orWhere('color', $color);
            });
        }

        return $query;
    }

    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && !str_starts_with($this->image, 'http')) {
            return asset('storage/' . $this->image);
        }

        $categoryName = $this->relationLoaded('category')
            ? $this->category?->name
            : $this->category()->value('name');

        return ProductImageHelper::urlForProduct($this->name, $categoryName, $this->id);
    }

    public static function collectFilterSizes(): array
    {
        return Product::active()->get()
            ->flatMap(fn ($p) => $p->getSizesList())
            ->unique()->sort()->values()->all();
    }

    public static function collectFilterColors(): array
    {
        return Product::active()->get()
            ->flatMap(fn ($p) => $p->getColorsList())
            ->unique()->sort()->values()->all();
    }
}
