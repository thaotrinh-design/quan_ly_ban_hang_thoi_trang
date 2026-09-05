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
                    <div id="modal-stock-info" class="mb-3 d-none">
                        <span class="badge bg-success" id="modal-stock-badge"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" name="quantity" id="modal-quantity" value="1" min="1" class="form-control" style="max-width:120px">
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

    // Store variant stock data
    let variantStocks = {};

    document.querySelectorAll('.btn-open-product-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            const sizes = JSON.parse(this.dataset.sizes || '[]');
            const colors = JSON.parse(this.dataset.colors || '[]');
            let variants = {};
            try {
                variants = JSON.parse(this.dataset.variants || '{}');
            } catch (e) {
                console.error('Variant JSON parse error:', e, this.dataset.variants);
            }
            const buyNow = this.dataset.action === 'buy';

            variantStocks = variants;

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

            // Reset stock info
            document.getElementById('modal-stock-info').classList.add('d-none');
            document.getElementById('modal-quantity').value = 1;

            document.getElementById('modal-confirm-btn').innerHTML = buyNow
                ? '<i class="fa-solid fa-bolt me-2"></i>Mua ngay'
                : '<i class="fa-solid fa-bag-shopping me-2"></i>Xác nhận thêm giỏ';

            bsModal.show();
        });
    });

    // Update stock info when size/color changes
    function updateStockInfo() {
        const size = document.getElementById('modal-size').value;
        const color = document.getElementById('modal-color').value;
        const stockInfo = document.getElementById('modal-stock-info');
        const stockBadge = document.getElementById('modal-stock-badge');
        const quantityInput = document.getElementById('modal-quantity');

        if (size && color) {
            const key = size + '_' + color;
            const stock = variantStocks[key] || 0;

            stockInfo.classList.remove('d-none');
            if (stock > 0) {
                stockBadge.className = 'badge bg-success';
                stockBadge.textContent = 'Còn ' + stock + ' sản phẩm';
                quantityInput.max = stock;
                quantityInput.value = Math.min(parseInt(quantityInput.value) || 1, stock);
            } else {
                stockBadge.className = 'badge bg-danger';
                stockBadge.textContent = 'Hết hàng';
                quantityInput.value = 0;
            }
        } else {
            stockInfo.classList.add('d-none');
        }
    }

    document.getElementById('modal-size').addEventListener('change', updateStockInfo);
    document.getElementById('modal-color').addEventListener('change', updateStockInfo);
});
</script>
@endpush
