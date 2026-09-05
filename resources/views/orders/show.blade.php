@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container mt-4">
    <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>
    <div class="card p-3 mt-3">
        <p><strong>Trạng thái:</strong> <span class="badge bg-info">{{ $order->status_label }}</span></p>
        <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
        <p><strong>Điện thoại:</strong> {{ $order->phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
        <p><strong>Hình thức:</strong> {{ ($order->order_type ?? 'delivery') === 'pickup' ? 'Mua tại chỗ' : 'Giao hàng' }}</p>
        <p><strong>Thanh toán:</strong>
            @if($order->payment_method === 'cod')
                COD
            @elseif($order->payment_method === 'bank')
                Chuyển khoản (VietQR)
            @elseif($order->payment_method === 'vnpay')
                VNPay
            @else
                {{ $order->payment_method }}
            @endif
        </p>
        @if($order->payment_method === 'vnpay' && $order->isPaid())
            <p><strong>Mã GD VNPay:</strong> {{ $order->vnpay_transaction_no }}</p>
            <p><strong>Ngân hàng:</strong> {{ $order->vnpay_bank_code }}</p>
            <p><strong>Ngày thanh toán:</strong> {{ $order->paid_at->format('d/m/Y H:i') }}</p>
        @endif
        @include('partials.order-pricing', ['order' => $order])
    </div>
    <table class="table table-bordered mt-3">
        <thead><tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product?->name ?? 'N/A' }} @if($item->size)<br><small>{{ $item->size }} / {{ $item->color }}</small>@endif</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price) }} VNĐ</td>
                <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <x-order-timeline :history="$order->statusHistory" />

    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection
