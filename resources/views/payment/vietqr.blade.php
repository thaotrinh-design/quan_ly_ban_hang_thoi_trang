@extends('layouts.app')

@section('title', 'Thanh toán chuyển khoản - Đơn hàng #' . $order->id)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thanh toán chuyển khoản ngân hàng</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted">Quét mã QR bằng ứng dụng ngân hàng để thanh toán</p>

                    <div class="my-3">
                        <img src="{{ $qrInfo['qr_url'] }}" alt="VietQR" class="img-fluid" style="max-width: 300px;">
                    </div>

                    <hr>

                    <div class="text-start">
                        <p><strong>Ngân hàng:</strong> {{ $qrInfo['bank_name'] }}</p>
                        <p><strong>Số tài khoản:</strong> {{ $qrInfo['account_number'] }}</p>
                        <p><strong>Chủ tài khoản:</strong> {{ $qrInfo['account_holder'] }}</p>
                        <p><strong>Số tiền:</strong> <span class="text-danger fw-bold">{{ number_format($qrInfo['amount']) }} VNĐ</span></p>
                        <p><strong>Nội dung CK:</strong> <code>{{ $qrInfo['purpose'] }}</code></p>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <small>
                            Đơn hàng #{{ $order->id }} sẽ được xác nhận sau khi nhận được thanh toán.
                            <br>Vui lòng chuyển khoản đúng số tiền và nội dung.
                        </small>
                    </div>

                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">
                        Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
