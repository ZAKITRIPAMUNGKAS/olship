<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Coupon extends Model
{
    use LogsActivity;
    protected $fillable = [
        'code', 'name', 'description', 'discount_type', 'discount_value', 
        'min_order_amount', 'max_discount_amount', 'usage_limit_total', 
        'usage_limit_per_user', 'used_count', 'starts_at', 'ends_at', 'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->ends_at && $this->ends_at->isPast()) return false;
        if ($this->usage_limit_total && $this->used_count >= $this->usage_limit_total) return false;
        
        return true;
    }
}
