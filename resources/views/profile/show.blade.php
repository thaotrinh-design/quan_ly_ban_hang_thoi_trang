@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container mt-4">
    <h2>Thông tin cá nhân</h2>
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card p-4">
                <h5>Cập nhật thông tin</h5>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label>Họ tên</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="mb-2">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
                    </div>
                    <button class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4">
                <h5>Đổi mật khẩu</h5>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-dark">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
