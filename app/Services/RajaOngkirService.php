<?php

namespace App\Services;

use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class RajaOngkirService
{
    protected $baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    protected $key;

    public function __construct()
    {
        $s = StoreSetting::first();
        $this->key = $s && $s->rajaongkir_api_key ? $s->rajaongkir_api_key : env('RAJAONGKIR_API_KEY', '');
    }

public function getBaseUrl(){ return $this->baseUrl; }
public function hasKey(){ return !empty($this->key); }

public function provincesWithMeta(): array
{
    $url = $this->baseUrl . '/destination/province';
    $res = $this->http()->get($url);
    $json = $res->json();
    return [
        'status'  => $res->status(),
        'ok'      => $res->successful(),
        'baseUrl' => $this->baseUrl,
        'hasKey'  => $this->hasKey(),
        'data'    => isset($json['data']) && is_array($json['data']) ? $json['data'] : [],
        'raw'     => $json,
    ];
}

public function citiesWithMeta(int $provinceId): array
{
    $url = $this->baseUrl . '/destination/city';
    $res = $this->http()->get($url, ['province_id' => $provinceId]);
    $json = $res->json();
    return [
        'status'  => $res->status(),
        'ok'      => $res->successful(),
        'baseUrl' => $this->baseUrl,
        'hasKey'  => $this->hasKey(),
        'data'    => isset($json['data']) && is_array($json['data']) ? $json['data'] : [],
        'raw'     => $json,
    ];
}


    protected function http()
    {
        return Http::withHeaders(['key' => $this->key])
            ->acceptJson()
            ->timeout(20);
    }

    /**
     * Cari tujuan domestik (provinsi/kota/kecamatan/kelurahan/kodepos) via satu endpoint.
     * Gunakan hasil "data[].id" untuk parameter origin/destination saat hitung ongkir.
     */
    public function searchDomesticDestination(string $search, int $limit = 50, int $offset = 0): array
    {
        $url = $this->baseUrl . '/destination/domestic-destination';
        $res = $this->http()->get($url, [
            'search' => $search,
            'limit'  => $limit,
            'offset' => $offset,
        ]);

        $json = $res->json();
        return isset($json['data']) && is_array($json['data']) ? $json['data'] : [];
    }


public function citiesByProvince(int $provinceId): array
{
    $url = $this->baseUrl . '/destination/city';
    $res = $this->http()->get($url, ['province_id' => $provinceId]);
    $json = $res->json();
    return isset($json['data']) && is_array($json['data']) ? $json['data'] : [];
}


    /**
     * Hitung ongkir domestik.
     * $courier contoh: "jne" / "pos" / "tiki" (sesuai ketersediaan).
     * $price: "lowest" atau "highest" (opsional; sesuai docs).
     */
    public function calculateDomesticCost(int $originId, int $destinationId, int $weightGram, string $courier, string $price = 'lowest'): array
    {
        $url = $this->baseUrl . '/calculate/domestic-cost';
        $payload = [
            'origin'      => $originId,
            'destination' => $destinationId,
            'weight'      => max(1, $weightGram), // gram
            'courier'     => $courier,
            'price'       => $price,              // "lowest"|"highest"
        ];

        $res  = $this->http()->asForm()->post($url, $payload);
        $json = $res->json();

        // Response berisi meta + data[] (name, code, service, description, cost, etd)
        return isset($json['data']) && is_array($json['data']) ? $json['data'] : [];
    }


public function provinces(): array {
    return Cache::remember('ro:provinces', 86400, function(){
        $res = $this->http()->get($this->baseUrl.'/destination/province')->json();
        return $res['data'] ?? [];
    });
}

// NOTE: path param {province_id}
public function citiesByProvinceId(int $provinceId): array {
    return Cache::remember("ro:cities:$provinceId", 86400, function() use ($provinceId){
        $res = $this->http()->get($this->baseUrl.'/destination/city/'.$provinceId)->json();
        return $res['data'] ?? [];
    });
}

// NOTE: path param {city_id}
public function districtsByCityId(int $cityId): array {
    return Cache::remember("ro:districts:$cityId", 86400, function() use ($cityId){
        $res = $this->http()->get($this->baseUrl.'/destination/district/'.$cityId)->json();
        return $res['data'] ?? [];
    });
}

// Calculate ongkir: DISTRICT level
public function calculateDistrictDomesticCost(int $originDistrictId, int $destinationDistrictId, int $weightGram, string $couriers, string $price='lowest'): array
{
    $url = $this->baseUrl . '/calculate/district/domestic-cost';
    $payload = [
        'origin'      => $originDistrictId,
        'destination' => $destinationDistrictId,
        'weight'      => max(1, $weightGram),
        'courier'     => $couriers, // ex: "jne:pos:tiki" atau list panjang
        'price'       => $price,
    ];
    $res = $this->http()->asForm()->post($url, $payload);
    $json = $res->json();
    return isset($json['data']) && is_array($json['data']) ? $json['data'] : [];
}

}
