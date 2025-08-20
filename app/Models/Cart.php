<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id','session_id'];

    public function user(){ return $this->belongsTo(User::class); }
    public function items(){ return $this->hasMany(CartItem::class); }

    public function subtotal()
    {
        return $this->items->sum(fn($i)=> $i->price_at * $i->qty);
    }

    public function totalWeightGram()
    {
        return $this->items->sum(fn($i)=> ($i->product->weight_gram ?? 0) * $i->qty);
    }
}
