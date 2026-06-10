@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container mt-4">
    <h2>Sản phẩm yêu thích</h2>
    <div class="row mt-3">
        @forelse($wishlists as $wishlist)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="{{ $wishlist->product->image_url }}" class="card-img-top product-img">
                <div class="card-body">
                    <h6>{{ $wishlist->product->name }}</h6>
                    <p class="text-danger">{{ number_format($wishlist->product->price) }} VNĐ</p>
                    <a href="{{ route('shop.show', $wishlist->product) }}" class="btn btn-sm btn-outline-dark">Xem</a>
                    <form action="{{ route('wishlist.destroy', $wishlist->product_id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted">Danh sách yêu thích trống.</p>
        @endforelse
    </div>
</div>
@endsection
