<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OlshopProductModelTest extends TestCase
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
    }

    public function test_get_image_url_returns_override_if_set()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TES-001',
            'image_url_override' => 'https://example.com/override.jpg',
            'price' => 1000,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $this->assertEquals('https://example.com/override.jpg', $product->image_url);
    }

    public function test_get_image_url_returns_primary_image_if_no_override()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TES-001',
            'price' => 1000,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/primary.jpg',
            'is_primary' => true,
        ]);

        $this->assertEquals(asset('storage/products/primary.jpg'), $product->image_url);
    }

    public function test_get_image_url_returns_full_url_for_primary_image()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TES-001',
            'price' => 1000,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'https://example.com/primary.jpg',
            'is_primary' => true,
        ]);

        $this->assertEquals('https://example.com/primary.jpg', $product->image_url);
    }

    public function test_get_image_url_falls_back_to_first_image_if_no_primary()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TES-001',
            'price' => 1000,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/first.jpg',
            'is_primary' => false,
        ]);

        $this->assertEquals(asset('storage/products/first.jpg'), $product->image_url);
    }

    public function test_get_image_url_returns_placeholder_if_no_images()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'PRD-TES-001',
            'price' => 1000,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $this->assertEquals('https://placehold.co/400x400?text=' . urlencode($product->name), $product->image_url);
    }
}
