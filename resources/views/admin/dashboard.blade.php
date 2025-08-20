@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header') <h1>Dashboard</h1> @endsection
@section('content')
@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
<div class="row">
  <div class="col-md-3"><div class="small-box bg-info"><div class="inner"><h3>{{ $stats['orders_today'] }}</h3><p>Orders Today</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-warning"><div class="inner"><h3>{{ $stats['orders_pending'] }}</h3><p>Pending</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-success"><div class="inner"><h3>Rp {{ number_format($stats['revenue_month']) }}</h3><p>Revenue (Month)</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-danger"><div class="inner"><h3>{{ $stats['low_stock'] }}</h3><p>Low Stock</p></div></div></div>
</div>
@endsection
