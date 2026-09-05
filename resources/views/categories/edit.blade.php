@extends('layouts.admin')

@section('title', 'Sửa danh mục')

@section('content')
<h2>Sửa danh mục</h2>
<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="mt-3" style="max-width:500px">
    @csrf @method('PUT')
    <div class="mb-2"><label>Tên danh mục</label><input type="text" name="name" class="form-control" value="{{ $category->name }}" required></div>
    <div class="mb-2"><label>Mô tả</label><textarea name="description" class="form-control">{{ $category->description }}</textarea></div>
    <button class="btn btn-primary">Cập nhật</button>
</form>
@endsection
