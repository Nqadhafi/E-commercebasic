<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','product_id','name_snapshot','price_snapshot','weight_gram_snapshot','qty'
    ];

    public function order(){ return $this->belongsTo(Order::class); }
    public function product(){ return $this->belongsTo(Product::class); }
}
