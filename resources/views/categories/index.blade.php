@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<h2>Quản lý danh mục</h2>
<a href="{{ route('admin.categories.create') }}" class="btn btn-success mb-3">Thêm danh mục</a>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Tên</th><th>Mô tả</th><th></th></tr></thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->description }}</td>
            <td>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
