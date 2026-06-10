@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container mt-4 mb-4">
    <h2>Đặt hàng</h2>
    <div class="row mt-3">
        <div class="col-md-7">
            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                @csrf

                <h5 class="mb-3">Hình thức nhận hàng</h5>
                <div class="btn-group w-100 mb-4" role="group">
                    <input type="radio" class="btn-check" name="order_type" id="type-delivery" value="delivery" checked>
                    <label class="btn btn-outline-dark" for="type-delivery"><i class="fa-solid fa-truck me-1"></i> Giao hàng</label>
                    <input type="radio" class="btn-check" name="order_type" id="type-pickup" value="pickup">
                    <label class="btn btn-outline-dark" for="type-pickup"><i class="fa-solid fa-store me-1"></i> Mua tại chỗ</label>
                </div>

                <div id="delivery-section">
                    <h5>Thông tin giao hàng</h5>
                    <div class="mb-2">
                        <label>Họ tên người nhận</label>
                        <input type="text" name="receiver_name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Địa chỉ nhận hàng</label>
                        <textarea name="address" id="address-field" class="form-control">{{ $addresses->where('is_default', true)->first()->address ?? '' }}</textarea>
                    </div>
                    @if($addresses->count())
                    <div class="mb-3">
                        <small class="text-muted">Địa chỉ đã lưu:</small>
                        @foreach($addresses as $addr)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" onclick="fillAddress('{{ $addr->receiver_name }}','{{ $addr->phone }}','{{ $addr->address }}')">
                            <label class="form-check-label">{{ $addr->receiver_name }} - {{ $addr->address }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div id="pickup-section" class="d-none">
                    <h5>Thông tin nhận tại cửa hàng</h5>
                    <div class="alert alert-info">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        <strong>Cửa hàng:</strong> {{ $store['address'] }}<br>
                        <i class="fa-solid fa-clock me-1"></i> {{ $store['hours'] }}
                    </div>
                    <div class="mb-2">
                        <label>Họ tên</label>
                        <input type="text" name="pickup_name" class="form-control" value="{{ auth()->user()->name }}">
                    </div>
                    <div class="mb-2">
                        <label>Số điện thoại</label>
                        <input type="text" name="pickup_phone" class="form-control" value="{{ auth()->user()->phone }}">
                    </div>
                </div>

                <h5 class="mt-4">Mã giảm giá</h5>
                <input type="text" name="coupon_code" class="form-control mb-3" placeholder="Nhập mã giảm giá">

                <h5>Phương thức thanh toán</h5>
                <div id="payment-delivery">
                    <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="cod" checked> <label>Thanh toán khi nhận hàng (COD)</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="bank"> <label>Chuyển khoản ngân hàng</label></div>
                    <div class="form-check mb-2"><input class="form-check-input" type="radio" name="payment_method" value="ewallet"> <label>Ví điện tử</label></div>
                </div>
                <div id="payment-pickup" class="d-none">
                    <div class="form-check"><input class="form-check-input" type="radio" name="payment_method_pickup" value="cod" checked> <label>Thanh toán tại quầy (COD)</label></div>
                    <div class="form-check mb-2"><input class="form-check-input" type="radio" name="payment_method_pickup" value="bank"> <label>Chuyển khoản (quét QR)</label></div>
                </div>

                <div id="bank-qr-box" class="card p-3 mb-3 d-none text-center">
                    <h6>Quét mã QR để thanh toán</h6>
                    <img src="{{ $store['bank_qr'] }}" alt="QR thanh toán" class="mb-2" width="220">
                    <p class="small mb-0">
                        {{ $store['bank_name'] }} | STK: <strong>{{ $store['bank_account'] }}</strong><br>
                        Chủ TK: {{ $store['bank_holder'] }}<br>
                        Nội dung: Thanh toan don hang + SĐT
                    </p>
                </div>

                <div class="mb-3">
                    <label>Ghi chú</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>

                <button class="btn btn-success btn-lg">Xác nhận đơn hàng</button>
            </form>
        </div>
        <div class="col-md-5">
            <div class="card p-3">
                <h5>Đơn hàng</h5>
                @foreach($checkoutItems as $item)
                <div class="d-flex justify-content-between mb-2">
                    <span>
                        {{ $item->product->name }} x{{ $item->quantity }}
                        @if($item->size)<br><small class="text-muted">{{ $item->size }} / {{ $item->color }}</small>@endif
                    </span>
                    <span>{{ number_format($item->product->getSellingPrice() * $item->quantity) }} VNĐ</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Tạm tính (chưa voucher):</span>
                    <span>{{ number_format($total) }} VNĐ</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function fillAddress(name, phone, addr) {
    document.querySelector('[name=receiver_name]').value = name;
    document.querySelector('[name=phone]').value = phone;
    document.getElementById('address-field').value = addr;
}

const typeDelivery = document.getElementById('type-delivery');
const typePickup = document.getElementById('type-pickup');
const deliverySection = document.getElementById('delivery-section');
const pickupSection = document.getElementById('pickup-section');
const paymentDelivery = document.getElementById('payment-delivery');
const paymentPickup = document.getElementById('payment-pickup');
const bankQr = document.getElementById('bank-qr-box');
const form = document.getElementById('checkout-form');

function syncCheckoutMode() {
    const isPickup = typePickup.checked;
    deliverySection.classList.toggle('d-none', isPickup);
    pickupSection.classList.toggle('d-none', !isPickup);
    paymentDelivery.classList.toggle('d-none', isPickup);
    paymentPickup.classList.toggle('d-none', !isPickup);

    document.getElementById('address-field').required = !isPickup;
    document.querySelector('[name=receiver_name]').required = !isPickup;
    document.querySelector('[name=phone]').required = !isPickup;
    document.querySelector('[name=pickup_name]').required = isPickup;
    document.querySelector('[name=pickup_phone]').required = isPickup;

    if (isPickup) {
        document.querySelector('[name=receiver_name]').value = document.querySelector('[name=pickup_name]').value;
        document.querySelector('[name=phone]').value = document.querySelector('[name=pickup_phone]').value;
    }
    toggleQr();
}

syncCheckoutMode();

function toggleQr() {
    const isPickup = typePickup.checked;
    let isBank = false;
    if (isPickup) {
        isBank = document.querySelector('[name=payment_method_pickup]:checked')?.value === 'bank';
    } else {
        isBank = document.querySelector('[name=payment_method]:checked')?.value === 'bank';
    }
    bankQr.classList.toggle('d-none', !isBank);
}

typeDelivery.addEventListener('change', syncCheckoutMode);
typePickup.addEventListener('change', syncCheckoutMode);
document.querySelectorAll('[name=payment_method], [name=payment_method_pickup]').forEach(el => {
    el.addEventListener('change', toggleQr);
});

document.querySelector('[name=pickup_name]')?.addEventListener('input', function() {
    if (typePickup.checked) document.querySelector('[name=receiver_name]').value = this.value;
});
document.querySelector('[name=pickup_phone]')?.addEventListener('input', function() {
    if (typePickup.checked) document.querySelector('[name=phone]').value = this.value;
});

form.addEventListener('submit', function(e) {
    if (typePickup.checked) {
        const pm = document.querySelector('[name=payment_method_pickup]:checked');
        if (pm) {
            let hidden = form.querySelector('input[name=payment_method][type=hidden]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'payment_method';
                form.appendChild(hidden);
            }
            hidden.value = pm.value;
        }
        document.querySelector('[name=receiver_name]').value = document.querySelector('[name=pickup_name]').value;
        document.querySelector('[name=phone]').value = document.querySelector('[name=pickup_phone]').value;
    }
});
</script>
@endpush
