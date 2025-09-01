@extends('layouts.app')
@push('styles') <link rel="stylesheet" href="{{ mix('css/store.css') }}"> @endpush
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-5">
      @php $img = 'storage/'.$product->thumbnail ? asset('public/'.$product->thumbnail) : 'https://via.placeholder.com/800x600?text=Product'; @endphp
      @dd($product->thumbnail)
      <div class="ratio ratio-4x3 product-hero" style="background-image:url('{{ $img }}')"></div>
    </div>
    <div class="col-md-7">
      <h3 class="mb-2">{{ $product->name }}</h3>
      <div class="h4 mb-3">@rupiah($product->price)</div>
      <div class="mb-3"><small>Berat: {{ $product->weight_gram }} gram</small></div>
      <div class="mb-3"><small>Stok: {{ $product->stock }}</small></div>
      <form method="post" action="{{ route('cart.add') }}">@csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="form-inline mb-3">
          <label class="mr-2">Qty</label>
          <input type="number" name="qty" class="form-control mr-2" value="1" min="1" style="width:100px">
          <button class="btn btn-primary">Tambah ke Keranjang</button>
        </div>
      </form>
      <a href="{{ route('catalog.index') }}" class="btn btn-link">Kembali ke katalog</a>
    </div>
  </div>
</div>
@endsection
