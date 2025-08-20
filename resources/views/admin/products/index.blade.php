@extends('adminlte::page')
@section('title','Products')
@section('content_header')
  <div class="d-flex justify-content-between align-items-center">
    <h1>Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create</a>
  </div>
@endsection
@section('content')
@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
<table class="table table-striped">
  <thead><tr><th>#</th><th>Name</th><th>SKU</th><th>Price</th><th>Stock</th><th>Active</th><th></th></tr></thead>
  <tbody>
    @foreach($q as $i=>$p)
    <tr>
      <td>{{ $q->firstItem()+$i }}</td>
      <td>{{ $p->name }}</td>
      <td>{{ $p->sku }}</td>
      <td>Rp {{ number_format($p->price) }}</td>
      <td>{{ $p->stock }}</td>
      <td>{!! $p->is_active ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>' !!}</td>
      <td class="text-right">
        <a href="{{ route('admin.products.edit',$p) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('admin.products.destroy',$p) }}" method="post" class="d-inline">@csrf @method('delete')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Del</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $q->links() }}
@endsection
