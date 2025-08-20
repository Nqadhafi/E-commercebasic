@extends('layouts.app')
@section('content')
<div class="container">
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Alamat Saya</h3>
    <a href="{{ route('addresses.create') }}" class="btn btn-primary">Tambah Alamat</a>
  </div>
  @forelse($addresses as $a)
    <div class="card mb-2">
      <div class="card-body d-flex justify-content-between">
        <div>
          <div><b>{{ $a->receiver_name }}</b> ({{ $a->phone }})</div>
          <div>{{ $a->address_line }}, {{ $a->city }}, {{ $a->province }} {{ $a->postal_code }}</div>
          <small>City ID: {{ $a->rajaongkir_city_id }}</small>
          @if($a->is_default) <span class="badge badge-success ml-2">Default</span> @endif
        </div>
        <div>
          <a href="{{ route('addresses.edit',$a) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('addresses.destroy',$a) }}" method="post" class="d-inline">@csrf @method('delete')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus alamat?')">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-info">Belum ada alamat.</div>
  @endforelse
</div>
@endsection
