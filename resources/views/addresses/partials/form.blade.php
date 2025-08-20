@if ($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
@php $d = $d ?? null; @endphp
<div class="form-row">
  <div class="form-group col-md-4">
    <label>Label</label>
    <input name="label" class="form-control" value="{{ old('label',$d->label ?? '') }}" placeholder="Rumah/Kantor">
  </div>
  <div class="form-group col-md-4">
    <label>Penerima</label>
    <input name="receiver_name" class="form-control" value="{{ old('receiver_name',$d->receiver_name ?? '') }}" required>
  </div>
  <div class="form-group col-md-4">
    <label>HP</label>
    <input name="phone" class="form-control" value="{{ old('phone',$d->phone ?? '') }}" required>
  </div>
</div>
<div class="form-group">
  <label>Alamat</label>
  <textarea name="address_line" class="form-control" required>{{ old('address_line',$d->address_line ?? '') }}</textarea>
</div>
<div class="form-row">
  <div class="form-group col-md-4">
    <label>Provinsi</label>
    <input name="province" class="form-control" value="{{ old('province',$d->province ?? '') }}" required>
  </div>
  <div class="form-group col-md-4">
    <label>Kota/Kabupaten</label>
    <input name="city" class="form-control" value="{{ old('city',$d->city ?? '') }}" required>
  </div>
  <div class="form-group col-md-2">
    <label>Kode Pos</label>
    <input name="postal_code" class="form-control" value="{{ old('postal_code',$d->postal_code ?? '') }}" required>
  </div>
  <div class="form-group col-md-2">
    <label>City ID (RO)</label>
    <input type="number" name="rajaongkir_city_id" class="form-control" value="{{ old('rajaongkir_city_id',$d->rajaongkir_city_id ?? '') }}" required>
  </div>
</div>
<div class="form-group form-check">
  <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default',$d->is_default ?? true)?'checked':'' }}>
  <label class="form-check-label" for="is_default">Jadikan default</label>
</div>
