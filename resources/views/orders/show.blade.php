@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Order #{{ $order->id }}</h3>
  <div class="row">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">Pengiriman</div>
        <div class="card-body">
          <div>{{ $order->ship_name }} ({{ $order->ship_phone }})</div>
          <div>{{ $order->ship_address }}, {{ $order->ship_city }}, {{ $order->ship_province }} {{ $order->ship_postal }}</div>
          <div>Kurir: {{ strtoupper($order->courier) }} - {{ $order->service }} | ETD: {{ $order->etd }}</div>
          <div>Resi: {{ $order->resi ?: '-' }}</div>
          <div>Status: <b class="text-uppercase">{{ $order->status }}</b></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">Ringkasan</div>
        <div class="card-body">
          <div>Subtotal: Rp {{ number_format($order->subtotal) }}</div>
          <div>Ongkir: Rp {{ number_format($order->shipping_cost) }}</div>
          <div>Total: <b>Rp {{ number_format($order->grandtotal) }}</b></div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header">Items</div>
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
        <tbody>
          @foreach($order->items as $it)
          <tr>
            <td>{{ $it->name_snapshot }}</td>
            <td>{{ $it->qty }}</td>
            <td>Rp {{ number_format($it->price_snapshot) }}</td>
            <td>Rp {{ number_format($it->price_snapshot * $it->qty) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
