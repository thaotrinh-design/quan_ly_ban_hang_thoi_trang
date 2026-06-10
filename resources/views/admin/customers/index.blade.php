@extends('layouts.admin')

@section('title', 'Khách hàng')

@section('content')
<h2>Quản lý khách hàng</h2>
<form class="mb-3">
    <input type="text" name="keyword" class="form-control d-inline-block w-auto" placeholder="Tìm kiếm..." value="{{ request('keyword') }}">
    <button class="btn btn-dark">Tìm</button>
</form>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Tên</th><th>Email</th><th>Điện thoại</th><th>Trạng thái</th><th></th></tr></thead>
    <tbody>
        @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td><span class="badge {{ $customer->status=='active'?'bg-success':'bg-danger' }}">{{ $customer->status }}</span></td>
            <td>
                <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-warning">{{ $customer->status=='active'?'Khóa':'Mở khóa' }}</button>
                </form>
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">{{ $customers->links() }}</div>
@endsection
