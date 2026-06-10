@extends('layouts.app')

@section('title', 'Địa chỉ giao hàng')

@section('content')
<div class="container mt-4">
    <h2>Quản lý địa chỉ giao hàng</h2>
    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card p-3">
                <h5>Thêm địa chỉ mới</h5>
                <form action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <div class="mb-2"><label>Họ tên</label><input type="text" name="receiver_name" class="form-control" required></div>
                    <div class="mb-2"><label>Điện thoại</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="mb-2"><label>Địa chỉ</label><textarea name="address" class="form-control" required></textarea></div>
                    <div class="form-check mb-2"><input type="checkbox" name="is_default" value="1" class="form-check-input"><label class="form-check-label">Đặt làm mặc định</label></div>
                    <button class="btn btn-success">Thêm</button>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            @forelse($addresses as $address)
            <div class="card p-3 mb-2">
                <strong>{{ $address->receiver_name }}</strong> @if($address->is_default)<span class="badge bg-primary">Mặc định</span>@endif
                <p class="mb-1">{{ $address->phone }}</p>
                <p>{{ $address->address }}</p>
                <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                </form>
            </div>
            @empty
            <p class="text-muted">Chưa có địa chỉ nào.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
