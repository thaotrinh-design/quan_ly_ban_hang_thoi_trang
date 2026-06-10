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
        <tr>
            <td>{{ $product->id }}</td>
            <td><img src="{{ $product->image_url }}" width="60"></td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name ?? '' }}</td>
            <td>{{ number_format($product->price) }} VNĐ</td>
            <td>{{ implode(', ', $product->getSizesList()) }}</td>
            <td>{{ implode(', ', $product->getColorsList()) }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
