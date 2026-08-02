<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminProductToggleTest extends TestCase
{
    use RefreshDatabase;
    
    protected $seller;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seller = User::create([
            'name' => 'Seller',
            'email' => 'seller@example.com',
            'password' => bcrypt('password')
        ]);

        $this->category = \App\Models\Category::create([
            'name' => 'Kabel',
            'slug' => 'kabel'
        ]);

        // Setup role and permission to pass middlewares
        $role = Role::firstOrCreate(['name' => 'admin']);
        $permission = Permission::firstOrCreate(['name' => 'products.view']);
        $role->givePermissionTo($permission);
    }

    public function test_admin_can_toggle_product_status_true_to_false()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TEST-001',
            'price' => 1000,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/admin/products/{$product->id}/toggle-status");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'is_active' => false,
                 ]);

        $product->refresh();
        $this->assertFalse((bool)$product->is_active);
    }
    
    public function test_admin_can_toggle_product_status_false_to_true()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TEST-002',
            'price' => 1000,
            'is_active' => false,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/admin/products/{$product->id}/toggle-status");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'is_active' => true,
                 ]);

        $product->refresh();
        $this->assertTrue((bool)$product->is_active);
    }

    public function test_requires_authentication()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TEST-003',
            'price' => 1000,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->postJson("/admin/products/{$product->id}/toggle-status");
        
        $response->assertStatus(401);
    }
}
