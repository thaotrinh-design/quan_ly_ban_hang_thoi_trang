@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<h2>Cập nhật sản phẩm</h2>
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mt-3" style="max-width:600px">
    @csrf @method('PUT')
    <div class="mb-2"><label>Tên</label><input type="text" name="name" class="form-control" value="{{ $product->name }}" required></div>
    <div class="mb-2">
        <label>Danh mục</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $product->category_id==$category->id?'selected':'' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2"><label>Giá</label><input type="number" name="price" class="form-control" value="{{ $product->price }}" required></div>
    <div class="mb-2"><label>Tồn kho</label><input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required></div>
    <div class="mb-2"><label>Size (cách nhau bởi dấu phẩy)</label><input type="text" name="available_sizes" class="form-control" value="{{ implode(', ', $product->getSizesList()) }}"></div>
    <div class="mb-2"><label>Màu (cách nhau bởi dấu phẩy)</label><input type="text" name="available_colors" class="form-control" value="{{ implode(', ', $product->getColorsList()) }}"></div>
    <div class="mb-2"><label>Mô tả</label><textarea name="description" class="form-control">{{ $product->description }}</textarea></div>
    <div class="mb-2"><img src="{{ $product->image_url }}" width="100" class="mb-2"><input type="file" name="image" class="form-control"></div>
    <div class="form-check mb-3"><input type="checkbox" name="status" value="1" class="form-check-input" {{ $product->status?'checked':'' }}><label class="form-check-label">Hiển thị</label></div>
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
@endsection
