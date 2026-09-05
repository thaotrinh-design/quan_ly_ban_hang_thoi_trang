<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            return back()->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
        }

        DB::transaction(function () use ($order) {
            $affected = Order::where('id', $order->id)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'cancelled']);

            if ($affected === 0) {
                throw new \RuntimeException('Đơn hàng đã được hủy trước đó.');
            }

            $order->refresh();
            $order->load('items.product');

            foreach ($order->items as $item) {
                if ($item->size && $item->color) {
                    ProductVariant::where('product_id', $item->product_id)
                        ->where('size', $item->size)
                        ->where('color', $item->color)
                        ->increment('stock', $item->quantity);
                } elseif ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->logStatusChange('cancelled', 'Khách hàng hủy đơn hàng');
        });

        return back()->with('success', 'Đã hủy đơn hàng.');
    }
}
