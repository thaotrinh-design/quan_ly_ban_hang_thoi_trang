<div class="col-md-4 col-sm-6 mb-4">
    <div class="product-card h-100">
        <div class="product-card-img-wrap">
            @if($product->hasDiscount())
            <span class="discount-badge">-{{ $product->discount_percent }}%</span>
            @endif
            <a href="{{ route('shop.show', $product) }}">
                <img src="{{ $product->image_url }}" class="product-card-img" alt="{{ $product->name }}" loading="lazy">
            </a>
        </div>
        <div class="product-card-body">
            <span class="product-cat">{{ $product->category?->name ?? '' }}</span>
            <h6 class="product-name">
                <a href="{{ route('shop.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
            </h6>
            @include('partials.product-price', ['product' => $product])
            <div class="product-actions mt-auto pt-2">
                <a href="{{ route('shop.show', $product) }}" class="btn btn-outline-fashion btn-sm">Chi tiết</a>
                @auth
                @php
                    $cardVariantStocks = [];
                    foreach ($product->variants as $v) {
                        $cardVariantStocks[$v->size . '_' . $v->color] = $v->stock;
                    }
                @endphp
                <button type="button" class="btn btn-outline-fashion btn-sm btn-open-product-modal"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-image="{{ $product->image_url }}"
                    data-sizes='@json($product->getSizesList())'
                    data-colors='@json($product->getColorsList())'
                    data-variants='@json($cardVariantStocks)'
                    data-price="{{ $product->getSellingPrice() }}"
                    data-original="{{ $product->price }}"
                    data-discount="{{ $product->discount_percent }}"
                    data-action="cart">
                    <i class="fa-solid fa-cart-plus me-1"></i> Thêm giỏ
                </button>
                <button type="button" class="btn btn-fashion btn-sm btn-open-product-modal"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-image="{{ $product->image_url }}"
                    data-sizes='@json($product->getSizesList())'
                    data-colors='@json($product->getColorsList())'
                    data-variants='@json($cardVariantStocks)'
                    data-price="{{ $product->getSellingPrice() }}"
                    data-original="{{ $product->price }}"
                    data-discount="{{ $product->discount_percent }}"
                    data-action="buy">
                    <i class="fa-solid fa-bolt me-1"></i> Mua ngay
                </button>
                @else
                <a href="{{ route('login') }}" class="btn btn-fashion btn-sm">Thêm giỏ</a>
                <a href="{{ route('login') }}" class="btn btn-outline-fashion btn-sm">Mua ngay</a>
                @endauth
            </div>
        </div>
    </div>
</div>
