@extends('layouts.admin')

@section('title', 'Đơn #' . $order->id)

@section('content')
<h2>Đơn hàng #{{ $order->id }}</h2>
<div class="card p-3 mt-3">
    <p><strong>Khách:</strong> {{ $order->user?->name ?? '' }} ({{ $order->user?->email ?? '' }})</p>
    <p><strong>Người nhận:</strong> {{ $order->receiver_name }} - {{ $order->phone }}</p>
    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
    <p><strong>Thanh toán:</strong> {{ $order->payment_method }}</p>
    <p><strong>Trạng thái hiện tại:</strong> {{ $order->status_label }}</p>
    @include('partials.order-pricing', ['order' => $order])
</div>
<table class="table table-bordered mt-3">
    <thead><tr><th>Sản phẩm</th><th>SL</th><th>Giá</th><th>Thành tiền</th></tr></thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product?->name ?? 'N/A' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price) }}</td>
            <td>{{ number_format($item->price * $item->quantity) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><th colspan="3" class="text-end">Tạm tính</th><th>{{ number_format($order->getSubtotalAmount()) }} VNĐ</th></tr>
        @if($order->hasDiscount())
        <tr><th colspan="3" class="text-end text-success">Giảm giá</th><th class="text-success">-{{ number_format($order->discount_amount) }} VNĐ</th></tr>
        @endif
        <tr><th colspan="3" class="text-end">Thành tiền</th><th>{{ number_format($order->total) }} VNĐ</th></tr>
    </tfoot>
</table>
<form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-3">
    @csrf @method('PUT')
    <div class="row g-2 align-items-end">
        <div class="col-auto">
            <label>Cập nhật trạng thái</label>
            <select name="status" class="form-select">
                @foreach([
                    'pending' => 'Chờ xử lý',
                    'processing' => 'Đang xử lý',
                    'shipping' => 'Đang giao',
                    'completed' => 'Đã thanh toán',
                    'cancelled' => 'Đã hủy',
                ] as $value => $label)
                <option value="{{ $value }}" {{ $order->status==$value?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-success">Cập nhật</button></div>
    </div>
</form>

<x-order-timeline :history="$order->statusHistory" />
@endsection
