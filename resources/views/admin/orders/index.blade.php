@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<h2>Quản lý đơn hàng</h2>
<form class="row g-2 mt-2 mb-3">
    <div class="col-auto">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Tất cả trạng thái</option>
            @foreach([
                'pending' => 'Chờ xử lý',
                'processing' => 'Đang xử lý',
                'shipping' => 'Đang giao',
                'completed' => 'Đã thanh toán',
                'cancelled' => 'Đã hủy',
            ] as $value => $label)
            <option value="{{ $value }}" {{ request('status')==$value?'selected':'' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</form>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Khách</th><th>Tổng</th><th>Trạng thái</th><th>Ngày</th><th></th></tr></thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->user?->name ?? 'N/A' }}</td>
            <td>{{ number_format($order->total) }} VNĐ</td>
            <td>{{ $order->status_label }}</td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">Chi tiết</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">{{ $orders->links() }}</div>
@endsection
