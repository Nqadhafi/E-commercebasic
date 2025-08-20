<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','sku','price','stock','weight_gram','thumbnail','is_active'];
    protected $casts = ['is_active'=>'bool'];

    public function orderItems(){ return $this->hasMany(OrderItem::class); }
    public function cartItems(){ return $this->hasMany(CartItem::class); }
}
