@extends('adminlte::page')
@section('title','Orders')
@section('content_header') <h1>Orders</h1> @endsection
@section('content')
@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
<form class="form-inline mb-3">
  <select name="status" class="form-control mr-2">
    <option value="">-- status --</option>
    @foreach(['pending','paid','processing','shipped','completed','canceled'] as $s)
      <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
    @endforeach
  </select>
  <input type="text" name="s" class="form-control mr-2" placeholder="Order ID" value="{{ request('s') }}">
  <button class="btn btn-primary">Filter</button>
</form>
<table class="table table-bordered">
  <thead><tr><th>#</th><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Updated</th><th></th></tr></thead>
  <tbody>
  @foreach($orders as $o)
    <tr>
      <td>{{ $orders->firstItem()+$loop->index }}</td>
      <td>#{{ $o->id }}</td>
      <td>{{ $o->ship_name }}<br><small>{{ $o->ship_phone }}</small></td>
      <td>Rp {{ number_format($o->grandtotal) }}</td>
      <td><span class="badge badge-info text-uppercase">{{ $o->status }}</span></td>
      <td>{{ $o->updated_at->format('d-m-Y H:i') }}</td>
      <td><a href="{{ route('admin.orders.show',$o) }}" class="btn btn-sm btn-primary">Detail</a></td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $orders->links() }}
@endsection
