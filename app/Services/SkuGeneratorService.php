<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class SkuGeneratorService
{
    /**
     * Generate automatic SKU with format: [SINGKATAN_KATEGORI]-[SINGKATAN_NAMA_PRODUK]
     */
    public static function generateSku(?int $categoryId, string $productName, ?string $wmsCode = null): string
    {
        // 1. Determine Category Abbreviation
        $catPrefix = 'PRD';
        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category && !empty($category->name)) {
                $catName = strtoupper(trim($category->name));
                if (Str::contains($catName, ['SAFETY', 'APD', 'PELINDUNG'])) {
                    $catPrefix = 'APD';
                } elseif (Str::contains($catName, ['KABEL', 'CABLE'])) {
                    $catPrefix = 'KBL';
                } elseif (Str::contains($catName, ['LAMPU', 'ELEKTRIKAL', 'ELECTRIC'])) {
                    $catPrefix = 'ELK';
                } elseif (Str::contains($catName, ['MAINTENANCE', 'PERAWATAN'])) {
                    $catPrefix = 'MNT';
                } else {
                    // Extract initials or first 3 letters
                    $words = preg_split('/\s+/', $catName);
                    if (count($words) >= 2) {
                        $catPrefix = substr($words[0], 0, 2) . substr($words[1], 0, 2);
                    } else {
                        $catPrefix = substr($catName, 0, 3);
                    }
                }
            }
        }

        // 2. Determine Product Name Abbreviation
        $cleanName = preg_replace('/[^A-Za-z0-9\s\-]/', '', $productName);
        $words = array_filter(explode(' ', strtoupper($cleanName)));
        
        $abbrParts = [];
        foreach ($words as $word) {
            // Ignore common stop words
            if (in_array($word, ['DAN', 'FOR', 'DENGAN', 'DAN', 'PRODUTO', 'PRODUK'])) continue;
            // If word has numbers (like 2x1.5mm, 12W, WD-40) keep numbers
            if (preg_match('/[0-9]/', $word)) {
                $abbrParts[] = $word;
            } elseif (strlen($word) > 1) {
                $abbrParts[] = substr($word, 0, 3);
            } else {
                $abbrParts[] = $word;
            }
        }

        $prodAbbr = implode('-', array_slice($abbrParts, 0, 3));
        if (empty($prodAbbr)) {
            $prodAbbr = Str::upper(Str::random(4));
        }

        $baseSku = $catPrefix . '-' . $prodAbbr;

        // 3. Ensure Uniqueness
        $sku = $baseSku;
        $counter = 1;
        while (Product::where('sku', $sku)->exists()) {
            $sku = sprintf('%s-%02d', $baseSku, $counter);
            $counter++;
        }

        return $sku;
    }
}
