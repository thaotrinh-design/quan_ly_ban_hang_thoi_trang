@php
    $subtotal = $order->getSubtotalAmount();
    $hasDiscount = $order->hasDiscount();
@endphp

<div class="order-pricing mt-2">
    <div class="d-flex justify-content-between">
        <span>Tạm tính (chưa áp voucher):</span>
        <span>{{ number_format($subtotal) }} VNĐ</span>
    </div>
    @if($hasDiscount)
    <div class="d-flex justify-content-between text-success">
        <span>Giảm giá @if($order->coupon_code)({{ $order->coupon_code }})@endif:</span>
        <span>-{{ number_format($order->discount_amount) }} VNĐ</span>
    </div>
    <div class="d-flex justify-content-between fw-bold text-danger fs-5 mt-1">
        <span>Thành tiền sau giảm:</span>
        <span>{{ number_format($order->total) }} VNĐ</span>
    </div>
    @else
    <div class="d-flex justify-content-between fw-bold fs-5 mt-1">
        <span>Thành tiền:</span>
        <span>{{ number_format($order->total) }} VNĐ</span>
    </div>
    @endif
</div>
