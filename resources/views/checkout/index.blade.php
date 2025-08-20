@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Checkout</h3>
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <div class="row">
    <div class="col-md-6">
      <h5>Data Pengiriman</h5>
      <div class="form-group">
        <label>Nama Penerima</label>
        <input id="receiver_name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>No. HP</label>
        <input id="phone" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Alamat</label>
        <textarea id="address_line" class="form-control" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Provinsi</label>
          <select id="province_id" class="form-control" required></select>
        </div>
        <div class="form-group col-md-6">
          <label>Kota/Kabupaten</label>
          <select id="city_id" class="form-control" required disabled></select>
        </div>
      </div>
      <div class="form-group">
        <label>Kecamatan (District)</label>
        <select id="district_id" class="form-control" required disabled></select>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Nama Provinsi</label>
          <input id="province_name" class="form-control" readonly>
        </div>
        <div class="form-group col-md-4">
          <label>Nama Kota/Kab</label>
          <input id="city_name" class="form-control" readonly>
        </div>
        <div class="form-group col-md-4">
          <label>Nama Kecamatan</label>
          <input id="district_name" class="form-control" readonly>
        </div>
      </div>

      <div class="form-group">
        <label>Kode Pos</label>
        <input id="postal_code" class="form-control" required>
      </div>

      @if(isset($originDistrictId))
      <div class="alert alert-light">
        <div><b>Asal pengiriman:</b> {{ $originDistrictName ? $originDistrictName : '—' }} {!! $originDistrictId ? '(ID '.$originDistrictId.')' : '' !!}</div>
      </div>
      @endif

      <h5>Kurir</h5>
      <select id="courier" class="form-control mb-3">
        <option value="jne">JNE</option>
        <option value="pos">POS</option>
        <option value="tiki">TIKI</option>
      </select>

      <button id="btnCheck" class="btn btn-outline-primary mb-3" disabled>Cek Ongkir</button>

      <div id="services" class="mb-3" style="display:none">
        <label>Layanan</label>
        <select id="service" class="form-control"></select>
      </div>
    </div>

    <div class="col-md-6">
      {{-- Detail Barang Dipesan --}}
      <h5>Detail Barang</h5>
      @php
        $items = $cart->items;
        $subtotal = $items->sum(fn($i)=> $i->price_at * $i->qty);
        $totalWeightLocal = $items->sum(fn($i)=> ($i->product->weight_gram ?? 0) * $i->qty);
      @endphp
      <div class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead class="thead-light">
            <tr>
              <th>Produk</th>
              <th class="text-right">Qty</th>
              <th class="text-right">Harga</th>
              <th class="text-right">Berat/pcs (gr)</th>
              <th class="text-right">Berat (gr)</th>
              <th class="text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
          @foreach($items as $it)
            @php
              $w = (int)($it->product->weight_gram ?? 0);
              $lineWeight = $w * (int)$it->qty;
              $lineTotal  = $it->price_at * $it->qty;
            @endphp
            <tr>
              <td>{{ $it->product->name }}</td>
              <td class="text-right">{{ $it->qty }}</td>
              <td class="text-right">Rp {{ number_format($it->price_at,0,',','.') }}</td>
              <td class="text-right">{{ number_format($w,0,',','.') }}</td>
              <td class="text-right">{{ number_format($lineWeight,0,',','.') }}</td>
              <td class="text-right">Rp {{ number_format($lineTotal,0,',','.') }}</td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-right">Total Berat</th>
              <th class="text-right">
                {{ number_format($totalWeightLocal,0,',','.') }} gr
                <small class="text-muted">(~ {{ number_format($totalWeightLocal/1000,2,',','.') }} kg)</small>
              </th>
              <th class="text-right">Rp {{ number_format($subtotal,0,',','.') }}</th>
            </tr>
          </tfoot>
        </table>
      </div>

      {{-- Ringkasan --}}
      <h5>Ringkasan</h5>
      <ul class="list-group mb-3">
        <li class="list-group-item d-flex justify-content-between">
          <span>Subtotal</span><b id="subtotal">Rp {{ number_format($subtotal,0,',','.') }}</b>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>Ongkir</span><b id="ongkir">Rp 0</b>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>Total</span><b id="total">Rp {{ number_format($subtotal,0,',','.') }}</b>
        </li>
      </ul>

      <form id="placeForm" method="post" action="{{ route('checkout.place') }}">@csrf
        <input type="hidden" name="receiver_name" id="f_receiver_name">
        <input type="hidden" name="phone" id="f_phone">
        <input type="hidden" name="address_line" id="f_address_line">
        <input type="hidden" name="province" id="f_province">
        <input type="hidden" name="city" id="f_city">
        <input type="hidden" name="postal_code" id="f_postal_code">
        <input type="hidden" name="destination_city_id" id="f_destination_city_id"><!-- legacy -->
        <input type="hidden" name="district" id="f_district">
        <input type="hidden" name="destination_district_id" id="f_destination_district_id">
        <input type="hidden" name="courier" id="f_courier">
        <input type="hidden" name="service" id="f_service">
        <input type="hidden" name="etd" id="f_etd">
        <input type="hidden" name="shipping_cost" id="f_cost">
        <button class="btn btn-primary btn-block" disabled id="btnPlace">Buat Pesanan</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
