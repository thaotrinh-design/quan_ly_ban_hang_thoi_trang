@extends('layouts.app')

@section('title', $title ?? 'Cửa hàng')

@section('content')
<div class="shop-hero {{ 'shop-hero--' . ($activeTab ?? 'collection') }}">
    <div class="container">
        <h1 class="shop-hero-title">{{ $title ?? 'Cửa hàng thời trang' }}</h1>
        <p class="shop-hero-sub">{{ $subtitle ?? 'Khám phá các sản phẩm mới nhất và phù hợp với phong cách của bạn' }}</p>
        <div class="shop-tabs">
            <a href="{{ route('shop.sale') }}" class="shop-tab {{ ($activeTab ?? '') === 'sale' ? 'active' : '' }}">Sale</a>
            <a href="{{ route('shop.collection') }}" class="shop-tab {{ ($activeTab ?? 'collection') === 'collection' ? 'active' : '' }}">Bộ sưu tập</a>
            <a href="{{ route('shop.shirts') }}" class="shop-tab {{ ($activeTab ?? '') === 'shirts' ? 'active' : '' }}">Áo</a>
            <a href="{{ route('shop.pants') }}" class="shop-tab {{ ($activeTab ?? '') === 'pants' ? 'active' : '' }}">Quần</a>
            <a href="{{ route('shop.accessories') }}" class="shop-tab {{ ($activeTab ?? '') === 'accessories' ? 'active' : '' }}">Phụ kiện</a>
        </div>
    </div>
</div>

<div class="container shop-content pb-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            @include('shop._sidebar', ['filterAction' => ($featuredOnly ?? false) ? route('shop.featured') : route('shop.index')])
        </div>
        <div class="col-lg-9">
            <div class="row">
                @forelse($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                <div class="col-12"><div class="empty-state">Không tìm thấy sản phẩm phù hợp.</div></div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@include('partials.add-to-cart-modal')
@endsection
