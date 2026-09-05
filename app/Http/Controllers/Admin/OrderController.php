<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderByDesc('id');

        if ($request->status) {
            $query->where('status', $request->status);
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
            if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
                $order->load('items.product');

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }

                    if ($item->size && $item->color) {
                        ProductVariant::where('product_id', $item->product_id)
                            ->where('size', $item->size)
                            ->where('color', $item->color)
                            ->increment('stock', $item->quantity);
                    }
                }
            }

            $order->logStatusChange($request->status, 'Admin cập nhật trạng thái', auth()->user()?->name ?? 'admin');
        });

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
