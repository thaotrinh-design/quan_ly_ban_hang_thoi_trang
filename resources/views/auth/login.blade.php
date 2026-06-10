<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('store.name') }} Login</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            background:#f5f5f5;
        }

        .left{
            width:55%;
            background:url('https://images.unsplash.com/photo-1441986300917-64674bd600d8')
            center center/cover;
            position:relative;
        }

        .overlay{
            position:absolute;
            width:100%;
            height:100%;
            background:rgba(0,0,0,.45);
            display:flex;
            justify-content:center;
            align-items:center;
            color:white;
            flex-direction:column;
        }

        .overlay h1{
            font-size:55px;
            margin-bottom:15px;
        }

        .overlay p{
            font-size:20px;
        }

        .right{
            width:45%;
            display:flex;
            justify-content:center;
            align-items:center;
            background:white;
        }

        .login-box{
            width:420px;
        }

        .login-box h2{
            margin-bottom:10px;
            color:#222;
        }

        .login-box p{
            color:#777;
            margin-bottom:25px;
        }

        .form-group{
            margin-bottom:18px;
        }

        .form-group label{
            display:block;
            margin-bottom:6px;
            font-weight:600;
        }

        .input-box{
            position:relative;
        }

        .input-box i{
            position:absolute;
            left:15px;
            top:15px;
            color:#999;
        }

        .input-box input{
            width:100%;
            padding:14px 14px 14px 45px;
            border:1px solid #ddd;
            border-radius:8px;
        }

        .remember{
            display:flex;
            justify-content:space-between;
            margin-bottom:20px;
        }

        .remember a{
            text-decoration:none;
            color:#000;
        }

        .btn-login{
            width:100%;
            padding:14px;
            border:none;
            border-radius:8px;
            background:#000;
            color:white;
            font-size:16px;
            cursor:pointer;
        }

        .btn-login:hover{
            background:#222;
        }

        .register{
            margin-top:20px;
            text-align:center;
        }

        .register a{
            color:#000;
            font-weight:bold;
            text-decoration:none;
        }

        .error{
            color:red;
            margin-top:5px;
        }

    </style>

</head>
<body>

<div class="left">
    <div class="overlay">
        <h1>{{ config('store.name') }}</h1>
        <p>Thời trang hiện đại - Phong cách riêng của bạn</p>
    </div>
</div>

<div class="right">

    <div class="login-box">

        <h2>Đăng nhập</h2>
        <p>Chào mừng bạn quay trở lại</p>

        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login">

            @csrf

            <div class="form-group">
                <label>Email</label>

                <div class="input-box">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email"
                           name="email"
                           placeholder="Nhập email">
                </div>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>

                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password"
                           name="password"
                           placeholder="Nhập mật khẩu">
                </div>
            </div>

            <div class="remember">
                <label>
                    <input type="checkbox">
                    Ghi nhớ đăng nhập
                </label>

                <a href="{{ route('forgot.password') }}">
                    Quên mật khẩu?
                </a>
            </div>

            <button class="btn-login">
                Đăng nhập
            </button>

        </form>

        <div class="register">
            Chưa có tài khoản?
            <a href="/register">
                Đăng ký ngay
            </a>
        </div>

    </div>

</div>

</body>
</html>