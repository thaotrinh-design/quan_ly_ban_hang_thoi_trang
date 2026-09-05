@extends('layouts.admin')

@section('title', 'Báo cáo')

@section('content')
<h2>Báo cáo & Thống kê</h2>
<form class="row g-2 mt-2 mb-4">
    <div class="col-auto"><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
    <div class="col-auto"><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
    <div class="col-auto"><button class="btn btn-dark">Lọc</button></div>
    <div class="col-auto"><a href="{{ route('admin.reports.export', ['from'=>$from,'to'=>$to]) }}" class="btn btn-success">Xuất CSV</a></div>
</form>
<div class="card p-3 mb-4">
    <h4>Tổng doanh thu: {{ number_format($totalRevenue) }} VNĐ</h4>
</div>
<h5>Doanh thu theo ngày</h5>
<table class="table table-bordered">
    <thead><tr><th>Ngày</th><th>Doanh thu</th></tr></thead>
    <tbody>
        @forelse($revenueByDay as $row)
        <tr><td>{{ $row->date }}</td><td>{{ number_format($row->revenue) }} VNĐ</td></tr>
        @empty
        <tr><td colspan="2" class="text-muted">Không có dữ liệu</td></tr>
        @endforelse
    </tbody>
</table>
<h5 class="mt-4">Sản phẩm bán chạy</h5>
<table class="table table-bordered">
    <thead><tr><th>Sản phẩm</th><th>Đã bán</th><th>Doanh thu</th></tr></thead>
    <tbody>
        @forelse($bestSelling as $item)
        <tr>
            <td>{{ $item->product?->name ?? 'N/A' }}</td>
            <td>{{ $item->total_sold }}</td>
            <td>{{ number_format($item->revenue) }} VNĐ</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-muted">Không có dữ liệu</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
