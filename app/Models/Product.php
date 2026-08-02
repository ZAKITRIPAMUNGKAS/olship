<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use App\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;
    use HasSlug;

    protected $fillable = [
        'seller_id', 'category_id', 'brand_id', 'name', 'slug', 'sku',
        'description', 'short_description', 'price', 'compare_price',
        'cost_price', 'stock', 'low_stock_threshold', 'weight',
        'length', 'width', 'height', 'condition', 'is_active',
        'is_featured', 'is_digital', 'meta_title', 'meta_description',
        'total_sold', 'total_views', 'rating_avg', 'rating_count',
        'last_stock_sync_at', 'image_url_override'
    ];

    protected $appends = ['image_url'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(ProductDiscussion::class);
    }

    public function getWeightDisplayAttribute()
    {
        $attr = $this->productAttributes->where('attribute_name', 'Berat')->first();
        return $attr ? $attr->attribute_value : '-';
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price > $this->price && $this->compare_price > 0) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getImageUrlAttribute()
    {
        // Priority 1: Override URL from WMS (always a full URL)
        if (!empty($this->image_url_override)) {
            return $this->image_url_override;
        }

        // Priority 2: Primary image from product_images table
        $primary = $this->relationLoaded('primaryImage')
            ? $this->getRelation('primaryImage')
            : $this->primaryImage;

        if ($primary) {
            // Support both full URLs (synced from WMS) and relative storage paths
            if (filter_var($primary->image_path, FILTER_VALIDATE_URL)) {
                return $primary->image_path;
            }
            return asset('storage/' . $primary->image_path);
        }

        // Priority 3: First image from gallery
        $images = $this->relationLoaded('images')
            ? $this->getRelation('images')
            : $this->images;

        $first = $images->first();
        if ($first) {
            if (filter_var($first->image_path, FILTER_VALIDATE_URL)) {
                return $first->image_path;
            }
            return asset('storage/' . $first->image_path);
        }

        // Fallback: placeholder with product name
        return 'https://placehold.co/400x400?text=' . urlencode($this->name);
    }
}
