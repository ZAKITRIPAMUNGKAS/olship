<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'products.view', 'products.create', 'products.update', 'products.delete', 'products.publish', 'products.feature',
            'orders.view', 'orders.update-status', 'orders.export', 'orders.refund',
            'users.view', 'users.create', 'users.update', 'users.delete', 'users.ban',
            'coupons.view', 'coupons.create', 'coupons.update', 'coupons.delete',
            'flash_sales.view', 'flash_sales.create', 'flash_sales.update', 'flash_sales.delete',
            'settings.view', 'settings.update',
            'reports.view', 'reports.export',
            'reviews.manage', 'discussions.manage', 'banners.manage', 'system.logs',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
