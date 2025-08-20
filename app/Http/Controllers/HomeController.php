<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoreSetting;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::where('is_active',1)->latest()->take(8)->get();

        $s = StoreSetting::first();
        $name    = $s->store_name  ?? 'Tata Grosir Solo';
        $phone   = $s->store_phone ?? '081223311233';
        $address = $s->store_address ?? 'Depan kopasus solo, sukoharjo, jawa tengah';
        $tagline = 'Belanja mudah dari rumah';
        $hero    = asset('img/tgs.jpg');

        return view('home', compact('featured','name','phone','address','tagline','hero'));
    }
}
