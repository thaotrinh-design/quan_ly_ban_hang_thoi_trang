<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\SearchService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->ajax_search) {
            $results = SearchService::suggest($request->keyword);
            return response()->json($results);
        }

        return $this->renderShop($request, 'Tất cả sản phẩm', 'Khám phá toàn bộ sản phẩm của TINA STORE', 'collection');
    }

    public function collection(Request $request)
    {
        return $this->renderShop($request, 'Bộ sưu tập', 'Khám phá toàn bộ sản phẩm của TINA STORE', 'collection');
    }

    public function sale(Request $request)
    {
        return $this->renderShop($request, 'Sale', 'Tất cả sản phẩm đang giảm giá', 'sale', [
            'discountedOnly' => true,
        ]);
    }

    public function shirts(Request $request)
    {
        return $this->renderShop($request, 'Áo', 'Tất cả sản phẩm áo của cửa hàng', 'shirts', [
            'categoryNames' => ['Áo thun nam', 'Áo sơ mi', 'Áo khoác', 'Áo len & Hoodie'],
        ]);
    }

    public function pants(Request $request)
    {
        return $this->renderShop($request, 'Quần', 'Tất cả sản phẩm quần của cửa hàng', 'pants', [
            'categoryNames' => ['Quần jean', 'Quần short'],
        ]);
    }

    public function accessories(Request $request)
    {
        return $this->renderShop($request, 'Phụ kiện', 'Tất cả sản phẩm phụ kiện của cửa hàng', 'accessories', [
            'categoryNames' => ['Phụ kiện', 'Túi xách', 'Giày dép'],
        ]);
    }

    public function featured(Request $request)
    {
        return $this->renderShop($request, 'Sản phẩm nổi bật', 'Các sản phẩm được chọn lọc từ TINA STORE', 'featured', [
            'featuredOnly' => true,
        ]);
    }

    public function category(Category $category, Request $request)
    {
        $request->merge(['category_id' => $category->id]);

        return $this->renderShop($request, $category->name, $category->description ?? 'Sản phẩm theo danh mục', 'collection');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user']);

        $relatedProducts = Product::active()
            ->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $inWishlist = false;
        $canReview = false;

        if (auth()->check()) {
            $inWishlist = auth()->user()
                ->wishlists()
                ->where('product_id', $product->id)
                ->exists();

            $canReview = auth()->user()->hasPurchasedProduct($product->id);
        }

        return view('shop.show', compact('product', 'relatedProducts', 'inWishlist', 'canReview'));
    }

    private function renderShop(Request $request, string $title, string $subtitle, string $activeTab, array $filters = [])
    {
        $query = Product::active()->with('category');

        if (!empty($filters['featuredOnly'])) {
            $query->featured();
        }

        if (!empty($filters['discountedOnly'])) {
            $query->where('discount_percent', '>', 0);
        }

        if (!empty($filters['categoryNames'])) {
            $query->whereHas('category', function ($categoryQuery) use ($filters) {
                $categoryQuery->whereIn('name', $filters['categoryNames']);
            });
        }

        $query->search($request->keyword);

        if ($request->price_range) {
            $query->priceRange($request->price_range);
        } else {
            $query->priceBetween($request->min_price, $request->max_price);
        }

        $query->filterSize($request->size)
            ->filterColor($request->color)
            ->sortBy($request->sort);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', true)->get();
        $sizes = Product::collectFilterSizes();
        $colors = Product::collectFilterColors();
        $priceRanges = config('store.price_ranges');

        return view('shop.index', compact(
            'products',
            'categories',
            'sizes',
            'colors',
            'priceRanges',
            'title',
            'subtitle',
            'activeTab'
        ));
    }
}
