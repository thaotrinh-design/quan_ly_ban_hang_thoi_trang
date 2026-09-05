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
            return collect();
        }

        $keyword = trim($keyword);
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
                ["%{$keyword}%", "%{$keyword}%", "%{$keyword}%", "%{$keyword}%", "%{$keyword}%"]
            )
            ->where(function ($q) use ($keyword, $words) {
                // Tìm theo tên (exact match ưu tiên cao)
                $q->where('name', 'like', "%{$keyword}%");

                // Tìm theo từng từ
                foreach ($words as $word) {
                    if (strlen($word) >= 2) {
                        $q->orWhere('name', 'like', "%{$word}%")
                            ->orWhere('description', 'like', "%{$word}%")
                            ->orWhereHas('category', function ($cq) use ($word) {
                                $cq->where('name', 'like', "%{$word}%");
                            })
                            ->orWhere('available_colors', 'like', "%{$word}%")
                            ->orWhere('available_sizes', 'like', "%{$word}%");
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

        // Gợi ý theo tên sản phẩm
        $products = Product::active()
            ->where('name', 'like', "%{$keyword}%")
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
        $categories = \App\Models\Category::where('name', 'like', "%{$keyword}%")
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
