<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with('category')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        if ($featuredProducts->count() < 8) {
            $featuredProducts = Product::active()
                ->with('category')
                ->orderByDesc('id')
                ->limit(8)
                ->get();
        }

        $categories = Category::where('status', true)->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
