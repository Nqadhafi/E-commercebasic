@if ($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

@php $d = $data ?? null; @endphp
<div class="form-row">
  <div class="form-group col-md-6">
    <label>Name</label>
    <input name="name" class="form-control" value="{{ old('name',$d->name ?? '') }}" required>
  </div>
  <div class="form-group col-md-3">
    <label>SKU</label>
    <input name="sku" class="form-control" value="{{ old('sku',$d->sku ?? '') }}">
  </div>
  <div class="form-group col-md-3">
    <label>Price (Rp)</label>
    <input type="number" name="price" class="form-control" value="{{ old('price',$d->price ?? 0) }}" min="0" required>
  </div>
</div>
<div class="form-row">
  <div class="form-group col-md-3">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control" value="{{ old('stock',$d->stock ?? 0) }}" min="0" required>
  </div>
  <div class="form-group col-md-3">
    <label>Weight (gram)</label>
    <input type="number" name="weight_gram" class="form-control" value="{{ old('weight_gram',$d->weight_gram ?? 0) }}" min="0">
  </div>
  <div class="form-group col-md-3">
    <label>Thumbnail</label>
    <input type="file" name="thumbnail" class="form-control-file">
    @if($d && $d->thumbnail) <img src="{{ asset('storage/'.$d->thumbnail) }}" class="mt-2" style="height:60px"> @endif
  </div>
  <div class="form-group col-md-3">
    <label>Active</label><br>
    <input type="checkbox" name="is_active" value="1" {{ old('is_active',$d->is_active ?? true)?'checked':'' }}>
  </div>
</div>
