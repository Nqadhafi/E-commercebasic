<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'user_id','label','receiver_name','phone','address_line',
        'province','city','postal_code','rajaongkir_city_id','is_default'
    ];
    protected $casts = ['is_default'=>'bool'];

    public function user(){ return $this->belongsTo(User::class); }
}
