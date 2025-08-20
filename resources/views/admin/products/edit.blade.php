@extends('adminlte::page')
@section('title','Edit Product')
@section('content_header') <h1>Edit Product</h1> @endsection
@section('content')
<form method="post" action="{{ route('admin.products.update',$product) }}" enctype="multipart/form-data">@csrf @method('put')
  @include('admin.products.partials.form',['data'=>$product])
  <button class="btn btn-primary">Update</button>
  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
</form>
@endsection
