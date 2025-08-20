@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Pesanan Saya</h3>
  <table class="table">
    <thead><tr><th>#</th><th>Total</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
    <tbody>
      @foreach($orders as $o)
      <tr>
        <td>#{{ $o->id }}</td>
        <td>Rp {{ number_format($o->grandtotal) }}</td>
        <td><span class="badge badge-info text-uppercase">{{ $o->status }}</span></td>
        <td>{{ $o->created_at->format('d-m-Y H:i') }}</td>
        <td><a href="{{ route('orders.show',$o) }}" class="btn btn-sm btn-primary">Detail</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $orders->links() }}
</div>
@endsection
