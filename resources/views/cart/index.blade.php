@extends('layouts.app')
@section('content')
<div class="container">
@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
<h3>Cart</h3>
@if(!$cart || $cart->items->isEmpty())
  <div class="alert alert-info">Cart empty</div>
@else
<table class="table">
  <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
  <tbody>
    @foreach($cart->items as $it)
    <tr>
      <td>{{ $it->product->name ?? '-' }}</td>
      <td>Rp {{ number_format($it->price_at) }}</td>
      <td>
        <form method="post" action="{{ route('cart.update',$it) }}" class="form-inline">@csrf @method('put')
          <input type="number" name="qty" class="form-control form-control-sm mr-2" value="{{ $it->qty }}" min="1">
          <button class="btn btn-sm btn-outline-primary">Update</button>
        </form>
      </td>
      <td>Rp {{ number_format($it->price_at * $it->qty) }}</td>
      <td>
        <form method="post" action="{{ route('cart.remove',$it) }}">@csrf @method('delete')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove?')">Remove</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<div class="d-flex justify-content-between">
  <form method="post" action="{{ route('cart.clear') }}">@csrf @method('delete')
    <button class="btn btn-outline-secondary">Clear</button>
  </form>
  <a href="{{ route('checkout.show') }}" class="btn btn-primary">Checkout</a>
</div>
@endif
</div>
@endsection
