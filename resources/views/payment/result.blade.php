@extends('layouts.app')

@section('title', 'Kết quả thanh toán')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header {{ $status === 'success' ? 'bg-success' : 'bg-danger' }} text-white">
                    <h5 class="mb-0">Kết quả thanh toán</h5>
                </div>
                <div class="card-body text-center">
                    @if($status === 'success')
                        <div class="text-success mb-3">
                            <i class="bi bi-check-circle" style="font-size: 60px;"></i>
                        </div>
                        <h4 class="text-success">Thanh toán thành công!</h4>
                        <p class="text-muted">{{ $message }}</p>

                        @if($order)
                            <hr>
                            <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
                            <p><strong>Tổng tiền:</strong> {{ number_format($order->total) }} VNĐ</p>
                            <p><strong>Phương thức:</strong> VNPay</p>
                            <p><strong>Mã giao dịch:</strong> {{ $order->vnpay_transaction_no }}</p>
                            <p><strong>Ngân hàng:</strong> {{ $order->vnpay_bank_code }}</p>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('orders.show', $order->id ?? 1) }}" class="btn btn-primary">
                                Xem đơn hàng
                            </a>
                            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    @else
                        <div class="text-danger mb-3">
                            <i class="bi bi-x-circle" style="font-size: 60px;"></i>
                        </div>
                        <h4 class="text-danger">Thanh toán thất bại</h4>
                        <p class="text-muted">{{ $message }}</p>

                        <div class="mt-3">
                            <a href="{{ route('orders.index') }}" class="btn btn-primary">
                                Xem đơn hàng
                            </a>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                Quay lại giỏ hàng
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
