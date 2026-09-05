@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mt-4 mb-4">
    <h2>Giỏ hàng của bạn</h2>
    @if($cart->items->isEmpty())
        <p class="text-muted mt-3">Giỏ hàng trống. <a href="{{ route('shop.index') }}">Tiếp tục mua sắm</a></p>
    @else
    <form id="checkout-selected-form" action="{{ route('checkout.select') }}" method="POST" class="mb-3">
        @csrf
        <button type="button" class="btn btn-outline-secondary btn-sm me-2" id="select-all-cart">Chọn tất cả</button>
        <button type="submit" class="btn btn-success">Thanh toán đã chọn</button>
    </form>
    <table class="table table-bordered mt-3">
        <thead>
            <tr><th style="width:44px"></th><th>Sản phẩm</th><th>Size / Màu</th><th>Giá</th><th>SL</th><th>Thành tiền</th><th></th></tr>
        </thead>
        <tbody>
            @foreach($cart->items as $item)
            <tr>
                <td class="align-middle text-center">
                    <input type="checkbox" class="cart-item-check form-check-input" value="{{ $item->id }}" checked>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $item->product->image_url }}" width="60" class="me-2">
                        {{ $item->product->name }}
                    </div>
                </td>
                <td>{{ $item->size }} / {{ $item->color }}</td>
                <td>{{ number_format($item->product->getSellingPrice()) }} VNĐ</td>
                <td>
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                        @csrf @method('PUT')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="{{ $item->product->stock }}" class="form-control form-control-sm" style="width:70px">
                        <button class="btn btn-sm btn-secondary ms-1">OK</button>
                    </form>
                    @if($item->quantity > $item->product->stock)
                        <small class="text-danger">Chỉ còn {{ $item->product->stock }} sản phẩm</small>
                    @elseif($item->product->stock <= 5)
                        <small class="text-warning">Sắp hết hàng (còn {{ $item->product->stock }})</small>
                    @endif
                </td>
                <td>{{ number_format($item->product->getSellingPrice() * $item->quantity) }} VNĐ</td>
                <td>
                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr><th colspan="5" class="text-end">Tổng cộng</th><th colspan="2">{{ number_format($total) }} VNĐ</th></tr>
        </tfoot>
    </table>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('checkout-selected-form');
    if (!form) return;

    const selectAllBtn = document.getElementById('select-all-cart');
    const checkboxes = Array.from(document.querySelectorAll('.cart-item-check'));

    selectAllBtn?.addEventListener('click', function () {
        const allChecked = checkboxes.every(box => box.checked);
        checkboxes.forEach(box => box.checked = !allChecked);
        this.textContent = allChecked ? 'Chọn tất cả' : 'Bỏ chọn tất cả';
    });

    form.addEventListener('submit', function (e) {
        const selected = checkboxes.filter(box => box.checked).map(box => box.value);
        if (!selected.length) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
            return;
        }

        form.querySelectorAll('input[name="item_ids[]"]').forEach(input => input.remove());
        selected.forEach(id => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'item_ids[]';
            hidden.value = id;
            form.appendChild(hidden);
        });
    });
});
</script>
@endpush
