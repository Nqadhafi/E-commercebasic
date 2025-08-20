@extends('adminlte::page')
@section('title','Order #'.$order->id)
@section('content_header') <h1>Order #{{ $order->id }}</h1> @endsection
@section('content')
@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header"><strong>Customer & Shipping</strong></div>
      <div class="card-body">
        <p><b>{{ $order->ship_name }}</b> ({{ $order->ship_phone }})</p>
        <p>{{ $order->ship_address }}, {{ $order->ship_city }}, {{ $order->ship_province }} {{ $order->ship_postal }}</p>
        <p>Kurir: {{ strtoupper($order->courier) }} - {{ $order->service }}@if($order->etd) | ETD: {{ $order->etd }} @endif</p>
        <p>Resi: {{ $order->resi ?: '-' }}</p>
      </div>
    </div>

    <div class="card card-outline card-secondary">
      <div class="card-header"><strong>Ubah Status</strong></div>
      <div class="card-body">
        <form method="post" action="{{ route('admin.orders.updateStatus',$order) }}">@csrf @method('put')
          <div class="form-row">
            <div class="form-group col-md-5">
              <select name="status" class="form-control">
                @foreach(['pending','paid','processing','shipped','completed','canceled'] as $s)
                  <option value="{{ $s }}" @selected($order->status===$s)>{{ $s }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-5">
              <input name="resi" class="form-control" placeholder="Resi (opsional)" value="{{ $order->resi }}">
            </div>
            <div class="form-group col-md-2">
              <button class="btn btn-primary btn-block">Save</button>
            </div>
          </div>
        </form>
        <form method="post" action="{{ route('admin.orders.resendWa',$order) }}" class="mt-2">@csrf
          <button class="btn btn-outline-success btn-sm">Resend WA</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-outline card-info">
      <div class="card-header"><strong>Items</strong></div>
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
      <div class="card-footer">
        <div>Subtotal: <b>Rp {{ number_format($order->subtotal) }}</b></div>
        <div>Ongkir: <b>Rp {{ number_format($order->shipping_cost) }}</b></div>
        <div>Total: <b>Rp {{ number_format($order->grandtotal) }}</b></div>
        <div>Status: <span class="badge badge-info text-uppercase">{{ $order->status }}</span></div>
      </div>
    </div>
  </div>
</div>
@endsection
