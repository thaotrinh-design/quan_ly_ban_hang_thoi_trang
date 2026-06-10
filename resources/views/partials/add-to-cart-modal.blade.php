<div class="modal fade" id="addToCartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Thêm vào giỏ hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="d-flex gap-3 mb-3">
                    <img id="modal-product-image" src="" width="90" height="110" class="rounded object-fit-cover">
                    <div>
                        <h6 id="modal-product-name" class="mb-1"></h6>
                        <div id="modal-product-price"></div>
                    </div>
                </div>
                <form action="{{ route('cart.add') }}" method="POST" id="modal-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" id="modal-product-id">
                    <input type="hidden" name="buy_now" id="modal-buy-now" value="0">
                    <div class="mb-3">
                        <label class="form-label">Chọn Size <span class="text-danger">*</span></label>
                        <select name="size" id="modal-size" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chọn Màu <span class="text-danger">*</span></label>
                        <select name="color" id="modal-color" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width:120px">
                    </div>
                    <button type="submit" id="modal-confirm-btn" class="btn btn-fashion w-100 py-2">
                        <i class="fa-solid fa-bag-shopping me-2"></i>Xác nhận
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addToCartModal');
    if (!modal) return;
    const bsModal = new bootstrap.Modal(modal);

    document.querySelectorAll('.btn-open-product-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            const sizes = JSON.parse(this.dataset.sizes || '[]');
            const colors = JSON.parse(this.dataset.colors || '[]');
            const buyNow = this.dataset.action === 'buy';
            document.getElementById('modal-product-id').value = this.dataset.id;
            document.getElementById('modal-product-name').textContent = this.dataset.name;
            document.getElementById('modal-product-image').src = this.dataset.image;
            document.getElementById('modal-buy-now').value = buyNow ? '1' : '0';

            const discount = parseInt(this.dataset.discount || '0');
            const price = parseInt(this.dataset.price);
            const original = parseInt(this.dataset.original);
            let priceHtml = '';
            if (discount > 0) {
                priceHtml = '<span class="price-original d-block">' + original.toLocaleString('vi-VN') + 'đ</span><span class="price-sale">' + price.toLocaleString('vi-VN') + 'đ</span>';
            } else {
                priceHtml = '<span class="price-sale">' + price.toLocaleString('vi-VN') + 'đ</span>';
            }
            document.getElementById('modal-product-price').innerHTML = priceHtml;

            const sizeSel = document.getElementById('modal-size');
            const colorSel = document.getElementById('modal-color');
            sizeSel.innerHTML = '<option value="">-- Chọn size --</option>' + sizes.map(s => '<option value="'+s+'">'+s+'</option>').join('');
            colorSel.innerHTML = '<option value="">-- Chọn màu --</option>' + colors.map(c => '<option value="'+c+'">'+c+'</option>').join('');

            document.getElementById('modal-confirm-btn').innerHTML = buyNow
                ? '<i class="fa-solid fa-bolt me-2"></i>Mua ngay'
                : '<i class="fa-solid fa-bag-shopping me-2"></i>Xác nhận thêm giỏ';

            bsModal.show();
        });
    });
});
</script>
@endpush
