<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Services\FonnteService;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $r, RajaOngkirService $ro)
    {
        $cart = $r->user()->cart()->with('items.product')->first();
        abort_if(!$cart || $cart->items->isEmpty(), 400, 'Cart empty');

        // --- Tambahan info untuk view ---
        $store = StoreSetting::first();
        $originDistrictId   = $store->origin_district_id ?? null;
        $originDistrictName = null;

        // Jika punya city_id asal, ambil daftar district lalu cari nama district asal
        if (!empty($store->origin_city_id) && !empty($originDistrictId)) {
            $districts = $ro->districtsByCityId((int)$store->origin_city_id);
            foreach ($districts as $d) {
                if ((int)($d['id'] ?? 0) === (int)$originDistrictId) {
                    $originDistrictName = $d['name'] ?? null;
                    break;
                }
            }
        }

        // Hitung total berat keranjang (gram)
        $totalWeight = $cart->items->sum(function($i){
            $w = $i->product->weight_gram ?? 0;
            return (int)$w * (int)$i->qty;
        });

        return view('checkout.index', compact('cart', 'originDistrictId', 'originDistrictName', 'totalWeight'));
    }

    public function provinces(RajaOngkirService $ro)
    {
        return response()->json(['data' => $ro->provinces()]);
    }

    public function cities(Request $r, RajaOngkirService $ro)
    {
        $data = $r->validate(['province_id' => 'required|integer']);
        return response()->json(['data' => $ro->citiesByProvinceId((int)$data['province_id'])]);
    }

    public function districts(Request $r, RajaOngkirService $ro)
    {
        $data = $r->validate(['city_id' => 'required|integer']);
        return response()->json(['data' => $ro->districtsByCityId((int)$data['city_id'])]);
    }

    public function shippingOptions(Request $r, RajaOngkirService $ro)
    {
        $data = $r->validate([
            'destination_district_id' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        $store = StoreSetting::first();
        if (empty($store->origin_district_id)) {
            return response()->json([
                'error' => 'ORIGIN_NOT_SET',
                'message' => 'Origin district ID belum diisi di Settings.',
            ], 422);
        }

        $cart = $r->user()->cart()->with('items.product')->firstOrFail();
        $weight = $cart->items->sum(function($i){
            $w = $i->product->weight_gram ?? 0;
            return (int)$w * (int)$i->qty;
        });

        $list = $ro->calculateDistrictDomesticCost(
            (int)$store->origin_district_id,
            (int)$data['destination_district_id'],
            (int)max(1, $weight),
            $data['courier'],
            'lowest'
        );

        return response()->json(['data'=>$list]);
    }

    protected function formatOrderWa(Order $order, ?StoreSetting $store): string
    {
        $order->loadMissing('items');

        $storeName = $store->store_name ?? 'Tata Grosir Solo';

        // ====== Rangkai detail item ======
        $itemsLines = [];
        foreach ($order->items as $it) {
            $lineTotal = $it->price_snapshot * $it->qty;
            $itemsLines[] = '- ' . $it->name_snapshot . ' x' . $it->qty .
                ' @ Rp ' . number_format($it->price_snapshot, 0, ',', '.') .
                ' = Rp ' . number_format($lineTotal, 0, ',', '.');
        }

        // ====== Rangkai blok ongkir ======
        $ongkirLabel = strtoupper($order->courier) . '-' . $order->service;
        if (!empty($order->etd)) $ongkirLabel .= ' / ETD ' . $order->etd;

        // ====== Rekening ======
        $bankLines = [
            'BCA - 1279174448 - a/n Tata Grosir',
            'BRI - 21092937371932 - a/n Tata Grosir',
        ];

        // ====== Pesan final ======
        $lines = [
            '--- ' . $storeName . ' ---',
            'Terimakasih telah berbelanja di ' . strtolower($storeName) . ',',
            'Detail order :',
            'No: #' . $order->id,
            'Tanggal: ' . $order->created_at->format('d-m-Y H:i'),
            'Nama: ' . $order->ship_name . ' (' . $order->ship_phone . ')',
            'Alamat: ' . $order->ship_address . ', ' . $order->ship_city . ', ' . $order->ship_province . ' ' . $order->ship_postal,
            '',
        ];

        if (!empty($itemsLines)) {
            $lines[] = 'Items:';
            $lines = array_merge($lines, $itemsLines);
        }

        $lines = array_merge($lines, [
            '',
            'Subtotal: Rp ' . number_format($order->subtotal, 0, ',', '.'),
            'Ongkir (' . $ongkirLabel . '): Rp ' . number_format($order->shipping_cost, 0, ',', '.'),
            'Total: Rp ' . number_format($order->grandtotal, 0, ',', '.'),
            '',
            'Harap lakukan pembayaran ke rekening berikut :',
        ]);

        $lines = array_merge($lines, $bankLines);

        $lines = array_merge($lines, [
            '',
            'Apabila sudah dilakukan transfer, silahkan balas pesan ini dengan bukti pembayaran dan akan dilakukan verifikasi oleh admin',
            '',
            'Terima kasih',
        ]);

        return implode("\n", $lines);
    }

    public function placeOrder(Request $r, FonnteService $wa)
    {
        $data = $r->validate([
            'receiver_name' => 'required|string|max:150',
            'phone'         => 'required|string|max:30',
            'address_line'  => 'required|string',

            'province'      => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'district'      => 'required|string|max:100',
            'postal_code'   => 'required|string|max:10',
            'destination_district_id' => 'required|integer',

            'courier'       => 'required|string',
            'service'       => 'required|string',
            'etd'           => 'nullable|string',
            'shipping_cost' => 'required|integer|min:0',
        ]);

        $user = $r->user();
        $cart = $user->cart()->with('items.product')->firstOrFail();

        $order = DB::transaction(function() use ($user,$data,$cart){
            $subtotal = $cart->items->sum(function($i){
                return $i->price_at * $i->qty;
            });

            $o = Order::create([
                'user_id' => $user->id,
                'ship_name'     => $data['receiver_name'],
                'ship_phone'    => $data['phone'],
                'ship_address'  => $data['address_line'].' ('.$data['district'].')',
                'ship_province' => $data['province'],
                'ship_city'     => $data['city'],
                'ship_postal'   => $data['postal_code'],
                'destination_city_id' => (int)$data['destination_district_id'], // reuse kolom; menyimpan district id

                'courier'       => $data['courier'],
                'service'       => $data['service'],
                'etd'           => $data['etd'] ?? null,
                'shipping_cost' => (int)$data['shipping_cost'],
                'subtotal'      => $subtotal,
                'discount'      => 0,
                'grandtotal'    => $subtotal + (int)$data['shipping_cost'],
                'status'        => 'pending',
            ]);

            foreach ($cart->items as $it){
                $p = Product::lockForUpdate()->find($it->product_id);
                if ($p->stock < $it->qty) throw new \RuntimeException('Stock not enough');
                $p->decrement('stock', $it->qty);
                OrderItem::create([
                    'order_id'=>$o->id,
                    'product_id'=>$p->id,
                    'name_snapshot'=>$p->name,
                    'price_snapshot'=>$p->price,
                    'weight_gram_snapshot'=>$p->weight_gram ?? 0,
                    'qty'=>$it->qty,
                ]);
            }
            $cart->items()->delete();
            return $o;
        });

        if ($user->wa_opt_in && $user->phone) {
            $store = StoreSetting::first();
            $msg   = $this->formatOrderWa($order, $store);
            $wa->sendText($user->phone, $msg, ['event' => 'ORDER_PLACED', 'order_id' => $order->id]);
        }

        return redirect()->route('orders.show', $order)->with('ok','Order created');
    }
}