const rupiah = n => new Intl.NumberFormat('id-ID').format(n);
const $prov = document.getElementById('province_id');
const $city = document.getElementById('city_id');
const $dist = document.getElementById('district_id');
const $provNm = document.getElementById('province_name');
const $cityNm = document.getElementById('city_name');
const $distNm = document.getElementById('district_name');
const $btnChk = document.getElementById('btnCheck');
const $svcWrap = document.getElementById('services');
const $svcSel = document.getElementById('service');
const $courier = document.getElementById('courier');
const subtotal = {{ $subtotal }};
const $ongkir = document.getElementById('ongkir');
const $total = document.getElementById('total');
const $btnPlace = document.getElementById('btnPlace');

const $h = {
  receiver_name: document.getElementById('f_receiver_name'),
  phone: document.getElementById('f_phone'),
  address_line: document.getElementById('f_address_line'),
  province: document.getElementById('f_province'),
  city: document.getElementById('f_city'),
  district: document.getElementById('f_district'),
  postal_code: document.getElementById('f_postal_code'),
  destination_district_id: document.getElementById('f_destination_district_id'),
  courier: document.getElementById('f_courier'),
  service: document.getElementById('f_service'),
  etd: document.getElementById('f_etd'),
  cost: document.getElementById('f_cost'),
};

async function jget(url){
  const r = await fetch(url, {headers:{'Accept':'application/json'}});
  return r.json();
}
async function jpost(url, body){
  const r = await fetch(url,{method:'POST',headers:{
    'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json','Content-Type':'application/json'
  }, body: JSON.stringify(body)});
  return r.json();
}

// provinces
(async () => {
  const js = await jget(`{{ route('checkout.provinces') }}`);
  $prov.innerHTML = '<option value="">-- pilih provinsi --</option>';
  (js.data||[]).forEach(p=>{
    const o = document.createElement('option'); o.value=p.id; o.textContent=p.name; $prov.appendChild(o);
  });
})();

$prov.addEventListener('change', async (e)=>{
  const pid = e.target.value; $provNm.value = e.target.selectedOptions[0]?.text || '';
  $city.innerHTML=''; $city.disabled=true; $cityNm.value='';
  $dist.innerHTML=''; $dist.disabled=true; $distNm.value='';
  $btnChk.disabled = true; $svcWrap.style.display='none'; $svcSel.innerHTML='';

  if(!pid) return;
  const js = await jget(`{{ route('checkout.cities') }}?province_id=${pid}`);
  $city.innerHTML = '<option value="">-- pilih kota/kab --</option>';
  (js.data||[]).forEach(c=>{
    const typ = c.type || c.city_type || c.type_name || '';
    const label = (typ ? typ + ' ' : '') + (c.name || c.city_name || '');
    const o = document.createElement('option');
    o.value = c.id;
    o.textContent = label;
    o.dataset.name = c.name || c.city_name || '';
    o.dataset.type = typ;
    $city.appendChild(o);
  });
  $city.disabled=false;
});

