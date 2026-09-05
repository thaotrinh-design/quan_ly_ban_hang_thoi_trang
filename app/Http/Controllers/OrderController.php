<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->orderByDesc('id')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product', 'statusHistory');

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        $order->load('items.product');

        foreach ($order->items as $item) {
            // Khôi phục tồn kho sản phẩm
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }

            // Khôi phục tồn kho variant
            if ($item->size && $item->color) {
                ProductVariant::where('product_id', $item->product_id)
                    ->where('size', $item->size)
                    ->where('color', $item->color)
                    ->increment('stock', $item->quantity);
            }
        }

        $order->logStatusChange('cancelled', 'Khách hàng hủy đơn hàng');

        return back()->with('success', 'Đã hủy đơn hàng.');
    }
}
