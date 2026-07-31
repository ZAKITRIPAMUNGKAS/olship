<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::role('staff')->first() ?? User::role('super_admin')->first();
        $categories = Category::all();
        $brands = Brand::all();

        if ($categories->isEmpty()) return;

        $productData = [
            [
                'name' => 'Mesin Bor Listrik Bosch GSB 550 Pro',
                'price' => 550000,
                'compare_price' => 600000,
                'category' => 'Power Tools',
                'brand' => 'Bosch',
                'description' => 'Bor listrik tangguh 550W untuk kayu dan beton.',
                'is_featured' => true,
            ],
            [
                'name' => 'Gerinda Tangan Makita GA4030',
                'price' => 725000,
                'compare_price' => 850000,
                'category' => 'Power Tools',
                'brand' => 'Makita',
                'description' => 'Gerinda tangan slim design untuk kenyamanan penggunaan lama.',
                'is_featured' => true,
            ],
            [
                'name' => 'Set Kunci Pas Tekiro 8-24mm',
                'price' => 350000,
                'compare_price' => 420000,
                'category' => 'Hand Tools',
                'brand' => 'Tekiro',
                'description' => 'Kunci pas Chrome Vanadium kualitas industri.',
                'is_featured' => true,
            ],
            [
                'name' => 'Tang Kombinasi Krisbow 7 Inch',
                'price' => 85000,
                'compare_price' => 95000,
                'category' => 'Hand Tools',
                'brand' => 'Krisbow',
                'description' => 'Tang serbaguna dengan grip anti slip.',
                'is_featured' => false,
            ],
            [
                'name' => 'Multimeter Digital Sanwa CD800a',
                'price' => 650000,
                'compare_price' => 700000,
                'category' => 'Alat Ukur',
                'brand' => 'Sanwa',
                'description' => 'Alat ukur presisi buatan Jepang.',
                'is_featured' => true,
            ],
            [
                'name' => 'Meteran Laser Bosch GLM 40',
                'price' => 1250000,
                'compare_price' => 1400000,
                'category' => 'Alat Ukur',
                'brand' => 'Bosch',
                'description' => 'Pengukuran jarak cepat dan akurat hingga 40 meter.',
                'is_featured' => false,
            ],
            [
                'name' => 'Kabel Eterna NYM 2x1.5mm 50m',
                'price' => 450000,
                'compare_price' => 480000,
                'category' => 'Kelistrikan',
                'brand' => 'Eterna',
                'description' => 'Kabel listrik standar SNI berkualitas tinggi.',
                'is_featured' => true,
            ],
            [
                'name' => 'Stop Kontak Broco 4 Lubang + Kabel',
                'price' => 75000,
                'compare_price' => 85000,
                'category' => 'Kelistrikan',
                'brand' => 'Broco',
                'description' => 'Terminal listrik aman dengan saklar pusat.',
                'is_featured' => false,
            ],
            [
                'name' => 'Lampu LED Philips MyCare 12W',
                'price' => 45000,
                'compare_price' => 55000,
                'category' => 'Lampu',
                'brand' => 'Philips',
                'description' => 'Lampu LED hemat energi dengan teknologi MyCare.',
                'is_featured' => true,
            ],
            [
                'name' => 'Lampu Sorot LED Hanochs 50W',
                'price' => 150000,
                'compare_price' => 180000,
                'category' => 'Lampu',
                'brand' => 'Hanochs',
                'description' => 'Floodlight outdoor IP65 tahan cuaca.',
                'is_featured' => false,
            ],
            [
                'name' => 'Kompresor Angin Lakoni Imola 75',
                'price' => 1100000,
                'compare_price' => 1250000,
                'category' => 'Pneumatic',
                'brand' => 'Lakoni',
                'description' => 'Kompresor portable 0.75 HP untuk bengkel.',
                'is_featured' => true,
            ],
            [
                'name' => 'Mesin Las Inverter Rhino MMA-120',
                'price' => 850000,
                'compare_price' => 950000,
                'category' => 'Perlengkapan Las',
                'brand' => 'Rhino',
                'description' => 'Mesin las portable hemat listrik 450W.',
                'is_featured' => true,
            ],
            [
                'name' => 'Smart Switch Wifi Tuya 1 Channel',
                'price' => 95000,
                'compare_price' => 120000,
                'category' => 'Kelistrikan',
                'brand' => 'Broco',
                'description' => 'Saklar pintar pintar wifi yang dapat dikontrol menggunakan aplikasi handphone Android/iOS dari jarak jauh.',
                'is_featured' => true,
            ],
            [
                'name' => 'Lampu LED Philips Essential 18W Putih',
                'price' => 59000,
                'compare_price' => 69000,
                'category' => 'Lampu',
                'brand' => 'Philips',
                'description' => 'Lampu bohlam LED hemat energi 18 Watt merk Philips warna cahaya putih terang tahan lama.',
                'is_featured' => false,
            ],
            [
                'name' => 'Kabel Eterna NYA 1x1.5mm 100 Meter',
                'price' => 260000,
                'compare_price' => 290000,
                'category' => 'Kelistrikan',
                'brand' => 'Eterna',
                'description' => 'Kabel tunggal NYA ukuran 1x1.5mm panjang 100 meter kualitas standar SNI untuk instalasi kelistrikan.',
                'is_featured' => true,
            ],
            [
                'name' => 'Tang Potong Tekiro 6 Inch',
                'price' => 55000,
                'compare_price' => 65000,
                'category' => 'Hand Tools',
                'brand' => 'Tekiro',
                'description' => 'Tang potong merk Tekiro ukuran 6 inch bahan Chrome Vanadium yang sangat tajam dan presisi.',
                'is_featured' => true,
            ],
            [
                'name' => 'MCB Schneider Electric Domae 1 Phase 16A',
                'price' => 49000,
                'compare_price' => 65000,
                'category' => 'Kelistrikan',
                'brand' => 'Schneider',
                'description' => 'Schneider Electric MCB Domae 1 Phase 16A adalah alat pengaman arus lebih dan hubungan singkat (korsleting) untuk instalasi kelistrikan rumah tinggal atau kantor. Kualitas terbaik standar internasional SNI.',
                'is_featured' => true,
            ],
            [
                'name' => 'Nugget Ayam So Good 500g',
                'price' => 32000,
                'compare_price' => 38000,
                'category' => 'Frozen Food',
                'brand' => 'So Good',
                'description' => 'Nugget ayam So Good 500g, renyah diluar lembut didalam. Cocok untuk sarapan dan makan siang praktis.',
                'is_featured' => true,
            ],
            [
                'name' => 'Sosis Sapi Fiesta 360g',
                'price' => 28500,
                'compare_price' => 33000,
                'category' => 'Frozen Food',
                'brand' => 'Fiesta',
                'description' => 'Sosis sapi premium Fiesta 360g. Daging sapi pilihan tanpa pengawet berbahaya, siap goreng atau bakar.',
                'is_featured' => true,
            ],
            [
                'name' => 'Fillet Ikan Dori Cedea 500g',
                'price' => 45000,
                'compare_price' => 52000,
                'category' => 'Frozen Food',
                'brand' => 'Cedea',
                'description' => 'Fillet ikan dori beku Cedea 500g tanpa tulang dan kulit. Segar, bersih, siap masak untuk berbagai hidangan.',
                'is_featured' => false,
            ],
            [
                'name' => 'Dimsum Siomay Udang Champ 300g',
                'price' => 38000,
                'compare_price' => 44000,
                'category' => 'Frozen Food',
                'brand' => 'Champ',
                'description' => 'Dimsum siomay udang premium Champ 300g. Isi udang asli, tekstur kenyal, cocok dikukus atau digoreng.',
                'is_featured' => true,
            ],
            [
                'name' => 'Beef Burger Patty Belfoods 400g',
                'price' => 55000,
                'compare_price' => 65000,
                'category' => 'Frozen Food',
                'brand' => 'Belfoods',
                'description' => 'Daging burger sapi Belfoods 400g (isi 4 pcs). Daging sapi murni berkualitas tinggi, juicy dan lezat untuk burger homemade.',
                'is_featured' => true,
            ],
        ];

        $wmsMapping = [
            'Mesin Bor Listrik Bosch GSB 550 Pro' => ['sku' => 'SKU-9CPHNFID', 'stock' => 50],
            'Gerinda Tangan Makita GA4030' => ['sku' => 'SKU-HL1WOEPZ', 'stock' => 40],
            'Set Kunci Pas Tekiro 8-24mm' => ['sku' => 'SKU-AAE2DZIH', 'stock' => 60],
            'Tang Kombinasi Krisbow 7 Inch' => ['sku' => 'SKU-VHURNAT3', 'stock' => 80],
            'Multimeter Digital Sanwa CD800a' => ['sku' => 'SKU-L8Q3IZXQ', 'stock' => 35],
            'Meteran Laser Bosch GLM 40' => ['sku' => 'SKU-AUEP5FFO', 'stock' => 25],
            'Kabel Eterna NYM 2x1.5mm 50m' => ['sku' => 'SKU-SLDENRLA', 'stock' => 45],
            'Stop Kontak Broco 4 Lubang + Kabel' => ['sku' => 'SKU-GYGUHSLZ', 'stock' => 90],
            'Lampu LED Philips MyCare 12W' => ['sku' => 'SKU-6AXKM6TW', 'stock' => 100],
            'Lampu Sorot LED Hanochs 50W' => ['sku' => 'SKU-SHKHAYQA', 'stock' => 15],
            'Kompresor Angin Lakoni Imola 75' => ['sku' => 'SKU-R5UCSBQY', 'stock' => 10],
            'Mesin Las Inverter Rhino MMA-120' => ['sku' => 'SKU-LUIY3SJS', 'stock' => 8],
            'Smart Switch Wifi Tuya 1 Channel' => ['sku' => 'SKU-VNV5LIY3', 'stock' => 30],
            'Lampu LED Philips Essential 18W Putih' => ['sku' => 'SKU-YD4SQUN1', 'stock' => 75],
            'Kabel Eterna NYA 1x1.5mm 100 Meter' => ['sku' => 'SKU-RHAOTUPD', 'stock' => 50],
            'Tang Potong Tekiro 6 Inch' => ['sku' => 'SKU-NVQBFM2M', 'stock' => 40],
            'MCB Schneider Electric Domae 1 Phase 16A' => ['sku' => 'SKU-J0KHEGDC', 'stock' => 120],
            'Nugget Ayam So Good 500g'                  => ['sku' => 'SKU-FRZ001SG', 'stock' => 200],
            'Sosis Sapi Fiesta 360g'                    => ['sku' => 'SKU-FRZ002FS', 'stock' => 150],
            'Fillet Ikan Dori Cedea 500g'               => ['sku' => 'SKU-FRZ003CD', 'stock' => 100],
            'Dimsum Siomay Udang Champ 300g'            => ['sku' => 'SKU-FRZ004CH', 'stock' => 120],
            'Beef Burger Patty Belfoods 400g'           => ['sku' => 'SKU-FRZ005BF', 'stock' => 80],
        ];

        $imageMapping = [
            'Mesin Bor Listrik Bosch GSB 550 Pro'       => 'products/bosch-drill.png',
            'Gerinda Tangan Makita GA4030'               => 'products/makita-grinder.png',
            'Set Kunci Pas Tekiro 8-24mm'                => 'products/tekiro-wrench.png',
            'Tang Kombinasi Krisbow 7 Inch'              => 'products/krisbow-pliers.png',
            'Multimeter Digital Sanwa CD800a'            => 'products/sanwa-multimeter.png',
            'Meteran Laser Bosch GLM 40'                 => 'products/bosch-laser.png',
            'Kabel Eterna NYM 2x1.5mm 50m'              => 'products/eterna-cable.png',
            'Stop Kontak Broco 4 Lubang + Kabel'        => 'products/broco-strip.png',
            'Lampu LED Philips MyCare 12W'               => 'products/philips-led.png',
            'Lampu Sorot LED Hanochs 50W'                => 'products/hanochs-floodlight.png',
            'Kompresor Angin Lakoni Imola 75'            => 'products/lakoni-compressor.png',
            'Mesin Las Inverter Rhino MMA-120'           => 'products/rhino-welder.png',
            'Smart Switch Wifi Tuya 1 Channel'           => 'products/tuya-smart-switch.png',
            'Lampu LED Philips Essential 18W Putih'      => 'products/philips-essential-18w.png',
            'Kabel Eterna NYA 1x1.5mm 100 Meter'        => 'products/eterna-nya-cable.png',
            'Tang Potong Tekiro 6 Inch'                  => 'products/tekiro-cutting-pliers.png',
            'MCB Schneider Electric Domae 1 Phase 16A'  => 'products/schneider-mcb.png',
            'Nugget Ayam So Good 500g'                   => 'products/frozen-nugget.png',
            'Sosis Sapi Fiesta 360g'                     => 'products/frozen-sosis.png',
            'Fillet Ikan Dori Cedea 500g'                => 'products/frozen-fish-fillet.png',
            'Dimsum Siomay Udang Champ 300g'             => 'products/frozen-dimsum.png',
            'Beef Burger Patty Belfoods 400g'            => 'products/frozen-burger.png',
        ];

        foreach ($productData as $data) {
            $cat = $categories->where('name', $data['category'])->first();
            $brand = $brands->where('name', $data['brand'])->first();
            
            $mapping = $wmsMapping[$data['name']] ?? ['sku' => 'SKU-' . strtoupper(Str::random(8)), 'stock' => rand(10, 100)];
            
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'seller_id' => $seller->id,
                    'category_id' => $cat->id,
                    'brand_id' => $brand?->id,
                    'name' => $data['name'],
                    'sku' => $mapping['sku'],
                    'short_description' => $data['description'],
                    'description' => '<p>' . $data['description'] . ' Sangat direkomendasikan untuk profesional maupun hobi.</p>',
                    'price' => $data['price'],
                    'compare_price' => $data['compare_price'],
                    'stock' => $mapping['stock'],
                    'weight' => rand(500, 5000),
                    'is_active' => true,
                    'is_featured' => $data['is_featured'],
                    'rating_avg' => rand(40, 50) / 10,
                    'rating_count' => rand(5, 50),
                    'total_sold' => rand(20, 200),
                    'total_views' => rand(500, 2000),
                ]
            );

            // Attributes
            ProductAttribute::updateOrCreate(
                ['product_id' => $product->id, 'attribute_name' => 'Merek'],
                ['attribute_value' => $data['brand']]
            );
            ProductAttribute::updateOrCreate(
                ['product_id' => $product->id, 'attribute_name' => 'Kondisi'],
                ['attribute_value' => 'Baru']
            );

            // Product Image
            $imagePath = $imageMapping[$data['name']] ?? null;
            if ($imagePath) {
                ProductImage::updateOrCreate(
                    ['product_id' => $product->id, 'sort_order' => 1],
                    [
                        'image_path' => $imagePath,
                        'alt_text'   => $data['name'],
                        'is_primary' => true,
                    ]
                );
            }
        }
    }
}
