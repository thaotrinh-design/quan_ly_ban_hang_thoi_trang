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
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                    ->orWhere('email', 'like', "%{$request->keyword}%")
                    ->orWhere('phone', 'like', "%{$request->keyword}%");
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

        $user->delete();

        return back()->with('success', 'Xóa khách hàng thành công.');
    }
}
