<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()
            ->wishlists()
            ->with('product.category')
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Đã thêm vào danh sách yêu thích.');
    }

    public function destroy(int $productId)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();

        return back()->with('success', 'Đã xóa khỏi danh sách yêu thích.');
    }
}
