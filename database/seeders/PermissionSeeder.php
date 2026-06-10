<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage_products' => 'Quản lý sản phẩm',
            'manage_categories' => 'Quản lý danh mục',
            'manage_orders' => 'Quản lý đơn hàng',
            'manage_customers' => 'Quản lý khách hàng',
            'manage_coupons' => 'Quản lý mã giảm giá',
            'view_reports' => 'Xem báo cáo',
            'manage_permissions' => 'Quản lý phân quyền',
        ];

        foreach ($permissions as $name => $description) {
            Permission::create(compact('name', 'description'));
        }

        $adminRole = Role::create([
            'name' => 'Quản trị viên',
            'description' => 'Toàn quyền hệ thống',
        ]);

        $staffRole = Role::create([
            'name' => 'Nhân viên',
            'description' => 'Quản lý đơn hàng và sản phẩm',
        ]);

        $adminRole->permissions()->sync(Permission::pluck('id'));
        $staffRole->permissions()->sync(
            Permission::whereIn('name', ['manage_products', 'manage_orders', 'view_reports'])->pluck('id')
        );
    }
}
