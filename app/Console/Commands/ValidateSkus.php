<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ValidateSkus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sku:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate that all products in Olshop have non-null, unique SKUs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai validasi SKU...");

        $products = Product::all();
        $nullSkus = 0;
        $skus = [];
        $duplicateSkus = [];

        foreach ($products as $product) {
            if (empty($product->sku)) {
                $this->error("PRODUK TANPA SKU: ID {$product->id} - \"{$product->name}\"");
                $nullSkus++;
            } else {
                if (in_array($product->sku, $skus)) {
                    $duplicateSkus[] = $product->sku;
                }
                $skus[] = $product->sku;
            }
        }

        if (!empty($duplicateSkus)) {
            $duplicateSkus = array_unique($duplicateSkus);
            foreach ($duplicateSkus as $dupSku) {
                $this->error("SKU DUPLIKAT: \"{$dupSku}\" digunakan oleh beberapa produk:");
                $dupProducts = Product::where('sku', $dupSku)->get();
                foreach ($dupProducts as $dp) {
                    $this->error("  - ID {$dp->id} - \"{$dp->name}\"");
                }
            }
        }

        $this->info("---------------------------------------------");
        if ($nullSkus === 0 && empty($duplicateSkus)) {
            $this->info("VALIDASI SUKSES: Semua produk memiliki SKU yang unik dan valid.");
            return 0;
        } else {
            $this->warn("VALIDASI SELESAI DENGAN PERINGATAN:");
            $this->warn("- Produk tanpa SKU: {$nullSkus}");
            $this->warn("- SKU duplikat: " . count($duplicateSkus));
            return 1;
        }
    }
}
