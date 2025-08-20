@php
  $img = $p->thumbnail ? asset('storage/'.$p->thumbnail) : 'https://via.placeholder.com/600x400?text=Product';
@endphp
<div class="card product-card h-100">
  <div class="product-thumb" style="background-image:url('{{ $img }}')"></div>
  <div class="card-body d-flex flex-column">
    <h6 class="mb-1 product-name" title="{{ $p->name }}">{{ $p->name }}</h6>
    <div class="mb-2 product-price">@rupiah($p->price)</div>
    <div class="mb-2 product-stock">Stok: {{ $p->stock }}</div>
    <div class="mb-2 product-weight">Berat: {{ $p->weight_gram }} gram</div>
    @if($p->stock <= 5) <span class="badge badge-warning mb-2">Stok menipis</span> @endif
    <a href="{{ route('catalog.show',$p) }}" class="btn btn-outline-primary btn-sm mt-auto">Detail</a>
  </div>
</div>
