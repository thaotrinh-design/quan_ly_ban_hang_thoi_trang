@extends('layouts.admin')

@section('title', 'Sửa mã giảm giá')

@section('content')
<h2>Sửa mã giảm giá</h2>
<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="mt-3" style="max-width:500px">
    @csrf @method('PUT')
    <div class="mb-2"><label>Mã</label><input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required></div>
    <div class="mb-2"><label>Giảm (%)</label><input type="number" name="discount" class="form-control" value="{{ $coupon->discount }}" required></div>
    <div class="mb-2"><label>Từ ngày</label><input type="date" name="start_date" class="form-control" value="{{ $coupon->start_date->format('Y-m-d') }}" required></div>
    <div class="mb-2"><label>Đến ngày</label><input type="date" name="end_date" class="form-control" value="{{ $coupon->end_date->format('Y-m-d') }}" required></div>
    <div class="mb-2"><label>Số lượng</label><input type="number" name="quantity" class="form-control" value="{{ $coupon->quantity }}" required></div>
    <button class="btn btn-success">Cập nhật</button>
</form>
@endsection
