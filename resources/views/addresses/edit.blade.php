@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Edit Alamat</h3>
  <form method="post" action="{{ route('addresses.update',$address) }}">@csrf @method('put')
    @include('addresses.partials.form',['d'=>$address])
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
