@extends('layouts.admin')

@section('title', 'Mã giảm giá')

@section('content')
<h2>Quản lý mã giảm giá</h2>
<a href="{{ route('admin.coupons.create') }}" class="btn btn-success mb-3">Tạo mã mới</a>
<table class="table table-bordered">
    <thead><tr><th>Mã</th><th>Giảm (%)</th><th>Từ</th><th>Đến</th><th>SL còn</th><th></th></tr></thead>
    <tbody>
        @foreach($coupons as $coupon)
        <tr>
            <td>{{ $coupon->code }}</td>
            <td>{{ $coupon->discount }}%</td>
            <td>{{ $coupon->start_date->format('d/m/Y') }}</td>
            <td>{{ $coupon->end_date->format('d/m/Y') }}</td>
            <td>{{ $coupon->quantity }}</td>
            <td>
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">{{ $coupons->links() }}</div>
@endsection
