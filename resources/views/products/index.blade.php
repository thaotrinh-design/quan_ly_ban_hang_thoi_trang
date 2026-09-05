@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<h2>Quản lý sản phẩm</h2>
<a href="{{ route('admin.products.create') }}" class="btn btn-success mb-3">Thêm sản phẩm</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th><th>Ảnh</th><th>Tên</th><th>Danh mục</th><th>Giá</th>
            <th>Size có sẵn</th><th>Màu có sẵn</th><th>Tồn kho</th><th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr class="{{ $product->trashed() ? 'table-danger' : '' }}">
            <td>{{ $product->id }}</td>
            <td><img src="{{ $product->image_url }}" width="60"></td>
            <td>
                {{ $product->name }}
                @if($product->trashed())
                    <span class="badge bg-danger">Đã xóa</span>
                @endif
            </td>
            <td>{{ $product->category->name ?? '' }}</td>
            <td>{{ number_format($product->price) }} VNĐ</td>
            <td>{{ implode(', ', $product->getSizesList()) }}</td>
            <td>{{ implode(', ', $product->getColorsList()) }}</td>
            <td>
                {{ $product->stock }}
                @if($product->variants->count() > 0)
                    <small class="text-muted d-block">
                        @foreach($product->variants->take(3) as $v)
                            {{ $v->size }}/{{ $v->color }}:{{ $v->stock }}@if(!$loop->last), @endif
                        @endforeach
                        @if($product->variants->count() > 3)...@endif
                    </small>
                @endif
            </td>
            <td>
                @if($product->trashed())
                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Khôi phục</button>
                    </form>
                    <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa vĩnh viễn? Sản phẩm này không thể khôi phục.')">Xóa vĩnh viễn</button>
                    </form>
                @else
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
