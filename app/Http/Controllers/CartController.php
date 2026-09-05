<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        $cart->load('items.product');
        $total = $this->cartService->getTotal($cart);

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'buy_now' => 'nullable|boolean',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!in_array($request->size, $product->getSizesList())) {
            return back()->with('error', 'Vui lòng chọn size hợp lệ.');
        }
        if (!in_array($request->color, $product->getColorsList())) {
            return back()->with('error', 'Vui lòng chọn màu hợp lệ.');
        }

        try {
            $cartItem = $this->cartService->addItem(
                auth()->user(),
                $request->product_id,
                $request->quantity ?? 1,
                $request->size,
                $request->color
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($request->boolean('buy_now')) {
            session(['checkout_item_ids' => [$cartItem->id]]);

            return redirect()->route('checkout.index');
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        try {
            $this->cartService->updateQuantity(
                auth()->user(),
                $itemId,
                $request->quantity
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function destroy(int $itemId)
    {
        $this->cartService->removeItem(auth()->user(), $itemId);

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
