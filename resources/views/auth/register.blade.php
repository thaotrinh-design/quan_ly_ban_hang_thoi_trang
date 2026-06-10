<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - {{ config('store.name') }}</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
            overflow:hidden;
        }

        /* LEFT */

        .left{
            width:55%;
            background:url('https://images.unsplash.com/photo-1483985988355-763728e1935b')
            center center/cover;
            position:relative;
        }

        .overlay{
            position:absolute;
            inset:0;
            background:rgba(0,0,0,.45);

            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;

            color:white;
            text-align:center;
            padding:40px;
        }

        .overlay h1{
            font-size:60px;
            margin-bottom:15px;
        }

        .overlay p{
            font-size:22px;
        }

        /* RIGHT */

        .right{
            width:45%;
            background:white;

            display:flex;
            justify-content:center;
            align-items:center;
        }

        .register-box{
            width:430px;
        }

        .logo{
            text-align:center;
            font-size:28px;
            font-weight:bold;
            margin-bottom:10px;
        }

        .title{
            text-align:center;
            margin-bottom:25px;
            color:#666;
        }

        .form-group{
            margin-bottom:15px;
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
            color:#888;
        }

        .input-box input{
            width:100%;
            padding:14px 14px 14px 45px;
            border:1px solid #ddd;
            border-radius:8px;
        }

        .error{
            color:red;
            font-size:14px;
            margin-top:5px;
        }

        .btn-register{
            width:100%;
            padding:14px;
            border:none;
            border-radius:8px;
            background:black;
            color:white;
            font-size:16px;
            cursor:pointer;
            margin-top:10px;
        }

        .btn-register:hover{
            background:#222;
        }

        .login-link{
            text-align:center;
            margin-top:20px;
        }

        .login-link a{
            text-decoration:none;
            font-weight:bold;
            color:black;
        }

        .benefits{
            margin-top:25px;
            font-size:14px;
            color:#666;
        }

        .benefits p{
            margin-bottom:8px;
        }

    </style>

</head>
<body>

<!-- LEFT -->

<div class="left">

    <div class="overlay">

        <h1>{{ config('store.name') }}</h1>

        <p>
            Đăng ký để khám phá hàng nghìn sản phẩm thời trang mới nhất
        </p>

    </div>

</div>

<!-- RIGHT -->

<div class="right">

    <div class="register-box">

        <div class="logo">
            {{ config('store.name') }}
        </div>

        <div class="title">
            Tạo tài khoản mua sắm
        </div>

        <form method="POST" action="/register">

            @csrf

            <div class="form-group">

                <label>Họ và tên</label>

                <div class="input-box">
                    <i class="fa-solid fa-user"></i>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Nguyễn Văn A">
                </div>

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <div class="form-group">

                <label>Email</label>

                <div class="input-box">
                    <i class="fa-solid fa-envelope"></i>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="example@gmail.com">
                </div>

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <div class="form-group">

                <label>Số điện thoại</label>

                <div class="input-box">
                    <i class="fa-solid fa-phone"></i>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="09xxxxxxxx">
                </div>

                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <div class="form-group">

                <label>Mật khẩu</label>

                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password"
                        placeholder="Nhập mật khẩu">
                </div>

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <div class="form-group">

                <label>Xác nhận mật khẩu</label>

                <div class="input-box">
                    <i class="fa-solid fa-key"></i>

                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Nhập lại mật khẩu">
                </div>

            </div>

            <button class="btn-register">
                Đăng ký tài khoản
            </button>

        </form>

        <div class="login-link">

            Đã có tài khoản?

            <a href="/login">
                Đăng nhập ngay
            </a>

        </div>

        <div class="benefits">

            <p>✓ Nhận ưu đãi thành viên</p>

            <p>✓ Theo dõi đơn hàng dễ dàng</p>

            <p>✓ Lưu sản phẩm yêu thích</p>

            <p>✓ Nhận mã giảm giá độc quyền</p>

        </div>

    </div>

</div>

</body>
</html>