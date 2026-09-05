<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    /**
     * Fulltext search với nhiều tiêu chí và relevance scoring
     */
    public static function search(?string $keyword, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        if (!$keyword || strlen(trim($keyword)) < 2) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        $keyword = trim($keyword);
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $keyword);
        $words = explode(' ', $keyword);

        $products = Product::active()
            ->with('category')
            ->selectRaw('products.*, 
                CASE 
                    WHEN name LIKE ? THEN 10
                    WHEN name LIKE ? THEN 5
                    WHEN description LIKE ? THEN 3
                    WHEN available_colors LIKE ? THEN 2
                    WHEN available_sizes LIKE ? THEN 1
                    ELSE 0
                END as relevance',
                ["%{$escaped}%", "%{$escaped}%", "%{$escaped}%", "%{$escaped}%", "%{$escaped}%"]
            )
            ->where(function ($q) use ($escaped, $words) {
                $q->where('name', 'like', "%{$escaped}%");

                foreach ($words as $word) {
                    if (strlen($word) >= 2) {
                        $wEsc = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $word);
                        $q->orWhere('name', 'like', "%{$wEsc}%")
                            ->orWhere('description', 'like', "%{$wEsc}%")
                            ->orWhereHas('category', function ($cq) use ($wEsc) {
                                $cq->where('name', 'like', "%{$wEsc}%");
                            })
                            ->orWhere('available_colors', 'like', "%{$wEsc}%")
                            ->orWhere('available_sizes', 'like', "%{$wEsc}%");
                    }
                }
            })
            ->orderByDesc('relevance')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return $products;
    }

    /**
     * Gợi ý tìm kiếm nhanh (autocomplete)
     */
    public static function suggest(?string $keyword, int $limit = 5): array
    {
        if (!$keyword || strlen(trim($keyword)) < 2) {
            return [];
        }

        $keyword = trim($keyword);
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $keyword);

        // Gợi ý theo tên sản phẩm
        $products = Product::active()
            ->where('name', 'like', "%{$escaped}%")
            ->select('id', 'name', 'price', 'discount_percent', 'image')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getSellingPrice(),
                    'original_price' => $product->price,
                    'image' => $product->image_url,
                    'type' => 'product',
                ];
            });

        // Gợi ý theo danh mục
        $categories = \App\Models\Category::where('name', 'like', "%{$escaped}%")
            ->select('id', 'name')
            ->limit(3)
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'type' => 'category',
                ];
            });

        return array_merge($categories->toArray(), $products->toArray());
    }
}
