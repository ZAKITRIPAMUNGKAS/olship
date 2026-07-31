<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Order extends Model
{
    use LogsActivity;
    protected $fillable = [
        'order_number', 'user_id', 'status', 'payment_status',
        'shipping_status', 'shipping_name', 'shipping_phone',
        'shipping_address', 'shipping_city', 'shipping_province',
        'shipping_postal_code', 'subtotal', 'shipping_cost',
        'discount_amount', 'coupon_code', 'coupon_discount',
        'tax_amount', 'total_amount', 'courier', 'courier_service',
        'courier_cost', 'tracking_number', 'shipped_at',
        'customer_notes', 'admin_notes', 'paid_at',
        'delivered_at', 'completed_at', 'cancelled_at',
        'cancellation_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
