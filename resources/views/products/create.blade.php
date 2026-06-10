@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
<h2>Thêm sản phẩm</h2>
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-3" style="max-width:600px">
    @csrf
    <div class="mb-2"><label>Tên</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-2">
        <label>Danh mục</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2"><label>Giá</label><input type="number" name="price" class="form-control" required></div>
    <div class="mb-2"><label>Tồn kho</label><input type="number" name="stock" class="form-control" required></div>
    <div class="mb-2"><label>Size (cách nhau bởi dấu phẩy)</label><input type="text" name="available_sizes" class="form-control" placeholder="S, M, L, XL" value="S, M, L, XL"></div>
    <div class="mb-2"><label>Màu (cách nhau bởi dấu phẩy)</label><input type="text" name="available_colors" class="form-control" placeholder="Đen, Trắng, Xám" value="Đen, Trắng, Xám, Xanh navy"></div>
    <div class="mb-2"><label>Mô tả</label><textarea name="description" class="form-control"></textarea></div>
    <div class="mb-2"><label>Ảnh</label><input type="file" name="image" class="form-control"></div>
    <div class="form-check mb-3"><input type="checkbox" name="status" value="1" class="form-check-input" checked><label class="form-check-label">Hiển thị</label></div>
    <button class="btn btn-success">Lưu</button>
</form>
@endsection
