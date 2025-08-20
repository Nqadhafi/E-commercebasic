@extends('adminlte::page')
@section('title','Settings')
@section('content_header') <h1>Settings</h1> @endsection
@section('content')
@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
<form method="post" action="{{ route('admin.settings.update') }}">@csrf @method('put')
<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header"><strong>Store</strong></div>
      <div class="card-body">
        <div class="form-group"><label>Name</label><input name="store_name" class="form-control" value="{{ old('store_name',$s->store_name??'') }}"></div>
        <div class="form-group"><label>Phone</label><input name="store_phone" class="form-control" value="{{ old('store_phone',$s->store_phone??'') }}"></div>
        <div class="form-group"><label>Address</label><textarea name="store_address" class="form-control">{{ old('store_address',$s->store_address??'') }}</textarea></div>
      </div>
    </div>
    <div class="card card-outline card-success">
      <div class="card-header"><strong>Fonnte (WhatsApp)</strong></div>
      <div class="card-body">
        <div class="form-group"><label>Token</label><input name="fonnte_token" class="form-control" value="{{ old('fonnte_token',$s->fonnte_token??'') }}"></div>
        <div class="form-group"><label>Sender</label><input name="fonnte_sender" class="form-control" value="{{ old('fonnte_sender',$s->fonnte_sender??'') }}"></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card card-outline card-info">
      <div class="card-header"><strong>RajaOngkir (Komerce)</strong></div>
      <div class="card-body">
        <div class="form-group"><label>API Key</label><input name="rajaongkir_api_key" class="form-control" value="{{ old('rajaongkir_api_key',$s->rajaongkir_api_key??'') }}"></div>
        <div class="form-group"><label>Account Type</label>
          <select name="rajaongkir_account_type" class="form-control">
            @foreach(['starter','basic','pro'] as $t)
              <option value="{{ $t }}" @selected(($s->rajaongkir_account_type??'starter')===$t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
  <label>Origin District ID</label>
  <input type="number" name="origin_district_id" class="form-control"
         value="{{ old('origin_district_id',$s->origin_district_id??'') }}">
</div>
        <div class="form-group"><label>Origin City ID</label><input type="number" name="origin_city_id" class="form-control" value="{{ old('origin_city_id',$s->origin_city_id??'') }}"></div>
        <div class="form-group"><label>Active Couriers</label>
          @php $acs = $s->active_couriers ?? []; @endphp
          <div class="form-check"><input class="form-check-input" type="checkbox" name="active_couriers[]" value="jne" {{ in_array('jne',$acs)?'checked':'' }}> <label class="form-check-label">JNE</label></div>
          <div class="form-check"><input class="form-check-input" type="checkbox" name="active_couriers[]" value="pos" {{ in_array('pos',$acs)?'checked':'' }}> <label class="form-check-label">POS</label></div>
          <div class="form-check"><input class="form-check-input" type="checkbox" name="active_couriers[]" value="tiki" {{ in_array('tiki',$acs)?'checked':'' }}> <label class="form-check-label">TIKI</label></div>
        </div>
        <div class="form-group"><label>Shipping Markup (flat Rp)</label><input type="number" name="shipping_markup_flat" class="form-control" value="{{ old('shipping_markup_flat',$s->shipping_markup_flat??0) }}"></div>
      </div>
    </div>
  </div>
</div>
<button class="btn btn-primary">Save</button>
</form>
@endsection
