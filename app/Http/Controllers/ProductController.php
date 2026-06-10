<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

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

            $imagePath = $request
                ->file('image')
                ->store('products', 'public');
        }

        Product::create([
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

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Thêm sản phẩm thành công'
            );
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view(
            'products.edit',
            compact(
                'product',
                'categories'
            )
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

    return redirect()->route('admin.products.index');
}

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index');
    }

    private function parseList(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}