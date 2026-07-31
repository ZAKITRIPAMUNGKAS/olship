<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id', 
        'product_id', 
        'order_item_id',
        'rating', 
        'title',
        'comment', 
        'status',
        'seller_reply',
        'replied_at',
        'is_verified_purchase',
        'image_path'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
