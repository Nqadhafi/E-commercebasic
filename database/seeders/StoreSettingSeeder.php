<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreSetting;

class StoreSettingSeeder extends Seeder
{
    public function run()
    {
        StoreSetting::firstOrCreate(
            ['id' => 1],
            [
                'store_name' => 'Tata Grosir Solo',
                'store_phone' => '6285743255888',
                'store_address' => 'Jl. Raya Solo - Yogyakarta, Dusun II, Pucangan, Kec. Kartasura, Kabupaten Sukoharjo, Jawa Tengah 57168',
                'rajaongkir_api_key' => env('RAJAONGKIR_API_KEY'),
                'rajaongkir_account_type' => env('RAJAONGKIR_ACCOUNT_TYPE','starter'),
                'origin_city_id' => 399, 
                'origin_district_id' => '5262',// contoh: Surakarta
                'active_couriers' => ['jne'],
                'shipping_markup_flat' => 0,
                'fonnte_token' => env('FONNTE_TOKEN'),
                'fonnte_sender' => env('FONNTE_SENDER')
            ]
        );
    }
}
