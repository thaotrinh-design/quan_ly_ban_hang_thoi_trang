<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->orderByDesc('id');

        if ($request->keyword) {
            $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $request->keyword);
            $query->where(function ($q) use ($escaped) {
                $q->where('name', 'like', "%{$escaped}%")
                    ->orWhere('email', 'like', "%{$escaped}%")
                    ->orWhere('phone', 'like', "%{$escaped}%");
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }

        $user->update([
            'status' => $user->status === 'active' ? 'locked' : 'active',
        ]);

        return back()->with('success', 'Cập nhật trạng thái tài khoản thành công.');
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }

        if ($user->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa khách hàng có đơn hàng.');
        }

        $user->delete();

        return back()->with('success', 'Xóa khách hàng thành công.');
    }
}
