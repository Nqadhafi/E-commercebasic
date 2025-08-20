@extends('layouts.app')
@push('styles') <link rel="stylesheet" href="{{ mix('css/store.css') }}"> @endpush
@section('content')
@if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif
<div class="container">
  <form class="mb-3">
    <div class="input-group">
      <input name="s" class="form-control" placeholder="Cari produk..." value="{{ request('s') }}">
      <div class="input-group-append"><button class="btn btn-primary">Cari</button></div>
    </div>
  </form>

  <div class="row">
    @foreach($products as $p)
      <div class="col-6 col-md-3 mb-4">@include('components.product-card',['p'=>$p])</div>
    @endforeach
  </div>

  {{ $products->withQueryString()->links() }}
</div>
@endsection
