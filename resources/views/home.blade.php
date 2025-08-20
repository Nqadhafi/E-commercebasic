@extends('layouts.app')
@section('content')
<div class="home-hero" style="background-image: linear-gradient(180deg,rgba(0,0,0,.25),rgba(0,0,0,.35)), url('{{ $hero }}');">
  <div class="container">
    <div class="hero-inner">
      <h1 class="hero-title">{{ $name }}</h1>
      <p class="hero-tag">{{ $tagline }}</p>
      <div class="hero-cta">
        <a class="btn btn-primary btn-lg" href="{{ route('catalog.index') }}">Belanja Sekarang</a>
        <a class="btn btn-outline-light btn-lg" href="tel:{{ $phone }}">Hubungi: {{ $phone }}</a>
      </div>
    </div>
  </div>
</div>

<div class="info-strip">
  <div class="container">
    <div class="row text-center">
      <div class="col-12 col-md-4 mb-2 mb-md-0">
        <div class="info-item">
          <div class="info-title">Alamat Toko</div>
          <div class="info-text">{{ $address }}</div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-2 mb-md-0">
        <div class="info-item">
          <div class="info-title">Telepon</div>
          <div class="info-text">{{ $phone }}</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-item">
          <div class="info-title">Jam Layanan</div>
          <div class="info-text">Setiap hari 08.00–20.00</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-4">
  <div class="row g-3 features mb-4">
    <div class="col-12 col-md-4">
      <div class="feature-card">
        <div class="feature-title">Cek Ongkir Otomatis</div>
        <div class="feature-text">Integrasi RajaOngkir Komerce untuk tarif akurat.</div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="feature-card">
        <div class="feature-title">Notifikasi WhatsApp</div>
        <div class="feature-text">Update status pesanan via Fonnte.</div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="feature-card">
        <div class="feature-title">Belanja dari Rumah</div>
        <div class="feature-text">Pesan online, pembayaran mudah, kirim cepat.</div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Produk Unggulan</h4>
    <a href="{{ route('catalog.index') }}" class="btn btn-link">Lihat semua</a>
  </div>
  <div class="row">
    @forelse($featured as $p)
      <div class="col-6 col-md-3 mb-4">@include('components.product-card',['p'=>$p])</div>
    @empty
      <div class="col-12"><div class="alert alert-info">Belum ada produk.</div></div>
    @endforelse
  </div>
</div>
@endsection
