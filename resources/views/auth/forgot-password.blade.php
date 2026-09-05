<!DOCTYPE html>
<html>
<head>
    <title>Quên mật khẩu</title>
</head>
<body>

<h2>Quên mật khẩu</h2>

@if(session('error'))
    <p style="color:red">
        {{ session('error') }}
    </p>
@endif

<form method="POST" action="{{ route('forgot.password') }}">

    @csrf

    <label>Email hoặc Số điện thoại</label>

    <input
        type="text"
        name="account"
        placeholder="Nhập Email hoặc SĐT"
    >

    <br><br>

    <button type="submit">
        Tiếp tục
    </button>

</form>

</body>
</html>