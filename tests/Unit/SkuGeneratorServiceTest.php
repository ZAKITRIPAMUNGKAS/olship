<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\SkuGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkuGeneratorServiceTest extends TestCase
{
    use RefreshDatabase;
    
    protected $seller;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seller = User::create([
            'name' => 'Seller',
            'email' => 'seller@example.com',
            'password' => bcrypt('password')
        ]);
    }

    public function test_generates_sku_with_safety_category()
    {
        $category = Category::create([
            'name' => 'SAFETY EQUIPMENT',
            'slug' => 'safety-equipment',
        ]);

        $sku = SkuGeneratorService::generateSku($category->id, 'Helm Proyek Kuning');
        
        $this->assertStringStartsWith('APD-', $sku);
        $this->assertStringContainsString('HEL-PRO-KUN', $sku);
    }

    public function test_generates_sku_with_kabel_category()
    {
        $category = Category::create([
            'name' => 'KABEL LISTRIK',
            'slug' => 'kabel-listrik',
        ]);

        $sku = SkuGeneratorService::generateSku($category->id, 'Kabel 2x1.5mm');
        
        $this->assertStringStartsWith('KBL-', $sku);
        $this->assertStringContainsString('KAB-2X15MM', $sku);
    }

    public function test_generates_sku_with_elektrikal_category()
    {
        $category = Category::create([
            'name' => 'ELEKTRIKAL',
            'slug' => 'elektrikal',
        ]);

        $sku = SkuGeneratorService::generateSku($category->id, 'Lampu Philips 12W');
        
        $this->assertStringStartsWith('ELK-', $sku);
        $this->assertStringContainsString('LAM-PHI-12W', $sku);
    }

    public function test_generates_sku_with_maintenance_category()
    {
        $category = Category::create([
            'name' => 'MAINTENANCE',
            'slug' => 'maintenance',
        ]);

        $sku = SkuGeneratorService::generateSku($category->id, 'WD-40 400ml');
        
        $this->assertStringStartsWith('MNT-', $sku);
        $this->assertStringContainsString('WD-40-400', $sku);
    }

    public function test_generates_sku_with_generic_category()
    {
        $category = Category::create([
            'name' => 'ALAT TULIS',
            'slug' => 'alat-tulis',
        ]);

        $sku = SkuGeneratorService::generateSku($category->id, 'Buku Tulis Sinar Dunia');
        
        // ALAT TULIS -> ALTU
        $this->assertStringStartsWith('ALTU-', $sku);
        $this->assertStringContainsString('BUK-TUL-SIN', $sku);
    }

    public function test_generates_sku_with_null_category()
    {
        $sku = SkuGeneratorService::generateSku(null, 'Barang Tidak Dikenal');
        
        $this->assertStringStartsWith('PRD-', $sku);
        $this->assertStringContainsString('BAR-TID-DIK', $sku);
    }

    public function test_generates_unique_sku_when_base_exists()
    {
        $category = Category::create([
            'name' => 'SAFETY',
            'slug' => 'safety',
        ]);

        // Create product manually to occupy the base SKU
        Product::create([
            'name' => 'Helm Proyek',
            'slug' => 'helm-proyek',
            'sku' => 'APD-HEL-PRO',
            'price' => 100000,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $category->id,
        ]);

        $sku = SkuGeneratorService::generateSku($category->id, 'Helm Proyek');
        
        $this->assertEquals('APD-HEL-PRO-01', $sku);

        Product::create([
            'name' => 'Helm Proyek',
            'slug' => 'helm-proyek-01',
            'sku' => 'APD-HEL-PRO-01',
            'price' => 100000,
            'is_active' => true,
            'seller_id' => $this->seller->id,
            'category_id' => $category->id,
        ]);

        $sku2 = SkuGeneratorService::generateSku($category->id, 'Helm Proyek');
        $this->assertEquals('APD-HEL-PRO-02', $sku2);
    }
}
