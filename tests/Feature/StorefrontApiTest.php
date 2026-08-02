<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorefrontApiTest extends TestCase
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

        $this->category = Category::create([
            'name' => 'Kabel',
            'slug' => 'kabel'
        ]);
    }

    public function test_can_fetch_active_products()
    {
        Product::create([
            'name' => 'Active Product 1',
            'slug' => 'active-product-1',
            'sku' => 'PRD-ACT-001',
            'price' => 10000,
            'description' => 'Test description',
            'stock' => 10,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        Product::create([
            'name' => 'Inactive Product 1',
            'slug' => 'inactive-product-1',
            'sku' => 'PRD-INA-001',
            'price' => 5000,
            'is_active' => false,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson('/api/v1/storefront/products');

        $response->assertStatus(200)
                 ->assertHeader('Access-Control-Allow-Origin', '*')
                 ->assertJson([
                     'success' => true,
                     'total' => 1,
                 ])
                 ->assertJsonCount(1, 'products');

        $products = $response->json('products');
        $this->assertEquals('Active Product 1', $products[0]['name']);
        
        $response->assertJsonStructure([
            'success',
            'total',
            'products' => [
                '*' => [
                    'id', 'name', 'sku', 'price', 'price_idr', 'description', 'image_url', 'category', 'stock'
                ]
            ]
        ]);
    }

    public function test_can_filter_products_by_category_slug()
    {
        $cat1 = Category::create(['name' => 'Category 1', 'slug' => 'cat-1']);
        $cat2 = Category::create(['name' => 'Category 2', 'slug' => 'cat-2']);

        Product::create(['name' => 'P1', 'slug' => 'p-1', 'sku' => 'P-1', 'price' => 10, 'is_active' => true, 'category_id' => $cat1->id, 'seller_id' => $this->seller->id]);
        Product::create(['name' => 'P2', 'slug' => 'p-2', 'sku' => 'P-2', 'price' => 10, 'is_active' => true, 'category_id' => $cat2->id, 'seller_id' => $this->seller->id]);
        Product::create(['name' => 'P3', 'slug' => 'p-3', 'sku' => 'P-3', 'price' => 10, 'is_active' => true, 'category_id' => $cat1->id, 'seller_id' => $this->seller->id]);

        $response = $this->getJson('/api/v1/storefront/products?category=cat-1');

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'total' => 2])
                 ->assertJsonCount(2, 'products');
    }

    public function test_can_limit_products()
    {
        for ($i = 0; $i < 10; $i++) {
            Product::create(['name' => "P$i", 'slug' => "p-$i", 'sku' => "P-$i", 'price' => 10, 'is_active' => true, 'seller_id' => $this->seller->id, 'category_id' => $this->category->id]);
        }

        $response = $this->getJson('/api/v1/storefront/products?limit=5');

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'total' => 5])
                 ->assertJsonCount(5, 'products');
    }

    public function test_can_fetch_categories_with_active_products()
    {
        $cat1 = Category::create(['name' => 'Category 1', 'slug' => 'cat-1']); // Active products
        $cat2 = Category::create(['name' => 'Category 2', 'slug' => 'cat-2']); // Inactive products
        $cat3 = Category::create(['name' => 'Category 3', 'slug' => 'cat-3']); // No products

        Product::create(['name' => 'P1', 'slug' => 'p-1', 'sku' => 'P-1', 'price' => 10, 'is_active' => true, 'category_id' => $cat1->id, 'seller_id' => $this->seller->id]);
        Product::create(['name' => 'P2', 'slug' => 'p-2', 'sku' => 'P-2', 'price' => 10, 'is_active' => false, 'category_id' => $cat2->id, 'seller_id' => $this->seller->id]);

        $response = $this->getJson('/api/v1/storefront/categories');

        $response->assertStatus(200)
                 ->assertHeader('Access-Control-Allow-Origin', '*')
                 ->assertJson(['success' => true])
                 ->assertJsonCount(1, 'categories');

        $categories = $response->json('categories');
        $this->assertEquals('Category 1', $categories[0]['name']);
    }
}
