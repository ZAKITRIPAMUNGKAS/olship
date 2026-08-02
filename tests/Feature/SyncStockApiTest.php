<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class SyncStockApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seller;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.api.wms_token', 'test-token-123');

        $this->seller = \App\Models\User::create([
            'name' => 'Seller',
            'email' => 'seller@example.com',
            'password' => bcrypt('password')
        ]);

        $this->category = Category::create([
            'name' => 'Kabel',
            'slug' => 'kabel'
        ]);
    }

    public function test_rejects_unauthorized_requests()
    {
        $response = $this->postJson('/api/v1/products/sync-stock', [
            'kode_barang' => 'PRD-001',
            'total_stock' => 10,
            'calculated_at' => '2026-05-26T19:30:00Z',
        ]);

        $response->assertStatus(401);
    }

    public function test_rejects_requests_with_invalid_token()
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson('/api/v1/products/sync-stock', [
                'kode_barang' => 'PRD-001',
                'total_stock' => 10,
                'calculated_at' => '2026-05-26T19:30:00Z',
            ]);

        $response->assertStatus(401);
    }

    public function test_creates_new_product_if_sku_does_not_exist()
    {
        $category = Category::create(['name' => 'General', 'slug' => 'general']);

        $response = $this->withHeader('Authorization', 'Bearer test-token-123')
            ->postJson('/api/v1/products/sync-stock', [
                'kode_barang' => 'PRD-NEW-001',
                'total_stock' => 50,
                'calculated_at' => '2026-05-26T19:30:00Z',
                'nama' => 'Test Product New',
                'harga' => 150000,
                'deskripsi' => 'This is a new product',
                'image_url' => 'https://example.com/new.jpg'
            ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Product master data and stock synced successfully']);

        $product = Product::first();
        $this->assertNotNull($product);
        $this->assertEquals(50, $product->stock);
        $this->assertEquals('Test Product New', $product->name);
        $this->assertEquals(150000, $product->price);
        $this->assertEquals('This is a new product', $product->description);
        $this->assertEquals('https://example.com/new.jpg', $product->image_url_override);
        
        $this->assertNotEmpty($product->sku);
    }

    public function test_updates_existing_product_stock()
    {
        $product = Product::create([
            'name' => 'Existing Product',
            'slug' => 'existing-product',
            'sku' => 'PRD-EX-001', // This is what is sent as kode_barang
            'price' => 10000,
            'stock' => 5,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer test-token-123')
            ->postJson('/api/v1/products/sync-stock', [
                'kode_barang' => 'PRD-EX-001',
                'total_stock' => 20,
                'calculated_at' => '2026-05-26T19:30:00Z',
                'image_url' => 'https://example.com/updated.jpg'
            ]);

        $response->assertStatus(200);

        $product->refresh();
        $this->assertEquals(20, $product->stock);
        $this->assertEquals('https://example.com/updated.jpg', $product->image_url_override);
    }

    public function test_syncs_gallery_image_urls()
    {
        $product = Product::create([
            'name' => 'Existing Product',
            'slug' => 'existing-product',
            'sku' => 'PRD-EX-002',
            'price' => 10000,
            'stock' => 5,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer test-token-123')
            ->postJson('/api/v1/products/sync-stock', [
                'kode_barang' => 'PRD-EX-002',
                'total_stock' => 20,
                'calculated_at' => '2026-05-26T19:30:00Z',
                'gallery_image_urls' => [
                    'https://example.com/img1.jpg',
                    'https://example.com/img2.jpg',
                ]
            ]);

        $response->assertStatus(200);

        $images = $product->images;
        $this->assertCount(2, $images);
        $this->assertEquals('https://example.com/img1.jpg', $images[0]->image_path);
        $this->assertTrue((bool)$images[0]->is_primary);
        $this->assertEquals('https://example.com/img2.jpg', $images[1]->image_path);
        $this->assertFalse((bool)$images[1]->is_primary);
    }

    public function test_skips_stale_data()
    {
        $product = Product::create([
            'name' => 'Existing Product',
            'slug' => 'existing-product',
            'sku' => 'PRD-EX-003',
            'price' => 10000,
            'stock' => 50,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'last_stock_sync_at' => Carbon::parse('2026-05-26T20:00:00Z'),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer test-token-123')
            ->postJson('/api/v1/products/sync-stock', [
                'kode_barang' => 'PRD-EX-003',
                'total_stock' => 10,
                'calculated_at' => '2026-05-26T19:00:00Z', // Older than last_stock_sync_at
            ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Skipped: stale data']);

        $product->refresh();
        $this->assertEquals(50, $product->stock); // Unchanged
    }

    public function test_validates_required_fields()
    {
        $response = $this->withHeader('Authorization', 'Bearer test-token-123')
            ->postJson('/api/v1/products/sync-stock', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['kode_barang', 'total_stock', 'calculated_at']);
    }
}
