<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MapExistingSkus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sku:map 
                            {--strategy=auto : Strategy to use: "auto" for fuzzy name matching, or "csv" for manual mapping}
                            {--file= : Path to the WMS products CSV or mapping CSV file}
                            {--dry-run : Preview the mapping without saving to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Map existing online shop products to WMS products using name similarity or CSV mapping';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $strategy = $this->option('strategy');
        $filePath = $this->option('file');
        $dryRun = $this->option('dry-run');

        if (!$filePath || !file_exists($filePath)) {
            $this->error("File WMS/mapping CSV tidak ditemukan. Silakan tentukan path file yang benar dengan --file=path/ke/file.csv");
            return 1;
        }

        $this->info("Memulai proses pemetaan dengan strategi: " . strtoupper($strategy) . ($dryRun ? " (DRY RUN)" : ""));

        $csvData = $this->readCsv($filePath);
        if (empty($csvData)) {
            $this->error("File CSV kosong atau format tidak sesuai.");
            return 1;
        }

        if ($strategy === 'csv') {
            $this->processManualCsv($csvData, $dryRun);
        } else {
            $this->processAutoMatching($csvData, $dryRun);
        }

        return 0;
    }

    /**
     * Read CSV file helper.
     */
    private function readCsv($path)
    {
        $rows = [];
        if (($handle = fopen($path, "r")) !== false) {
            $headers = fgetcsv($handle, 1000, ",");
            // Normalize headers
            $headers = array_map(function($h) {
                return strtolower(trim($h, "\xEF\xBB\xBF "));
            }, $headers);

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if (count($headers) === count($data)) {
                    $rows[] = array_combine($headers, $data);
                }
            }
            fclose($handle);
        }
        return $rows;
    }

    /**
     * Process manual mapping where CSV has columns like:
     * - product_name / name
     * - sku / code
     * OR
     * - olshop_id, wms_code
     */
    private function processManualCsv($data, $dryRun)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($data as $row) {
            $sku = $row['sku'] ?? $row['kode_barang'] ?? $row['wms_code'] ?? null;
            $productName = $row['name'] ?? $row['nama'] ?? $row['product_name'] ?? null;
            $productId = $row['id'] ?? $row['product_id'] ?? $row['olshop_id'] ?? null;

            if (!$sku) {
                $this->warn("Baris dilewati karena tidak ada kolom SKU/kode_barang.");
                continue;
            }

            // Find product in Olshop
            $product = null;
            if ($productId) {
                $product = Product::find($productId);
            } elseif ($productName) {
                $product = Product::where('name', $productName)->first();
            }

            if ($product) {
                $this->info("Menghubungkan: \"{$product->name}\" -> SKU WMS: \"{$sku}\"");
                if (!$dryRun) {
                    $product->update(['sku' => $sku]);
                }
                $successCount++;
            } else {
                $this->error("Gagal mencocokkan produk: \"{$productName}\" (ID: {$productId}) ke SKU WMS: \"{$sku}\"");
                $failCount++;
            }
        }

        $this->info("Selesai. Sukses dipetakan: {$successCount}, Gagal: {$failCount}");
    }

    /**
     * Process auto mapping using fuzzy name matching.
     */
    private function processAutoMatching($wmsProducts, $dryRun)
    {
        $olshopProducts = Product::all();
        $this->info("Memuat " . $olshopProducts->count() . " produk dari Olshop...");

        $successCount = 0;
        $failCount = 0;

        foreach ($olshopProducts as $olshopProduct) {
            $bestMatch = null;
            $bestScore = 999;
            $bestWmsCode = null;

            foreach ($wmsProducts as $wmsProduct) {
                $wmsName = $wmsProduct['nama'] ?? $wmsProduct['name'] ?? null;
                $wmsCode = $wmsProduct['kode_barang'] ?? $wmsProduct['sku'] ?? null;

                if (!$wmsName || !$wmsCode) {
                    continue;
                }

                $distance = levenshtein(
                    strtolower(trim($olshopProduct->name)), 
                    strtolower(trim($wmsName))
                );

                if ($distance < $bestScore) {
                    $bestScore = $distance;
                    $bestMatch = $wmsName;
                    $bestWmsCode = $wmsCode;
                }
            }

            // Threshold: levenshtein distance <= 5 or 25% of string length
            $threshold = max(5, round(strlen($olshopProduct->name) * 0.25));

            if ($bestScore <= $threshold) {
                $this->info("Cocok: \"{$olshopProduct->name}\" <=> \"{$bestMatch}\" (Score: {$bestScore}) -> Set SKU: \"{$bestWmsCode}\"");
                if (!$dryRun) {
                    $olshopProduct->update(['sku' => $bestWmsCode]);
                }
                $successCount++;
            } else {
                $this->warn("TIDAK COCOK: \"{$olshopProduct->name}\" (Terdekat: \"{$bestMatch}\", Score: {$bestScore})");
                $failCount++;
            }
        }

        $this->info("Selesai. Otomatis dipetakan: {$successCount}, Gagal/Perlu manual: {$failCount}");
    }
}
