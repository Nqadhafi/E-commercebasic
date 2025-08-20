<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StoreSetting;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $r)
    {
        $q = Order::query();
        if ($r->filled('status')) $q->where('status',$r->status);
        if ($r->filled('s')) $q->where('id',$r->s);
        $orders = $q->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

public function show(\App\Models\Order $order)
{
    $order->load('items');
    return view('admin.orders.show', compact('order'));
}

protected function formatOrderStatusWa(\App\Models\Order $order, ?StoreSetting $store, string $status): string
{
    $order->loadMissing('items');

    $storeName = $store && $store->store_name ? $store->store_name : 'Tata Grosir Solo';

    $lines = [];
    $lines[] = '--- '.$storeName.' ---';
    $lines[] = 'Terimakasih telah berbelanja di '.strtolower($storeName).',';
    $lines[] = 'Detail order :';
    $lines[] = 'No: #'.$order->id;
    $lines[] = 'Tanggal: '.$order->created_at->format('d-m-Y H:i');
    $lines[] = 'Nama: '.$order->ship_name.' ('.$order->ship_phone.')';
    $lines[] = 'Alamat: '.$order->ship_address.', '.$order->ship_city.', '.$order->ship_province.' '.$order->ship_postal;
    $lines[] = '';

    // Items
    if ($order->items && $order->items->count()) {
        $lines[] = 'Items:';
        foreach ($order->items as $it) {
            $lineTotal = $it->price_snapshot * $it->qty;
            $lines[] = '- '.$it->name_snapshot.' x'.$it->qty.
                       ' @ Rp '.number_format($it->price_snapshot,0,',','.').
                       ' = Rp '.number_format($lineTotal,0,',','.');
        }
        $lines[] = '';
    }

    // Ringkasan
    $ongkirLabel = strtoupper($order->courier).'-'.$order->service;
    if (!empty($order->etd)) {
        $ongkirLabel .= ' / ETD '.$order->etd;
    }
    $lines[] = 'Subtotal: Rp '.number_format($order->subtotal,0,',','.');
    $lines[] = 'Ongkir ('.$ongkirLabel.'): Rp '.number_format($order->shipping_cost,0,',','.');
    $lines[] = 'Total: Rp '.number_format($order->grandtotal,0,',','.');
    $lines[] = '';

    // Blok status (switch-compatible PHP 7)
    switch ($status) {
        case 'paid':
            $lines[] = 'Status: DIBAYAR';
            $lines[] = 'Pembayaran Anda sudah kami terima. Pesanan masuk antrian proses.';
            $lines[] = '';
            break;

        case 'processing':
            $lines[] = 'Status: DIPROSES';
            $lines[] = 'Pesanan sedang diproses dan disiapkan untuk pengiriman.';
            $lines[] = '';
            break;

        case 'shipped':
            $lines[] = 'Status: DIKIRIM';
            $lines[] = 'Pesanan telah dikirim via '.$ongkirLabel.'.';
            $lines[] = 'Resi: '.($order->resi ?: '-');
            $lines[] = '';
            break;

        case 'completed':
            $lines[] = 'Status: SELESAI';
            $lines[] = 'Pesanan telah selesai. Terima kasih sudah berbelanja di '.strtolower($storeName).'.';
            $lines[] = '';
            break;

        case 'canceled':
            $lines[] = 'Status: DIBATALKAN';
            $lines[] = 'Pesanan dibatalkan. Jika ini tidak sesuai, silakan balas pesan ini.';
            $lines[] = '';
            break;

        default: // pending & lainnya
            $lines[] = 'Status: MENUNGGU PEMBAYARAN';
            $lines[] = 'Harap lakukan pembayaran sesuai total di atas.';
            $lines[] = '';
            $lines[] = 'Harap lakukan pembayaran ke rekening berikut :';
            $lines[] = 'BCA - 1279174448 - a/n Tata Grosir';
            $lines[] = 'BRI - 21092937371932 - a/n Tata Grosir';
            $lines[] = '';
            $lines[] = 'Apabila sudah dilakukan transfer, silahkan balas pesan ini dengan bukti pembayaran dan akan dilakukan verifikasi oleh admin';
            $lines[] = '';
            break;
    }

    $lines[] = 'Terima kasih';
    return implode("\n", $lines);
}



public function updateStatus(Request $r, \App\Models\Order $order, \App\Services\FonnteService $wa)
{
    $data = $r->validate([
        'status'=>'required|in:pending,paid,processing,shipped,completed,canceled',
        'resi'=>'nullable|string|max:100'
    ]);

    $order->fill($data)->save();

    // WA template status
    if ($order->user && $order->user->wa_opt_in && $order->user->phone) {
        $store = StoreSetting::first();
        $msg = $this->formatOrderStatusWa($order, $store, $data['status']);
        $wa->sendText($order->user->phone, $msg, [
            'event'=>'ORDER_STATUS',
            'order_id'=>$order->id,
            'status'=>$order->status
        ]);
    }

    return back()->with('ok','Status updated');
}

public function resendWa(\App\Models\Order $order, \App\Services\FonnteService $wa)
{
    if ($order->user && $order->user->wa_opt_in && $order->user->phone) {
        $store = StoreSetting::first();
        $msg = $this->formatOrderStatusWa($order->loadMissing('items'), $store, $order->status);
        $wa->sendText($order->user->phone, $msg, [
            'event'=>'ORDER_STATUS_RESEND',
            'order_id'=>$order->id,
            'status'=>$order->status
        ]);
    }
    return back()->with('ok','WA sent');
}

}
