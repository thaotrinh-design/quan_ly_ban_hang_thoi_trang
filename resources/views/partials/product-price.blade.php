<div class="product-price">
    @if($product->hasDiscount())
        <span class="price-original">{{ number_format($product->price) }}đ</span>
        <span class="price-sale">{{ number_format($product->getSellingPrice()) }}đ</span>
    @else
        <span class="price-sale">{{ number_format($product->price) }}đ</span>
    @endif
</div>
