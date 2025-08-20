<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name','store_phone','store_address',
        'rajaongkir_api_key','rajaongkir_account_type','origin_city_id',
        'active_couriers','shipping_markup_flat',
        'fonnte_token','fonnte_sender','origin_district_id'
    ];
    protected $casts = ['active_couriers'=>'array'];
}
