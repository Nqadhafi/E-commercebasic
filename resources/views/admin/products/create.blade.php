@extends('adminlte::page')
@section('title','Create Product')
@section('content_header') <h1>Create Product</h1> @endsection
@section('content')
<form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">@csrf
  @include('admin.products.partials.form')
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
</form>
@endsection
