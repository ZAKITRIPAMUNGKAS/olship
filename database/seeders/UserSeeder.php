<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@listrindojaya.com'],
            [
                'name' => 'Listrindo Jaya Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Profile for Super Admin
        UserProfile::firstOrCreate(['user_id' => $superAdmin->id], [
            'bio' => 'System Administrator for Listrindo Jaya',
            'gender' => 'male'
        ]);

        // 2. Admin Standard
        $admin = User::firstOrCreate(
            ['email' => 'admin@1'],
            [
                'name' => 'General Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // 3. Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@1'],
            [
                'name' => 'Operational Staff',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $staff->assignRole('staff');

        // 4. Staff (operator internal — dulu seller)
        $staff2 = User::firstOrCreate(
            ['email' => 'staff2@1'],
            [
                'name' => 'Operator Gudang',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $staff2->assignRole('staff');

        // Create Store for internal reference (single store = Listrindo Jaya)
        Store::firstOrCreate(
            ['seller_id' => $staff2->id],
            [
                'name' => 'Listrindo Jaya Official Store',
                'slug' => Str::slug('Listrindo Jaya Official Store'),
                'description' => 'Toko resmi peralatan teknik dan perkakas berkualitas Listrindo Jaya.',
                'is_active' => true,
                'is_verified' => true,
            ]
        );

        // 5. Customer
        $customer = User::firstOrCreate(
            ['email' => 'user@listrindojaya.com'],
            [
                'name' => 'Listrindo Jaya Test Customer',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole('customer');

        // Create Profile for Customer
        UserProfile::firstOrCreate(['user_id' => $customer->id], [
            'bio' => 'Regular Customer at Listrindo Jaya',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta'
        ]);

        // 6. Additional Random Customers
        if (User::count() < 10) {
            User::factory(5)->create()->each(function ($user) {
                $user->assignRole('customer');
            });
        }
    }
}
