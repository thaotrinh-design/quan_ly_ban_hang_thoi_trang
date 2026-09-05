<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'variants')->withTrashed()->orderByDesc('id')->paginate(20)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view(
            'products.create',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $sizes = $this->parseList($request->available_sizes);
        $colors = $this->parseList($request->available_colors);
        $hasVariants = count($sizes) > 0 && count($colors) > 0;

        // Xác định stock
        if ($hasVariants) {
            $variantStocks = $request->input('variant_stock', []);
            $totalStock = 0;
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $key = $size . '_' . $color;
                    $stock = max(0, (int) ($variantStocks[$key] ?? 0));
                    $totalStock += $stock;
                }
            }
        } else {
            $totalStock = max(0, (int) ($request->input('stock_simple', 0)));
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $totalStock,
            'available_sizes' => $sizes,
            'available_colors' => $colors,
            'status' => $request->boolean('status', true),
            'image' => $imagePath,
        ]);

        // Tạo variants chỉ khi có size + color
        if ($hasVariants) {
            $this->syncVariants($product, $request);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('variants');

        return view(
            'products.edit',
            compact('product', 'categories')
        );
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $sizes = $this->parseList($request->available_sizes);
        $colors = $this->parseList($request->available_colors);
        $hasVariants = count($sizes) > 0 && count($colors) > 0;

        if ($hasVariants) {
            $variantStocks = $request->input('variant_stock', []);
            $totalStock = 0;
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $key = $size . '_' . $color;
                    $stock = max(0, (int) ($variantStocks[$key] ?? 0));
                    $totalStock += $stock;
                }
            }
        } else {
            $totalStock = max(0, (int) ($request->input('stock_simple', $product->stock)));
        }

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $product->id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $totalStock,
            'available_sizes' => $sizes,
            'available_colors' => $colors,
            'status' => $request->boolean('status', true),
            'image' => $imagePath,
        ]);

        $product->variants()->delete();

        if ($hasVariants) {
            $this->syncVariants($product, $request);
        }

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy(Product $product)
    {
        if ($product->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa sản phẩm có đơn hàng. Hãy ẩn sản phẩm thay vì xóa.');
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    public function restore(int $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã khôi phục sản phẩm.');
    }

    public function forceDelete(int $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa vĩnh viễn sản phẩm có đơn hàng.');
        }

        $product->forceDelete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã xóa vĩnh viễn sản phẩm.');
    }

    /**
     * Đồng bộ variants từ form data
     */
    private function syncVariants(Product $product, Request $request): void
    {
        $sizes = $this->parseList($request->available_sizes);
        $colors = $this->parseList($request->available_colors);
        $variantStocks = $request->input('variant_stock', []);

        foreach ($sizes as $size) {
            foreach ($colors as $color) {
                $key = $size . '_' . $color;
                $stock = max(0, (int) ($variantStocks[$key] ?? 0));

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => $color,
                    'stock' => $stock,
                ]);
            }
        }

        $totalStock = $product->getTotalVariantStock();
        $product->update(['stock' => $totalStock]);
    }

    private function parseList(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}