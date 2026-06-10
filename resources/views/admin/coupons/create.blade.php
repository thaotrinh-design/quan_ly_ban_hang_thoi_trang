@extends('layouts.admin')

@section('title', 'Tạo mã giảm giá')

@section('content')
<h2>Tạo mã giảm giá</h2>
<form action="{{ route('admin.coupons.store') }}" method="POST" class="mt-3" style="max-width:500px">
    @csrf
    <div class="mb-2"><label>Mã</label><input type="text" name="code" class="form-control" required></div>
    <div class="mb-2"><label>Giảm (%)</label><input type="number" name="discount" class="form-control" min="1" max="100" required></div>
    <div class="mb-2"><label>Từ ngày</label><input type="date" name="start_date" class="form-control" required></div>
    <div class="mb-2"><label>Đến ngày</label><input type="date" name="end_date" class="form-control" required></div>
    <div class="mb-2"><label>Số lượng</label><input type="number" name="quantity" class="form-control" min="1" required></div>
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
@endsection
