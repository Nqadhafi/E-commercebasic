<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $items = [
            ['sku'=>'PRB-0001','name'=>'Kursi Plastik Napolly','price'=>85000,'stock'=>40,'weight_gram'=>2500],
            ['sku'=>'PRB-0002','name'=>'Meja Lipat Serbaguna','price'=>250000,'stock'=>20,'weight_gram'=>7000],
            ['sku'=>'PRB-0003','name'=>'Rak Sepatu 5 Susun','price'=>145000,'stock'=>15,'weight_gram'=>4000],
            ['sku'=>'PRB-0004','name'=>'Ember 20L','price'=>25000,'stock'=>80,'weight_gram'=>1500],
            ['sku'=>'PRB-0005','name'=>'Gayung Plastik','price'=>6000,'stock'=>120,'weight_gram'=>200],
            ['sku'=>'PRB-0006','name'=>'Wajan 30cm','price'=>75000,'stock'=>50,'weight_gram'=>1200],
            ['sku'=>'PRB-0007','name'=>'Panci 24cm','price'=>120000,'stock'=>35,'weight_gram'=>1800],
            ['sku'=>'PRB-0008','name'=>'Spatula Silikon','price'=>18000,'stock'=>100,'weight_gram'=>90],
            ['sku'=>'PRB-0009','name'=>'Sendok Set 12pcs','price'=>20000,'stock'=>90,'weight_gram'=>300],
            ['sku'=>'PRB-0010','name'=>'Pisau Dapur Serbaguna','price'=>30000,'stock'=>70,'weight_gram'=>250],
            ['sku'=>'PRB-0011','name'=>'Talenan Kayu','price'=>25000,'stock'=>60,'weight_gram'=>600],
            ['sku'=>'PRB-0012','name'=>'Rak Piring 2 Tingkat','price'=>130000,'stock'=>25,'weight_gram'=>2200],
            ['sku'=>'PRB-0013','name'=>'Toples Kaca 1L','price'=>35000,'stock'=>80,'weight_gram'=>900],
            ['sku'=>'PRB-0014','name'=>'Hanger Set 10pcs','price'=>18000,'stock'=>100,'weight_gram'=>400],
            ['sku'=>'PRB-0015','name'=>'Keset Microfiber','price'=>22000,'stock'=>70,'weight_gram'=>300],
            ['sku'=>'PRB-0016','name'=>'Sapu Ijuk','price'=>20000,'stock'=>60,'weight_gram'=>450],
            ['sku'=>'PRB-0017','name'=>'Pel Lantai Stainless','price'=>35000,'stock'=>55,'weight_gram'=>800],
            ['sku'=>'PRB-0018','name'=>'Keranjang Pakaian','price'=>55000,'stock'=>40,'weight_gram'=>1200],
            ['sku'=>'PRB-0019','name'=>'Tempat Sampah 20L','price'=>65000,'stock'=>30,'weight_gram'=>2000],
            ['sku'=>'PRB-0020','name'=>'Tirai Kamar Mandi','price'=>50000,'stock'=>25,'weight_gram'=>500],
            ['sku'=>'PRB-0021','name'=>'Jemuran Baju Lipat','price'=>220000,'stock'=>18,'weight_gram'=>6000],
            ['sku'=>'PRB-0022','name'=>'Rak Dinding Kayu','price'=>70000,'stock'=>30,'weight_gram'=>2500],
            ['sku'=>'PRB-0023','name'=>'Baskom 18L','price'=>18000,'stock'=>90,'weight_gram'=>1200],
            ['sku'=>'PRB-0024','name'=>'Piring Set 6pcs','price'=>60000,'stock'=>50,'weight_gram'=>1500],
            ['sku'=>'PRB-0025','name'=>'Gelas Set 6pcs','price'=>45000,'stock'=>50,'weight_gram'=>900],
        ];

        $rows = array_map(function($x) use ($now){
            return [
                'name'         => $x['name'],
                'sku'          => $x['sku'],
                'price'        => $x['price'],
                'stock'        => $x['stock'],
                'weight_gram'  => $x['weight_gram'],
                'thumbnail'    => null,
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }, $items);

        // insert bulk
        Product::insert($rows);
    }
}
