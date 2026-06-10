@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<section class="home-hero">
    <div class="container text-center">
        <p class="hero-tag">Bộ sưu tập 2026</p>
        <h1 class="hero-title">Thời trang<br><em>dịu nhẹ</em> mỗi ngày</h1>
        <p class="hero-desc">Khám phá phong cách thanh lịch — từ áo thun basic đến váy dự tiệc, giá tốt & ưu đãi đến 40%</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('shop.index') }}" class="btn btn-fashion btn-lg">Tất cả sản phẩm</a>
            <a href="{{ route('shop.featured') }}" class="btn btn-outline-fashion btn-lg">Sản phẩm nổi bật</a>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="section-head text-center mb-4">
        <h2>Danh mục nổi bật</h2>
        <p>Chọn phong cách của bạn</p>
    </div>
    <div class="row g-3 mb-5">
        @foreach($categories->take(8) as $category)
        <div class="col-6 col-md-3">
            <a href="{{ route('shop.category', $category) }}" class="category-pill text-decoration-none">
                <span>{{ $category->name }}</span>
            </a>
        </div>
        @endforeach
    </div>

    <div class="section-head d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2>Gợi ý hôm nay</h2>
            <p class="mb-0 text-muted">Sản phẩm nổi bật & đang giảm giá</p>
        </div>
        <a href="{{ route('shop.featured') }}" class="text-fashion fw-semibold">Xem tất cả →</a>
    </div>
    <div class="row">
        @foreach($featuredProducts as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</div>

@include('partials.add-to-cart-modal')
@endsection
