<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        $s = StoreSetting::first();
        return view('admin.settings.show', compact('s'));
    }

    public function update(Request $r)
    {
        $data = $r->validate([
            'store_name'=>'nullable|string|max:200',
            'store_phone'=>'nullable|string|max:30',
            'store_address'=>'nullable|string',
            'origin_city_id'=>'nullable|integer',
            'active_couriers'=>'nullable|array',
            'shipping_markup_flat'=>'nullable|integer|min:0',
            'rajaongkir_api_key'=>'nullable|string',
            'rajaongkir_account_type'=>'nullable|string',
            'fonnte_token'=>'nullable|string',
            'fonnte_sender'=>'nullable|string',
        ]);
        if (isset($data['active_couriers'])) {
            $data['active_couriers'] = array_values($data['active_couriers']);
        }
        $s = StoreSetting::firstOrCreate(['id'=>1]);
        $s->update($data);
        return back()->with('ok','Saved');
    }
}
