<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $revenueByDay = Order::where('status', 'completed')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $bestSelling = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(quantity * price) as revenue')
        )
            ->whereHas('order', function ($q) use ($from, $to) {
                $q->where('status', 'completed')
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $totalRevenue = $revenueByDay->sum('revenue');

        return view('admin.reports.index', compact(
            'revenueByDay',
            'bestSelling',
            'totalRevenue',
            'from',
            'to'
        ));
    }

    public function export(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $orders = Order::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->with('user')
            ->get();

        $filename = 'bao-cao-' . $from . '-' . $to . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Khách hàng', 'Tổng tiền', 'Trạng thái', 'Ngày tạo']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? '',
                    $order->total,
                    $order->status,
                    $order->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
