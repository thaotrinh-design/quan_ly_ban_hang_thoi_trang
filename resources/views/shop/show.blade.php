@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4 mb-4">
    <div class="row g-4">
        <div class="col-md-5">
            <div class="product-detail-img-wrap">
                @if($product->hasDiscount())
                <span class="discount-badge">-{{ $product->discount_percent }}%</span>
                @endif
                <img src="{{ $product->image_url }}" class="product-detail-img" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-md-7">
            <span class="product-cat">{{ $product->category->name ?? '' }}</span>
            <h2 class="mt-2 mb-3">{{ $product->name }}</h2>
            @include('partials.product-price', ['product' => $product])
            <p class="text-muted mt-2"><i class="fa-solid fa-box me-1"></i> Còn {{ $product->stock }} sản phẩm</p>
            <p class="product-desc">{{ $product->description }}</p>

            @if($product->variants->count() > 0)
            <p class="small text-muted mb-1">Tồn kho theo size/màu:</p>
            <div class="mb-3">
                @foreach($product->variants as $variant)
                <span class="badge {{ $variant->inStock() ? 'bg-success' : 'bg-secondary' }} me-1 mb-1">
                    {{ $variant->size }}/{{ $variant->color }}: {{ $variant->stock }}
                </span>
                @endforeach
            </div>
            @endif

            <div class="d-flex gap-2 flex-wrap mt-3">
                @auth
                @php
                    $variantStocks = $product->variants->pluck('stock', 'size_color')->toArray();
                @endphp
                <button type="button" class="btn btn-outline-fashion btn-lg btn-open-product-modal"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-image="{{ $product->image_url }}"
                    data-sizes='@json($product->getSizesList())'
                    data-colors='@json($product->getColorsList())'
                    data-variants='@json($variantStocks)'
                    data-price="{{ $product->getSellingPrice() }}"
                    data-original="{{ $product->price }}"
                    data-discount="{{ $product->discount_percent }}"
                    data-action="cart">
                    <i class="fa-solid fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                </button>
                <button type="button" class="btn btn-fashion btn-lg btn-open-product-modal"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-image="{{ $product->image_url }}"
                    data-sizes='@json($product->getSizesList())'
                    data-colors='@json($product->getColorsList())'
                    data-variants='@json($variantStocks)'
                    data-price="{{ $product->getSellingPrice() }}"
                    data-original="{{ $product->price }}"
                    data-discount="{{ $product->discount_percent }}"
                    data-action="buy">
                    <i class="fa-solid fa-bolt me-2"></i>Mua ngay
                </button>
                @if($inWishlist)
                <form action="{{ route('wishlist.destroy', $product->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-fashion btn-lg">Bỏ yêu thích</button>
                </form>
                @else
                <form action="{{ route('wishlist.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button class="btn btn-outline-fashion btn-lg"><i class="fa-regular fa-heart me-1"></i>Yêu thích</button>
                </form>
                @endif
                @else
                <a href="{{ route('login') }}" class="btn btn-fashion btn-lg">Đăng nhập để mua</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-8">
            <h4 class="section-title">Đánh giá từ khách đã mua</h4>
            @auth
                @if($canReview)
                <form action="{{ route('reviews.store') }}" method="POST" class="review-form mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <p class="small text-success"><i class="fa-solid fa-check-circle"></i> Bạn đã mua — có thể đánh giá</p>
                    <select name="star" class="form-select w-auto mb-2">
                        @for($i=5; $i>=1; $i--)<option value="{{ $i }}">{{ $i }} sao</option>@endfor
                    </select>
                    <textarea name="comment" class="form-control mb-2" placeholder="Nhận xét của bạn"></textarea>
                    <button class="btn btn-fashion btn-sm">Gửi đánh giá</button>
                </form>
                @else
                <div class="alert alert-light border small">Chỉ khách đã mua mới được đánh giá. Bạn có thể xem nhận xét bên dưới.</div>
                @endif
            @endauth
            @forelse($product->reviews as $review)
            <div class="review-item">
                <strong>{{ $review->user->name ?? 'Khách' }}</strong>
                <span class="badge bg-fashion-soft ms-1">Đã mua</span>
                <span class="text-warning">{{ str_repeat('★', $review->star) }}</span>
                <p class="mb-0 mt-1">{{ $review->comment }}</p>
            </div>
            @empty
            <p class="text-muted">Chưa có đánh giá.</p>
            @endforelse
        </div>
    </div>

    @if($relatedProducts->count())
    <div class="mt-5">
        <h4 class="section-title">Có thể bạn thích</h4>
        <div class="row">
            @foreach($relatedProducts as $related)
                @include('partials.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif
</div>

@include('partials.add-to-cart-modal')
@endsection
