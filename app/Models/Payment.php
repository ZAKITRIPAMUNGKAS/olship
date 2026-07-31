<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'order_id', 'payment_method', 'transaction_id', 'payment_gateway',
        'gateway_response', 'meta', 'amount', 'status', 'paid_at', 'expired_at'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'meta' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
