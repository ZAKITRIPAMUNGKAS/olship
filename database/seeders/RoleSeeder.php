<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::findOrCreate('super_admin');
        $admin      = Role::findOrCreate('admin');
        $staff      = Role::findOrCreate('staff');
        $customer   = Role::findOrCreate('customer');

        // Super Admin gets all permissions
        $superAdmin->givePermissionTo(Permission::all());

        // Admin: full operational & management access
        $admin->givePermissionTo([
            'products.view', 'products.create', 'products.update', 'products.delete', 'products.publish', 'products.feature',
            'orders.view', 'orders.update-status', 'orders.export', 'orders.refund',
            'users.view', 'users.update', 'users.ban',
            'coupons.view', 'coupons.create', 'coupons.update', 'coupons.delete',
            'flash_sales.view', 'flash_sales.create', 'flash_sales.update', 'flash_sales.delete',
            'reports.view', 'reports.export',
            'reviews.manage', 'discussions.manage', 'banners.manage', 'settings.view',
        ]);

        // Staff: operational only (Produk & Order)
        $staff->givePermissionTo([
            'products.view', 'products.create', 'products.update',
            'orders.view', 'orders.update-status',
            'reviews.manage', 'discussions.manage',
        ]);
    }
}