$city.addEventListener('change', async (e)=>{
  const opt = e.target.selectedOptions[0];
  const typ = opt?.dataset.type || '';
  const nm  = opt?.dataset.name || opt?.textContent || '';
  const display = (typ ? typ + ' ' : '') + nm;
  $cityNm.value = display.trim();

  $dist.innerHTML=''; $dist.disabled=true; $distNm.value='';
  $btnChk.disabled = true; $svcWrap.style.display='none'; $svcSel.innerHTML='';

  if(!opt?.value) return;
  const js = await jget(`{{ route('checkout.districts') }}?city_id=${opt.value}`);
  $dist.innerHTML = '<option value="">-- pilih kecamatan --</option>';
  (js.data||[]).forEach(d=>{
    const o=document.createElement('option'); o.value=d.id; o.textContent=d.name; o.dataset.name=d.name; $dist.appendChild(o);
  });
  $dist.disabled=false;
});

$dist.addEventListener('change', e=>{
  const opt = e.target.selectedOptions[0];
  $distNm.value = opt ? opt.dataset.name : '';
  $btnChk.disabled = !opt || !opt.value;
});

// cek ongkir (DISTRICT)
document.getElementById('btnCheck').addEventListener('click', async ()=>{
  const did = parseInt($dist.value||'0',10);
  const courier = $courier.value;
  const js = await jpost(`{{ route('checkout.shipping') }}`, { destination_district_id: did, courier });
  const list = js.data || [];
  $svcSel.innerHTML='';
  list.forEach(it=>{
    const o=document.createElement('option');
    o.value=it.service; o.dataset.cost=it.cost; o.dataset.etd=it.etd||'';
    o.textContent=`${it.code.toUpperCase()} - ${it.service} (Rp ${new Intl.NumberFormat('id-ID').format(it.cost)})`;
    $svcSel.appendChild(o);
  });
  $svcWrap.style.display = list.length?'block':'none';
  updateTotals();
});

$svcSel.addEventListener('change', updateTotals);
['receiver_name','phone','address_line','postal_code'].forEach(id=>{
  document.getElementById(id).addEventListener('input', updateTotals);
});
$courier.addEventListener('change', ()=>{ $svcWrap.style.display='none'; $svcSel.innerHTML=''; $btnChk.click(); });

function updateTotals(){
  const opt = $svcSel.selectedOptions[0];
  const cost = opt ? parseInt(opt.dataset.cost) : 0;
  $ongkir.innerText = 'Rp '+ new Intl.NumberFormat('id-ID').format(cost);
  $total.innerText  = 'Rp '+ new Intl.NumberFormat('id-ID').format(subtotal + cost);

  $h.receiver_name.value = document.getElementById('receiver_name').value;
  $h.phone.value         = document.getElementById('phone').value;
  $h.address_line.value  = document.getElementById('address_line').value;
  $h.province.value      = $provNm.value;
  $h.city.value          = $cityNm.value;
  $h.district.value      = $distNm.value;
  $h.postal_code.value   = document.getElementById('postal_code').value;

  $h.destination_district_id.value = $dist.value || '';
  $h.courier.value = $courier.value;
  $h.service.value = opt ? opt.value : '';
  $h.etd.value     = opt ? opt.dataset.etd : '';
  $h.cost.value    = cost;

  const ready = !!(opt && $dist.value && $h.receiver_name.value && $h.phone.value && $h.address_line.value && $h.postal_code.value);
  $btnPlace.disabled = !ready;
}
</script>
@endpush
@endsection
