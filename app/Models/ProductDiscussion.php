<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscussion extends Model
{
    protected $fillable = ['user_id', 'product_id', 'parent_id', 'message', 'is_admin_reply'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replies()
    {
        return $this->hasMany(ProductDiscussion::class, 'parent_id')->oldest();
    }

    public function parent()
    {
        return $this->belongsTo(ProductDiscussion::class, 'parent_id');
    }
}
