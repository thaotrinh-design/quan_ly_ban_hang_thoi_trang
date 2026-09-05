<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt lại mật khẩu - TINA STORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .reset-card { max-width: 420px; width: 100%; }
    </style>
</head>
<body>
<div class="reset-card">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="text-center mb-4"><i class="fa-solid fa-lock"></i> Đặt lại mật khẩu</h4>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reset.password', ['id' => $user->id, 'token' => request()->query('token'), 'expires' => request()->query('expires')]) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password" class="form-control" required minlength="8" autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-dark w-100">Cập nhật mật khẩu</button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Quay lại đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
