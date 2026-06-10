<!DOCTYPE html>
<html>
<head>
    <title>Đặt lại mật khẩu</title>
</head>
<body>

<h2>Đặt lại mật khẩu</h2>

<form method="POST"
      action="/reset-password/{{ $user->id }}">

    @csrf

    <label>Mật khẩu mới</label>

    <input
        type="password"
        name="password"
    >

    <br><br>

    <label>Xác nhận mật khẩu</label>

    <input
        type="password"
        name="password_confirmation"
    >

    <br><br>

    <button type="submit">
        Cập nhật mật khẩu
    </button>

</form>

</body>
</html>