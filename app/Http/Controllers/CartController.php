<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected function currentCart(Request $r): Cart
    {
        if ($r->user()) {
            return Cart::firstOrCreate(['user_id'=>$r->user()->id]);
        }
        if (!$r->session()->has('cart_sid')) $r->session()->put('cart_sid', Str::uuid()->toString());
        return Cart::firstOrCreate(['session_id'=>$r->session()->get('cart_sid')]);
    }

    public function index(Request $r)
    {
        $cart = $this->currentCart($r)->load('items.product');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $r)
    {
        $data = $r->validate([
            'product_id'=>'required|exists:products,id',
            'qty'=>'required|integer|min:1'
        ]);
        $cart = $this->currentCart($r);
        $product = Product::findOrFail($data['product_id']);
        abort_if(!$product->is_active, 400, 'Inactive');
        abort_if($product->stock < $data['qty'], 400, 'Insufficient stock');

        $item = CartItem::firstOrNew(['cart_id'=>$cart->id,'product_id'=>$product->id]);
        $item->price_at = $product->price;
        $item->qty = ($item->exists ? $item->qty : 0) + $data['qty'];
        $item->save();

        return back()->with('ok','Added to cart');
    }

    public function update(Request $r, CartItem $item)
    {
        $data = $r->validate(['qty'=>'required|integer|min:1']);
        abort_if($item->product->stock < $data['qty'], 400, 'Insufficient stock');
        $item->qty = $data['qty'];
        $item->save();
        return back()->with('ok','Updated');
    }

    public function remove(CartItem $item)
    {
        $item->delete();
        return back()->with('ok','Removed');
    }

    public function clear(Request $r)
    {
        $cart = $this->currentCart($r);
        $cart->items()->delete();
        return back()->with('ok','Cleared');
    }
}
