@extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
<h2>Thêm danh mục</h2>
<form action="{{ route('admin.categories.store') }}" method="POST" class="mt-3" style="max-width:500px">
    @csrf
    <div class="mb-2"><label>Tên danh mục</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-2"><label>Mô tả</label><textarea name="description" class="form-control"></textarea></div>
    <button class="btn btn-success">Lưu</button>
</form>
@endsection
