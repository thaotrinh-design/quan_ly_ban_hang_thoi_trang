<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ</title>
</head>
<body>

    <h1>Đăng nhập thành công</h1>

    <h3>Xin chào {{ Auth::user()->name }}</h3>

    <a href="{{ route('logout') }}"
       onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
       Đăng xuất
    </a>

    <form id="logout-form"
          action="{{ route('logout') }}"
          method="POST"
          style="display:none">
        @csrf
    </form>

</body>
</html>