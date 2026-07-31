<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PROCESSED = 'processed';

    protected $fillable = [
        'seller_id', 'store_id', 'amount', 'status', 'bank_name', 
        'account_number', 'account_holder', 'admin_note', 'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
