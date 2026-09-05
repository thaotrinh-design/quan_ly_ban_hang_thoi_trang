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
            @include('shop._sidebar', ['filterAction' => ($activeTab ?? '') === 'featured' ? route('shop.featured') : route('shop.index')])
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsDiv = document.getElementById('search-suggestions');
    let debounceTimer;

    if (!searchInput || !suggestionsDiv) return;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const keyword = this.value.trim();

        if (keyword.length < 2) {
            suggestionsDiv.classList.add('d-none');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('shop.index') }}?ajax_search=1&keyword=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        suggestionsDiv.classList.add('d-none');
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        if (item.type === 'category') {
                            html += `<a href="{{ route('shop.index') }}?keyword=${encodeURIComponent(item.name)}" class="suggestion-item suggestion-category">
                                <i class="fa-solid fa-folder me-2"></i>${item.name}
                            </a>`;
                        } else {
                            const discountBadge = item.original_price > item.price ? `<span class="badge bg-danger ms-2">-${Math.round((1 - item.price/item.original_price) * 100)}%</span>` : '';
                            html += `<a href="{{ route('shop.index') }}?keyword=${encodeURIComponent(item.name)}" class="suggestion-item">
                                <img src="${item.image}" class="suggestion-img" onerror="this.style.display='none'">
                                <div class="suggestion-info">
                                    <div class="suggestion-name">${item.name}${discountBadge}</div>
                                    <div class="suggestion-price">${item.price.toLocaleString('vi-VN')}đ</div>
                                </div>
                            </a>`;
                        }
                    });

                    suggestionsDiv.innerHTML = html;
                    suggestionsDiv.classList.remove('d-none');
                })
                .catch(() => suggestionsDiv.classList.add('d-none'));
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.classList.add('d-none');
        }
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && suggestionsDiv.innerHTML) {
            suggestionsDiv.classList.remove('d-none');
        }
    });
});
</script>
@endpush
@endsection
