@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h2>Dashboard</h2>
<div class="row mt-4">
    <div class="col-md-3"><div class="card p-3 text-center"><h3>{{ $stats['products'] }}</h3><p>Sản phẩm</p></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><h3>{{ $stats['orders'] }}</h3><p>Đơn hàng</p></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><h3>{{ $stats['customers'] }}</h3><p>Khách hàng</p></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><h3>{{ number_format($stats['revenue']) }}</h3><p>Doanh thu (VNĐ)</p></div></div>
</div>
<h4 class="mt-5">Đơn hàng gần đây</h4>
<table class="table table-bordered mt-2">
    <thead><tr><th>ID</th><th>Khách</th><th>Tổng</th><th>Trạng thái</th><th>Ngày</th></tr></thead>
    <tbody>
        @foreach($recentOrders as $order)
        <tr>
            <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
            <td>{{ $order->user->name ?? '' }}</td>
            <td>{{ number_format($order->total) }} VNĐ</td>
            <td>{{ $order->status_label }}</td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
