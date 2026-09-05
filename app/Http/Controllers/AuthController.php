<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLogin()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email.required' => 'Không được bỏ trống Email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Không được bỏ trống mật khẩu'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // Kiểm tra tài khoản bị khóa
            if ($user->status == 'locked') {
                Auth::logout();

                return back()->with(
                    'error',
                    'Tài khoản đã bị khóa.'
                );
            }

            $request->session()->regenerate();

            return redirect()->intended($user->isAdmin() ? route('admin.dashboard') : route('home'))
                ->with('success', 'Đăng nhập thành công!');
        }

        return back()->with(
            'error',
            'Email hoặc mật khẩu không đúng.'
        );
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Hien thi form quen mat khau
    public function showForgotPassword()
    {
    return view('auth.forgot-password');
    }

    //Xu ly tim tai khoan
    public function forgotPassword(Request $request)
    {
    $request->validate([
        'account' => 'required'
    ]);

    $user = User::where('email', $request->account)
                ->orWhere('phone', $request->account)
                ->first();

    if (!$user) {
        return back()->with(
            'error',
            'Không tìm thấy tài khoản.'
        );
    }

    // Tạo token expire trong 15 phút
    $token = hash_hmac('sha256', $user->id . '|' . $user->email . '|' . now()->addMinutes(15)->timestamp, env('APP_KEY'));
    $expires = now()->addMinutes(15)->timestamp;

    return redirect()->route('reset.password', ['id' => $user->id, 'token' => $token, 'expires' => $expires]);
    }

    //Hien thi form doi mat khau
    public function showResetPassword(Request $request, $id)
    {
    $user = User::findOrFail($id);

    // Xác thực token
    $token = $request->query('token');
    $expires = $request->query('expires');

    if (!$token || !$expires || !is_numeric($expires)) {
        abort(403, 'Liên kết đặt lại mật khẩu không hợp lệ.');
    }

    if (now()->timestamp > $expires) {
        abort(403, 'Liên kết đặt lại mật khẩu đã hết hạn.');
    }

    $expectedToken = hash_hmac('sha256', $user->id . '|' . $user->email . '|' . $expires, env('APP_KEY'));

    if (!hash_equals($expectedToken, $token)) {
        abort(403, 'Liên kết đặt lại mật khẩu không hợp lệ.');
    }

    return view('auth.reset-password', compact('user'));
    }


    //Cap nhat mat khau moi
    public function resetPassword(Request $request, $id)
    {
    // Xác thực token từ query string
    $token = $request->query('token');
    $expires = $request->query('expires');
    $user = User::findOrFail($id);

    if (!$token || !$expires || !is_numeric($expires)) {
        abort(403, 'Liên kết đặt lại mật khẩu không hợp lệ.');
    }

    if (now()->timestamp > $expires) {
        abort(403, 'Liên kết đặt lại mật khẩu đã hết hạn.');
    }

    $expectedToken = hash_hmac('sha256', $user->id . '|' . $user->email . '|' . $expires, env('APP_KEY'));

    if (!hash_equals($expectedToken, $token)) {
        abort(403, 'Liên kết đặt lại mật khẩu không hợp lệ.');
    }

    $request->validate([
        'password' => [
            'required',
            'min:8',
            'confirmed'
        ]
    ]);

    $user->password = Hash::make($request->password);
    $user->save();

    return redirect('/login')->with('success', 'Đổi mật khẩu thành công.');
}
    //Hien thi form dang ky
    public function showRegister()
{
    return view('auth.register');
}   
    //Xu ly dang ky
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|unique:users,phone',
        'password' => [
            'required',
            'min:8',
            'confirmed'
        ]
    ],[
        'name.required' => 'Vui lòng nhập họ tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.unique' => 'Email đã tồn tại.',
        'phone.required' => 'Vui lòng nhập số điện thoại.',
        'phone.unique' => 'Số điện thoại đã tồn tại.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
        'password.confirmed' => 'Xác nhận mật khẩu không khớp.'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'role' => 'customer',
        'status' => 'active',
        'password' => Hash::make($request->password)
    ]);

    return redirect('/login')
        ->with(
            'success',
            'Đăng ký tài khoản thành công. Vui lòng đăng nhập để tiếp tục.'
        );
}
}

