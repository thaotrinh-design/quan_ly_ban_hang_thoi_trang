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
        $products = Product::with('category', 'variants')->withTrashed()->get();

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
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

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
            'stock' => $request->stock,
            'available_sizes' => $this->parseList($request->available_sizes),
            'available_colors' => $this->parseList($request->available_colors),
            'status' => $request->boolean('status', true),
            'image' => $imagePath,
        ]);

        // Tạo variants từ size + color
        $this->syncVariants($product, $request);

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
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'available_sizes' => $this->parseList($request->available_sizes),
            'available_colors' => $this->parseList($request->available_colors),
            'status' => $request->boolean('status', true),
            'image' => $imagePath,
        ]);

        // Cập nhật variants
        $this->syncVariants($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index');
    }

    public function restore(int $id)
    {
        Product::withTrashed()->find($id)->restore();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã khôi phục sản phẩm.');
    }

    public function forceDelete(int $id)
    {
        Product::withTrashed()->find($id)->forceDelete();

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

        // Xóa variants cũ
        $product->variants()->delete();

        // Tạo variants mới
        foreach ($sizes as $size) {
            foreach ($colors as $color) {
                $key = $size . '_' . $color;
                $stock = (int) ($variantStocks[$key] ?? 0);

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => $color,
                    'stock' => $stock,
                ]);
            }
        }

        // Cập nhật tổng stock từ variants
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