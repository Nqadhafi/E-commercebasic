<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $q = Product::latest()->paginate(12);
        return view('admin.products.index', compact('q'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'=>'required|string|max:200',
            'sku'=>'nullable|string|max:100',
            'price'=>'required|integer|min:0',
            'stock'=>'required|integer|min:0',
            'weight_gram'=>'nullable|integer|min:0',
            'thumbnail'=>'nullable|image|max:2048',
            'is_active'=>'nullable|boolean',
        ]);
        if (empty($data['sku'])) $data['sku'] = 'SKU-'.Str::upper(Str::random(8));
        if ($r->hasFile('thumbnail')) {
            $data['thumbnail'] = $r->file('thumbnail')->store('products','public');
        }
        $data['is_active'] = $r->boolean('is_active');
        Product::create($data);
        return redirect()->route('admin.products.index')->with('ok','Saved');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $r, Product $product)
    {
        $data = $r->validate([
            'name'=>'required|string|max:200',
            'sku'=>'required|string|max:100',
            'price'=>'required|integer|min:0',
            'stock'=>'required|integer|min:0',
            'weight_gram'=>'nullable|integer|min:0',
            'thumbnail'=>'nullable|image|max:2048',
            'is_active'=>'nullable|boolean',
        ]);
        if ($r->hasFile('thumbnail')) {
            $data['thumbnail'] = $r->file('thumbnail')->store('products','public');
        }
        $data['is_active'] = $r->boolean('is_active');
        $product->update($data);
        return redirect()->route('admin.products.index')->with('ok','Updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('ok','Deleted');
    }
}
