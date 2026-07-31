<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    protected $fillable = [
        'flash_sale_id', 'product_id', 'variant_option_id',
        'discount_type', 'discount_value', 'flash_price',
        'quota', 'sold_quota', 'max_per_user', 'is_active'
    ];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActiveForProduct($query, $productId)
    {
        return $query->where('product_id', $productId)
            ->where('is_active', true)
            ->whereHas('flashSale', function ($q) {
                $q->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now());
            });
    }
}
