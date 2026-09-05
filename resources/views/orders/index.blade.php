@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mt-4">
    <h2>Lịch sử đơn hàng</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr><th>Mã đơn</th><th>Giá đơn hàng</th><th>Trạng thái</th><th>Ngày đặt</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php $subtotal = $order->getSubtotalAmount(); @endphp
            <tr>
                <td>#{{ $order->id }}</td>
                <td>
                    @if($order->hasDiscount())
                        <span class="text-muted text-decoration-line-through small">{{ number_format($subtotal) }} VNĐ</span><br>
                        <span class="text-danger fw-bold">{{ number_format($order->total) }} VNĐ</span>
                        <small class="badge bg-success ms-1">-{{ number_format($order->discount_amount) }}đ</small>
                        @if($order->coupon_code)<br><small class="text-muted">Mã: {{ $order->coupon_code }}</small>@endif
                    @else
                        <span class="fw-bold">{{ number_format($order->total) }} VNĐ</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info">{{ $order->status_label }}</span>
                    @if($order->payment_method === 'vnpay' && !$order->isPaid())
                        <br><small class="text-warning">Chưa thanh toán</small>
                    @endif
                    @if($order->payment_method === 'bank' && !$order->isPaid())
                        <br><small class="text-warning">Chờ xác nhận</small>
                    @endif
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">Chi tiết</a>
                    @if($order->payment_method === 'vnpay' && $order->canBePaidOnline())
                        <a href="{{ route('payment.vnpay', $order) }}" class="btn btn-sm btn-success">Thanh toán lại</a>
                    @endif
                    @if($order->payment_method === 'bank' && !$order->isPaid())
                        <a href="{{ route('payment.vietqr', $order) }}" class="btn btn-sm btn-info">Xem QR</a>
                    @endif
                    @if($order->canBeCancelled())
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hủy đơn?')">Hủy</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-muted">Chưa có đơn hàng.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
