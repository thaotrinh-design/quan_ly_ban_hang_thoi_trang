<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderByDesc('id');

        if ($request->status) {
            $allowedStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user', 'statusHistory');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
        ]);

        DB::transaction(function () use ($request, $order) {
            $order->load('items.product');

            $cancellableStatuses = ['pending', 'processing'];

            if ($request->status === 'cancelled' && in_array($order->status, $cancellableStatuses)) {
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
            } elseif ($order->status === 'cancelled' && $request->status !== 'cancelled') {
                $insufficientItems = [];
                foreach ($order->items as $item) {
                    if ($item->size && $item->color) {
                        $decremented = ProductVariant::where('product_id', $item->product_id)
                            ->where('size', $item->size)
                            ->where('color', $item->color)
                            ->where('stock', '>=', $item->quantity)
                            ->decrement('stock', $item->quantity);
                        if ($decremented === 0) {
                            $insufficientItems[] = $item->product->name . " ({$item->size}/{$item->color})";
                        }
                    } elseif ($item->product) {
                        $decremented = Product::where('id', $item->product_id)
                            ->where('stock', '>=', $item->quantity)
                            ->decrement('stock', $item->quantity);
                        if ($decremented === 0) {
                            $insufficientItems[] = $item->product->name;
                        }
                    }
                }
                if (!empty($insufficientItems)) {
                    throw new \RuntimeException('Sản phẩm không đủ tồn kho: ' . implode(', ', $insufficientItems));
                }
            }

            $order->logStatusChange($request->status, 'Admin cập nhật trạng thái', auth()->user()?->name ?? 'admin');
        });

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
