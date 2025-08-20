<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUSES = ['pending','paid','processing','shipped','completed','canceled'];

    protected $fillable = [
        'user_id',
        'ship_name','ship_phone','ship_address','ship_province','ship_city','ship_postal','destination_city_id',
        'courier','service','etd','shipping_cost',
        'subtotal','discount','grandtotal',
        'resi','status','ordered_at'
    ];
    protected $casts = ['ordered_at'=>'datetime'];

    public function user(){ return $this->belongsTo(User::class); }
    public function items(){ return $this->hasMany(OrderItem::class); }

    public function scopeMine($q, $userId){ return $q->where('user_id',$userId); }
}
