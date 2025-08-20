@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Tambah Alamat</h3>
  <form method="post" action="{{ route('addresses.store') }}">@csrf
    @include('addresses.partials.form')
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
