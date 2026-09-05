<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with('assignedRole')->get();

        return view('admin.permissions.index', compact('roles', 'permissions', 'users'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        Role::create($request->only(['name', 'description']));

        return back()->with('success', 'Tạo nhóm quyền thành công.');
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return back()->with('success', 'Gán quyền thành công.');
    }

    public function assignRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể thay đổi quyền của chính mình.');
        }

        $request->validate([
            'role_id' => 'nullable|exists:roles,id',
        ]);

        // Check if demoting the last admin
        if (!$request->role_id) {
            $adminRoleId = Role::where('name', 'admin')->value('id');
            if ($adminRoleId && $user->role_id === $adminRoleId) {
                $adminCount = User::where('role_id', $adminRoleId)->count();
                if ($adminCount <= 1) {
                    return back()->with('error', 'Không thể hạ quyền admin cuối cùng.');
                }
            }
        }

        $user->update(['role_id' => $request->role_id, 'role' => 'customer']);

        return back()->with('success', 'Phân quyền người dùng thành công.');
    }
}
