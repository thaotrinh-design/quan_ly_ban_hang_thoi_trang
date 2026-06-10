<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index()
    {
        $user = auth()->user();
        $cart = $this->cartService->getOrCreateCart($user);
        $cart->load('items.product');

        $checkoutItems = $this->resolveCheckoutItems($cart);

        if ($checkoutItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống hoặc chưa chọn sản phẩm cần thanh toán.');
        }

        $total = $checkoutItems->sum(fn ($item) => $item->product->getSellingPrice() * $item->quantity);
        $addresses = $user->addresses;
        $store = config('store');

        return view('checkout.index', compact('cart', 'checkoutItems', 'total', 'addresses', 'store'));
    }

    public function selectItems(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'integer',
        ]);

        $user = auth()->user();
        $cart = $this->cartService->getOrCreateCart($user);

        $selectedIds = $cart->items()
            ->whereIn('id', $request->item_ids)
            ->pluck('id')
            ->values()
            ->all();

        if (empty($selectedIds)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một sản phẩm hợp lệ.');
        }

        session(['checkout_item_ids' => $selectedIds]);

        return redirect()->route('checkout.index');
    }

    public function placeOrder(Request $request)
    {
        $isPickup = $request->order_type === 'pickup';

        $rules = [
            'order_type' => 'required|in:delivery,pickup',
            'payment_method' => 'required|in:cod,bank' . ($isPickup ? '' : ',ewallet'),
            'coupon_code' => 'nullable|string',
            'note' => 'nullable|string|max:500',
        ];

        if ($isPickup) {
            $rules['receiver_name'] = 'required|string|max:255';
            $rules['phone'] = 'required|string|max:20';
        } else {
            $rules['receiver_name'] = 'required|string|max:255';
            $rules['phone'] = 'required|string|max:20';
            $rules['address'] = 'required|string|max:500';
        }

        $request->validate($rules);

        $user = auth()->user();
        $cart = $this->cartService->getOrCreateCart($user);
        $cart->load('items.product');

        $checkoutItems = $this->resolveCheckoutItems($cart);

        if ($checkoutItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống.');
        }

        $subtotal = $checkoutItems->sum(fn ($item) => $item->product->getSellingPrice() * $item->quantity);
        $discount = 0;
        $couponCode = null;

        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon || !$coupon->isValid()) {
                return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
            }

            $discount = $subtotal * ($coupon->discount / 100);
            $couponCode = $coupon->code;
            $coupon->decrement('quantity');
        }

        $total = max(0, $subtotal - $discount);
        $store = config('store');
        $address = $isPickup ? $store['address'] . ' (Mua tại chỗ)' : $request->address;

        $status = $isPickup ? 'completed' : 'pending';

        DB::transaction(function () use ($user, $cart, $checkoutItems, $request, $subtotal, $total, $discount, $couponCode, $address, $isPickup, $status) {
            $order = Order::create([
                'user_id' => $user->id,
                'receiver_name' => $request->receiver_name,
                'phone' => $request->phone,
                'address' => $address,
                'order_type' => $request->order_type,
                'subtotal' => $subtotal,
                'coupon_code' => $couponCode,
                'discount_amount' => $discount,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'total' => $total,
                'status' => $status,
            ]);

            foreach ($checkoutItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'size' => $item->size,
                    'color' => $item->color,
                    'quantity' => $item->quantity,
                    'price' => $item->product->getSellingPrice(),
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $selectedIds = $checkoutItems->pluck('id')->all();

            if (count($selectedIds) === $cart->items->count()) {
                $this->cartService->clear($cart);
            } else {
                $cart->items()->whereIn('id', $selectedIds)->delete();
            }

            session()->forget('checkout_item_ids');
        });

        $msg = $isPickup
            ? 'Đặt hàng mua tại quầy thành công! Đơn hàng đã được ghi nhận là đã thanh toán.'
            : 'Đặt hàng thành công!';

        return redirect()->route('orders.index')->with('success', $msg);
    }

    private function resolveCheckoutItems($cart)
    {
        $selectedIds = session('checkout_item_ids');

        if (is_array($selectedIds) && !empty($selectedIds)) {
            return $cart->items->filter(fn ($item) => in_array($item->id, $selectedIds))->values();
        }

        return $cart->items->values();
    }
}
