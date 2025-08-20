<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $r)
    {
        $q = Product::where('is_active',true);
        if ($r->filled('s')) $q->where('name','like','%'.$r->s.'%');
        $products = $q->latest()->paginate(12);
        return view('catalog.index', compact('products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        return view('catalog.show', compact('product'));
    }
}
