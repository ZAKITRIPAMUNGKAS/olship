<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['id', 'province_id', 'name', 'type', 'postal_code'];
    public $incrementing = false;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
}
